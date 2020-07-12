@extends ('layouts.admin')
@section ('contenido')
<p type="hidden" {{$rol = Auth::user()->role }}></p>
@if($rol == 'Administrador'||$rol == 'Operador'||$rol == 'Visita')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de Artículos
			@if($rol == 'Administrador' || $rol == 'Operador')
			<a href="articulo/create"><button class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"> Nuevo</i></button></a>
			@endif
			@if($rol == 'Administrador' || $rol == 'Gerente' || $rol == 'Operador')
			<a href="{{url('reportearticulos')}}" target="_blank"><button class="btn btn-info"><i class="fa fa-book" aria-hidden="true"> Reportes</i></button></a>
		</h3>
		@endif
		</h3>
		@include('almacen.articulo.search')

	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Id</th>
					<th>Proveedor</th>
					<th>Nombre</th>
					<th>Codigo</th>
					<th>Categoria</th>
					<th>Stock</th>
					<th>Imagen</th>
					<th>Estado</th>
					@if ($rol == 'Administrador'||$rol == 'Operador')
					<th>Opciones</th>
					@endif
				</thead>
				@foreach ($articulos as $art)
				<tr>
					<td>{{ $art->idarticulo}}</td>
					<td>{{ $art->proveedor}}</td>
					<td>{{ $art->nombre}}</td>
					<td>{{ $art->codigo}}</td>
					<td>{{ $art->categoria}}</td>
					<td>{{ $art->stock}}</td>
					<td>
						<img src="{{asset('/imagenes/articulos/'.$art->imagen)}}" alt="{{ $art->nombre}}" height="100px" width="100px" class="img-thumbnail">
					</td>
					@if ($art->estado == 'Activo')
					<td><small class="bg-green">{{ $art->estado}}</small></td>
					@else
					<td><small class="bg-red">{{ $art->estado}}</small></td>
					@endif
					<td>
						@if($rol == 'Administrador' || $rol == 'Operador')
						<a href="{{URL::action('ArticuloController@edit',$art->idarticulo)}}"><button class="btn btn-warning"><i class="fa fa-pencil-square-o" aria-hidden="true"> Editar</i></button></a>
						@endif
						@if($rol == 'Administrador')
						@if ($art->estado == 'Activo')
						<a href="" data-target="#modal-delete-{{$art->idarticulo}}" data-toggle="modal"><button class="btn btn-danger"><i class="fa fa-toggle-on" aria-hidden="true"> Desactivar</i></button></a>
						@else
						<a data-target="#modal-aceptar" data-toggle="modal"><button class="btn btn-success"><i class="fa fa-toggle-off" aria-hidden="true"> Activar </i></button></a>
						<div class="modal fade modal-slide-in-right" tabindex="-1" role="dialog" id="modal-aceptar">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title">Activar Articulo</h4>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<p>Confirme si desea Activar el Articulo</p>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
										<a href="{{URL::action('ArticuloController@activar',$art->idarticulo)}}"><button type="button" class="btn btn-primary">Confirmar</button></a>
									</div>
								</div>
							</div>
						</div>
						@endif
						@endif
					</td>
				</tr>
				@include('almacen.articulo.modal')
				@endforeach
			</table>
		</div>
		{{$articulos->render()}}
	</div>
</div>
@push ('scripts')
<script>
	$('#liAlmacen').addClass("treeview active");
	$('#liArticulos').addClass("active");
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