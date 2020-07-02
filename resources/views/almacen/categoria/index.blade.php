@extends ('layouts.admin')
@section ('contenido')

<p type="hidden" {{$rol = Auth::user()->role }}></p>
@if($rol == 'Administrador' || $rol == 'Operador')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de Categorías 
			@if($rol == 'Administrador' || $rol == 'Operador')
				<a href="categoria/create"><button class="btn btn-success">Nuevo</button></a> 
			@endif
			@if($rol == 'Administrador' || $rol == 'Gerente' || $rol == 'Operador')
				<a href="{{url('reportecategorias')}}" target="_blank"><button class="btn btn-info">Reporte</button></a>
			@endif
		</h3>
		@include('almacen.categoria.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Id</th>
					<th>Nombre</th>
					<th>Descripción</th>
					<th>Opciones</th>
				</thead>
               @foreach ($categorias as $cat)
				<tr>
					<td>{{ $cat->idcategoria}}</td>
					<td>{{ $cat->nombre}}</td>
					<td>{{ $cat->descripcion}}</td>
					<td>
						@if($rol == 'Administrador' || $rol == 'Operador')
							<a href="{{URL::action('CategoriaController@edit',$cat->idcategoria)}}"><button class="btn btn-info">Editar</button></a>
						@endif
						@if($rol == 'Administrador')
							<a href="" data-target="#modal-delete-{{$cat->idcategoria}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
						@endif
					</td>
				</tr>
				@include('almacen.categoria.modal')
				@endforeach
			</table>
		</div>
		{{$categorias->render()}}
	</div>
</div>
@push ('scripts')
<script>
$('#liAlmacen').addClass("treeview active");
$('#liCategorias').addClass("active");
</script>
@endpush
@endif
@if($rol == 'Gerente' )
<div class="alert alert-danger" role="alert">
  <h4 class="alert-heading">Permisos Insuficientes!</h4>
  No dispone de permisos, para volver haga <a href="{{url('home')}}" class="alert-link">Click Aqui</a>.
</div>

@endif
@endsection