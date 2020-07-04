@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de perdidas
				<a href="perdida/create"><button class="btn btn-success"><i class="fa fa-plus" aria-hidden="true">  Nuevo</i></button></a> 
				<a href="{{url('reporteperdidas')}}" target="_blank"><button class="btn btn-info"><i class="fa fa-book" aria-hidden="true"> Reportes</i></button></a></h3>	
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
					<th>Opciones</th>				
				</thead>
				@foreach ($perdidas as $perd)
				<tr>
					<td>{{ $perd->idperdida}}</td>
					<td>{{ $perd->fecha_hora }}</td>
					<td>{{ $perd->nombre }}</td>
					<td>{{ $perd->codigo }}</td>
					<td>{{ $perd->stock }}</td>
					<td>{{ $perd->descripcion }}</td>
					<td>
						<img src="{{asset('imagenes/articulos/'.$perd->imagen)}}" alt="{{ $perd->nombre}}" height="100px" width="100px" class="img-thumbnail">
					</td>
					<td>
						
						<a href="{{URL::action('PerdidaController@edit',$perd->idperdida)}}"><button class="btn btn-warning"><i class="fa fa-pencil-square-o" aria-hidden="true"> Editar</i></button></a>
						
						<a href="" data-target="#modal-delete-{{$perd->idperdida}}" data-toggle="modal"><button class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"> Eliminar</i></button></a>
						
					</td>
				</tr>
				@include('perdidas.perdida.modal')
				@endforeach
			</table>
		</div>
		{{$perdidas->render()}}
	</div>
	
</div>

@endsection