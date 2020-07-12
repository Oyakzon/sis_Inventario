@extends ('layouts.admin')
@section ('contenido')
<p type="hidden" {{$rol = Auth::user()->role }}></p>
@if($rol == 'Administrador'||$rol == 'Operador')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 ">
		<h3>Nueva perdida</h3>
		@if (count($errors)>0)
		<div class="alert alert-danger">
			<ul>
				@foreach ($error->all() as $error)
				<li>{{$error}}</li>
				@endforeach
			</ul>
		</div>
		@endif
		<hr>
		{!!Form::open(array('url'=>'perdidas/perdida','method'=>'POST','autocomplete'=>'off','files'=>'true'))!!}
		{{Form::token()}}
		<div class="row">
			<div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
				<div class="form-group">
					<label for="descripcion">Descripcion </label>
					<select class="selectpicker" id="descripcion" name="descripcion" data-max-options="2">
						<option value="Extraviado">Extraviado</option>
						<option value="Dañado">Dañado</option>
					</select>
				</div>
			</div>
			<div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
				<div class="form-group">
					<label for="idarticulo">Articulo </label>
					<select class="selectpicker" name="idarticulo" id="idarticulo" data-live-search="true">
						@foreach($articulos as $art)
						<option value="{{$art->idarticulo}}">{{$art->nombre}}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
				<div class="form-group">
					<label for="stock">Perdida</label>
					<input type="number" id="stock" name="stock" required value="{{old('stock')}}" class="form-control" placeholder="Cant" min="0" max="1000" maxlength="4">
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12" id="guardar">
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