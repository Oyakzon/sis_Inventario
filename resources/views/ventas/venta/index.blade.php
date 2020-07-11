@extends ('layouts.admin')
@section ('contenido')
<p type="hidden" {{$rol = Auth::user()->role }}></p>
@if($rol == 'Administrador'|| $rol == 'Operador'|| $rol == 'Gerente')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de Ventas 
			@if($rol == 'Administrador' || $rol == 'Operador')
				<a href="venta/create"><button class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"> Nuevo</i></button></a> 
			@endif
			@if($rol == 'Administrador' || $rol == 'Gerente' || $rol == 'Operador')
				<a href="{{url('reporteventas')}}" target="_blank"><button class="btn btn-info"><i class="fa fa-book" aria-hidden="true"> Reportes</i></button></a>
			@endif
		</h3>
		@include('ventas.venta.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Fecha</th>
					<th>Responsable</th>
					<th>Cliente</th>
					<th>Tipo Comprobante-S-N</th>
					<th>Impuesto</th>
					<th>Total</th>
					<th>Estado</th>
					<th>Opciones</th>
				</thead>
               @foreach ($ventas as $ven)
				<tr>
					<td>{{ $ven->fecha_hora}}</td>
					<td>{{ $ven->usuario}}</td>
					<td>{{ $ven->nombre}}</td>
					<td>{{ $ven->tipo_comprobante.': '.$ven->serie_comprobante.'-'.$ven->num_comprobante}}</td>
					<td>{{ $ven->impuesto}}</td>
					<td>${{ number_format($ven->total_venta) }}</td> 
					@if ($ven->estado == 'Aprobado')
					<td><small class="bg-green">{{ $ven->estado}}</small></td>
					@else
					<td><small class="bg-red">{{ $ven->estado}}</small></td>
					@endif
					<td>
						@if($rol == 'Administrador' || $rol == 'Gerente' || $rol == 'Operador')
							<a href="{{URL::action('VentaController@show',$ven->idventa)}}"><button class="btn btn-primary"><i class="fa fa-info-circle" aria-hidden="true"> Detalles</i></button></a>
						@endif
						@if($rol == 'Administrador' || $rol == 'Gerente' || $rol == 'Operador')
							<a target="_blank" href="{{URL::action('VentaController@reportec',$ven->idventa)}}"><button class="btn btn-info"><i class="fa fa-file-text" aria-hidden="true"> Reporte</i></button></a>
						@endif
						@if($rol == 'Administrador')
							<a href="" data-target="#modal-delete-{{$ven->idventa}}" data-toggle="modal"><button class="btn btn-danger"><i class="fa fa-ban" aria-hidden="true"> Anular</i></button></a>
						@endif
					</td>
				</tr>
				@include('ventas.venta.modal')
				@endforeach
			</table>
		</div>
		{{$ventas->render()}}
	</div>
</div>
@push ('scripts')
<script>
$('#liVentas').addClass("treeview active");
$('#liVentass').addClass("active");
</script>
@endpush
@endif
@if($rol == 'Visita')
<div class="alert alert-danger text-center" role="alert">
	<h3 class="alert-heading text-center">Acceso Denegado!</h3>
	<hr>
	<p class="text-center">No dispone de permisos para ingresar a esta ventana, para volver haga <a href="{{url('home')}}" class="alert-link text-center">Click Aqui</a>.</p>
</div>
@endif
@endsection