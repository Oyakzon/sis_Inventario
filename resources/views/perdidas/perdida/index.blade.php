@extends ('layouts.admin')
@section ('contenido')
<p type="hidden" {{$rol = Auth::user()->role }}></p>
@if($rol == 'Administrador'||$rol == 'Operador')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de perdidas
			@if($rol == 'Administrador'||$rol == 'Operador')
			<a href="perdida/create"><button class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"> Nuevo</i></button></a>
			<a href="{{url('reporteperdidas')}}" target="_blank"><button class="btn btn-info"><i class="fa fa-book" aria-hidden="true"> Reportes</i></button></a></h3>
		@endif
		</h3>
		@include('perdidas.perdida.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>id</th>
					<th>Fecha</th>
					<th>Nombre</th>
					<th>Codigo</th>
					<th>Perdida</th>
					<th>Descripcion</th>
					<th>Imagen</th>
					@if($rol == 'Administrador')
					<th>Opciones</th>
					@endif
				</thead>
				@foreach ($perdidas as $perd)
				<tr>
					<td>{{ $perd->idperdida}}</td>
					<td>{{ $perd->fecha_hora }}</td>
					<td>{{ $perd->nombre }}</td>
					<td>{{ $perd->codigo }}</td>
					<td>{{ $perd->stock }}</td>
					@if ($perd->descripcion == 'Extraviado')
					<td><small class="bg-red">{{ $perd->descripcion }}</small></td>
					@else
					<td><small class="bg-yellow">{{ $perd->descripcion }}</small></td>
					@endif
					<td>
						<img src="{{asset('imagenes/articulos/'.$perd->imagen)}}" alt="{{ $perd->nombre}}" height="100px" width="100px" class="img-thumbnail">
					</td>
					@if($rol == 'Administrador')
					<td>
						<a href="{{URL::action('PerdidaController@show',$perd->idperdida)}}"><button class="btn btn-primary"><i class="fa fa-info-circle" aria-hidden="true"> Detalles</i></button></a>
						<a href="" data-target="#modal-delete-{{$perd->idperdida}}" data-toggle="modal"><button class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"> Eliminar</i></button></a>
					</td>
					@endif
				</tr>
				@include('perdidas.perdida.modal')
				@endforeach
			</table>
		</div>
		{{$perdidas->render()}}
	</div>
</div>
@endif
@push ('scripts')
<script>
	$('#liAlmacen').addClass("treeview active");
	$('#liPerdidas').addClass("active");
</script>
@endpush
@if($rol == 'Gerente'||$rol == 'Visita')
<div class="alert alert-danger text-center" role="alert">
	<h3 class="alert-heading text-center">Acceso Denegado!</h3>
	<hr>
	<p class="text-center">No dispone de permisos para ingresar a esta ventana, para volver haga <a href="{{url('home')}}" class="alert-link text-center">Click Aqui</a>.</p>
</div>
@endif
@endsection