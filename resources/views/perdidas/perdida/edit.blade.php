@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
		<h3>Editar Perdida: {{ $perdida->idarticulo}} </h3>
		@if (count($errors)>0)
		<div class="alert alert-danger">
			<ul>
				@foreach($error->all() as $error)
				<li>{{$error}}</li>
				@endforeach
			</ul>
		</div>
		@endif
		<hr>
		{!!Form::model($perdida,['method'=>'PATCH','route'=>['perdidas.perdida.update',$perdida->idperdida],'files'=>'true'])!!}
		{{Form::token()}}
		<div class="row">

			<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
				<div class="form-group">
					<label for="descripcion">Descripcion: </label>
					<select class="selectpicker" id="descripcion" name="descripcion" data-max-options="3">
						@if ($perdida->descripcion == 'Extraviado')
						<option value="" selected>{{$perdida->descripcion}}</option>
						<option value="Dañado">Dañado</option>
						@else
						<option value="" selected>{{$perdida->descripcion}}</option>
						<option value="Extraviado">Extraviado</option>
						@endif
					</select>
				</div>
			</div>
			<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
				<div class="form-group">
					<label for="idarticulo">Articulo: </label>
					<select class="selectpicker" name="idarticulo" id="idarticulo" data-live-search="true">
						@foreach($articulos as $art)
						@if ($art->idarticulo==$perdida->idarticulo)
						<option value="{{$art->idarticulo}}" selected>{{$art->nombre}}</option>
						@else
						<option value="{{$art->idarticulo}}">{{$art->nombre}}</option>
						@endif
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
				<div class="form-group">
					<label for="imagen">Imagen</label>
					<input type="file" name="imagen" class="form-control">
				</div>
			</div>

			<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
				<div class="form-group">
					<label for="stock">Perdida</label>
					<input type="number" id="stock" name="stock" required value="{{$perdida->stock}}" class="form-control" placeholder="Perdida" min="1" max="1000">
				</div>
			</div>
			<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
				<div class="form-group">
					<label for="">Fecha</label>
					<input type="date" name="fecha_hora" required value="{{$perdida->fecha_hora}}" id="fecha_hora" class="form-control" required="true">
					
				</div>					
			</div>

		</div>
		<br>
		<div class="row">
			<div class="col-lg-4 col-sm-4 col-md-4 col-xs-12" id="guardar">
				<div class="form-group">
					<input name"_token" value="{{ csrf_token() }}" type="hidden"></input>
					<button class="btn btn-primary" type="submit"><i class="fa fa-floppy-o" aria-hidden="true"> Guardar</i></button>
					<button class="btn btn-danger" type="reset"><i class="fa fa-times" aria-hidden="true"> Cancelar</i></button>
				</div>
			</div>
		</div>

		{!!Form::close()!!}
	</div>
</div>
@endsection