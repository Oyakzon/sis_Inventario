@extends ('layouts.admin')
@section ('contenido')
<p type="hidden" {{$rol = Auth::user()->role }}></p>
@if($rol == 'Administrador'||$rol == 'Operador')
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="form-group">
			<label for="proveedor">ID Perdida</label>
			<p>{{$perdida->idperdida}}</p>
		</div>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="form-group">
			<label>ID Articulo</label>
			<p>{{$perdida->idarticulo}}</p>
		</div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="form-group">
			<label for="serie_comprobante">Fecha Creacion</label>
			<p>{{$perdida->fecha_hora}}</p>
		</div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="form-group">
			<label for="num_comprobante">Stock Articulo</label>
			<p>{{$perdida->real}}</p>
		</div>
	</div>
</div>

<div class="row">
	<div class="panel panel-primary">
		<div class="panel-body">
			<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
				<table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
					<thead style="background-color:#A9D0F5;">
						<th>ID</th>
						<th>Nombre</th>
						<th>Codigo</th>
						<th>Perdida</th>
						<th>Descripcion</th>

					</thead>
					<tbody>
						<tr>
							<td>{{$perdida->idperdida}}</td>
							<td>{{$perdida->nombre}}</td>
							<td>{{$perdida->codigo}}</td>
							<td>{{$perdida->stock}}</td>
							<td>{{$perdida->descripcion}}</td>
					</tbody>
				</table>
			</div>

		</div>
	</div>
</div>
@push ('scripts')
<script>
$('#liAlmacen').addClass("treeview active");
$('#liPerdidas').addClass("active");
</script>
@endpush
@endif
@if($rol == 'Gerente'||$rol == 'Visita')
<div class="alert alert-danger text-center" role="alert">
	<h3 class="alert-heading text-center">Acceso Denegado!</h3>
	<hr>
	<p class="text-center">No dispone de permisos para ingresar a esta ventana, para volver haga <a href="{{url('home')}}" class="alert-link text-center">Click Aqui</a>.</p>
</div>
@endif
@endsection