@extends ('layouts.admin')
@section ('contenido')
<p type="hidden" {{$rol = Auth::user()->role }}></p>
@if($rol == 'Administrador')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de Proveedores 
			@if($rol == 'Administrador' || $rol == 'Operador')
				<a href="proveedor/create"><button class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"> Nuevo</i></button></a>
			@endif 
			@if($rol == 'Administrador' || $rol == 'Gerente' || $rol == 'Operador')
				<a href="{{url('reporteproveedores')}}" target="_blank"><button class="btn btn-info"><i class="fa fa-book" aria-hidden="true"> Reportes</i></button></a>
			@endif
		</h3>
		@include('compras.proveedor.search')
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
						@if($rol == 'Administrador')
							<a href="{{URL::action('ProveedorController@edit',$per->idpersona)}}"><button class="btn btn-warning"><i class="fa fa-pencil-square-o" aria-hidden="true"> Editar</i></button></a>
						@endif
						@if($rol == 'Administrador')
							<a href="" data-target="#modal-delete-{{$per->idpersona}}" data-toggle="modal"><button class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"> Eliminar</i></button></a>
						@endif
					</td>
				</tr>
				@include('compras.proveedor.modal')
				@endforeach
			</table>
		</div>
		{{$personas->render()}}
	</div>
</div>
@push ('scripts')
<script>
$('#liCompras').addClass("treeview active");
$('#liProveedores').addClass("active");
</script>
@endpush
@endif
@if($rol == 'Gerente'|| $rol == 'Operador' )
<div class="alert alert-danger text-center" role="alert">
        <h3 class="alert-heading text-center">Acceso Denegado!</h3>
        <hr>
        <p class="text-center">No dispone de permisos para ingresar a esta ventana, para volver haga <a href="{{url('home')}}" class="alert-link text-center">Click Aqui</a>.</p>
    </div>
@endif
@endsection