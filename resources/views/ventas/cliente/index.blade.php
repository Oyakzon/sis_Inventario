@extends ('layouts.admin')
@section ('contenido')
<p type="hidden" {{$rol = Auth::user()->role }}></p>
@if($rol == 'Administrador'||$rol == 'Operador')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de Clientes
			@if($rol == 'Administrador' || $rol == 'Operador') 
				<a href="cliente/create"><button class="btn btn-success">Nuevo</button></a>
			@endif
			@if($rol == 'Administrador' || $rol == 'Gerente' || $rol == 'Operador')
				<a href="{{url('reporteclientes')}}" target="_blank"><button class="btn btn-info">Reporte</button></a>
			@endif
		</h3>
		@include('ventas.cliente.search')
	</div>
</div>


<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Id</th>
					<th>Nombre</th>
					<th>Tipo Doc.</th>
					<th>Número Doc.</th>
					<th>Teléfono</th>
					<th>Email</th>
					<th>Opciones</th>
				</thead>
               @foreach ($personas as $per)
				<tr>
					<td>{{ $per->idpersona}}</td>
					<td>{{ $per->nombre}}</td>
					<td>{{ $per->tipo_documento}}</td>
					<td>{{ $per->num_documento}}</td>
					<td>{{ $per->telefono}}</td>
					<td>{{ $per->email}}</td>
					<td>
						@if($rol == 'Administrador' || $rol == 'Operador')
							<a href="{{URL::action('ClienteController@edit',$per->idpersona)}}"><button class="btn btn-info">Editar</button></a>
						@endif
						@if($rol == 'Administrador')
							<a href="" data-target="#modal-delete-{{$per->idpersona}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
						@endif
					</td>
				</tr>
				@include('ventas.cliente.modal')
				@endforeach
			</table>
		</div>
		{{$personas->render()}}
	</div>
</div>
@push ('scripts')
<script>
$('#liVentas').addClass("treeview active");
$('#liClientes').addClass("active");
</script>
@endpush
@endif
@if($rol == 'Gerente')
<div class="alert alert-danger text-center" role="alert">
        <h3 class="alert-heading text-center">Acceso Denegado!</h3>
        <hr>
        <p class="text-center">No dispone de permisos para ingresar a esta ventana, para volver haga <a href="{{url('home')}}" class="alert-link text-center">Click Aqui</a>.</p>
    </div>
@endif
@endsection