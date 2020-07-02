@extends ('layouts.admin')
@section ('contenido')

<p type="hidden" {{$rol = Auth::user()->role }}></p>
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de Ingresos 
			@if($rol == 'Administrador' || $rol == 'Operador')
				<a href="ingreso/create"><button class="btn btn-success">Nuevo</button></a> 
			@endif
			@if($rol == 'Administrador' || $rol == 'Gerente' || $rol == 'Operador')
				<a href="{{url('reporteingresos')}}" target="_blank"><button class="btn btn-info">Reporte</button></a></h3>
			@endif
			@include('compras.ingreso.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Fecha</th>
					<th>Proveedor</th>
					<th>Comprobante</th>
					<th>Impuesto</th>
					<th>Total</th>
					<th>Estado</th>
					<th>Opciones</th>
				</thead>
               @foreach ($ingresos as $ing)
				<tr>
					<td>{{ $ing->fecha_hora}}</td>
					<td>{{ $ing->nombre}}</td>
					<td>{{ $ing->tipo_comprobante.': '.$ing->serie_comprobante.'-'.$ing->num_comprobante}}</td>
					<td>{{ $ing->impuesto}}</td>
					<td>{{ $ing->total}}</td>
					<td>{{ $ing->estado}}</td>
					<td>
						@if($rol == 'Administrador' || $rol == 'Gerente' || $rol == 'Operador')
							<a href="{{URL::action('IngresoController@show',$ing->idingreso)}}"><button class="btn btn-primary">Detalles</button></a>
						@endif
						@if($rol == 'Administrador' || $rol == 'Gerente' || $rol == 'Operador')
							<a target="_blank" href="{{URL::action('IngresoController@reportec',$ing->idingreso)}}"><button class="btn btn-info">Reporte</button></a>
						@endif
						@if($rol == 'Administrador')
							<a href="" data-target="#modal-delete-{{$ing->idingreso}}" data-toggle="modal"><button class="btn btn-danger">Anular</button></a>
						@endif
					</td>
				</tr>
				@include('compras.ingreso.modal')
				@endforeach
			</table>
		</div>
		{{$ingresos->render()}}
	</div>
</div>
@push ('scripts')
<script>
$('#liCompras').addClass("treeview active");
$('#liIngresos').addClass("active");
</script>
@endpush
@endsection