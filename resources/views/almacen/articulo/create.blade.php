@extends ('layouts.admin')
@section ('contenido')
<p type="hidden" {{$rol = Auth::user()->role }}></p>
@if($rol == 'Administrador'||$rol == 'Operador')
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<h3>Nuevo Articulo</h3>
		@if (count($errors)>0)
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
				<li>{{$error}}</li>
				@endforeach
			</ul>
		</div>
		@endif
	</div>
</div>
{!!Form::open(array('url'=>'almacen/articulo','method'=>'POST','autocomplete'=>'off','files'=>'true'))!!}
{{Form::token()}}
<div class="row">
	
	<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
		<div class="form-group">
			<label>Categoria</label>
			<select name="idcategoria" class="form-control selectpicker" data-live-search="true">
				@foreach ($categorias as $cat)
				<option value="{{$cat->idcategoria}}">{{$cat->nombre}}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
		<div class="form-group">
			<label>Proveedor</label>
			<select name="idproveedor" class="form-control selectpicker" data-live-search="true">
				@foreach ($persona as $per)
				<option value="{{$per->idpersona}}">{{$per->nombre}}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
		<div class="form-group">

			<label for="codigo">Código</label>
			<input type="number" name="codigo" id="codigobar" required value="{{old('codigo')}}" min="0" class="form-control" placeholder="Código del artículo...">
			<hr>
			<button class="btn btn-success" type="button" onclick="generarBarcode()"><i class="fa fa-barcode" aria-hidden="true"> Generar</i></button>
			<button class="btn btn-info" onclick="imprimir()" type="button"><i class="fa fa-print" aria-hidden="true"> Imprimir</i></button>

			<div id="print">
				<svg id="barcode"></svg>
			</div>
		</div>
	</div>

	<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
		<div class="form-group">
			<label for="nombre">Nombre</label>
			<input type="text" name="nombre" required value="{{old('nombre')}}" class="form-control" placeholder="Nombre...">
		</div>
	</div>

	<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
		<div class="form-group">
			<label for="stock">Stock</label>
			<input type="number" name="stock" required value="{{old('stock')}}" class="form-control" min="0" max="5000000" placeholder="Stock del articulo...">
		</div>
	</div>

	<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
		<div class="form-group">
			<label for="descripcion">Descripcion</label>
			<input type="text" name="descripcion" required value="{{old('descripcion')}}" class="form-control" placeholder="Descripcion del articulo...">
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
			<button class="btn btn-primary" type="submit"><i class="fa fa-floppy-o" aria-hidden="true"> Guardar</i></button>
			<button class="btn btn-danger" type="reset"><i class="fa fa-times" aria-hidden="true"> Cancelar</i></button>
		</div>
	</div>
</div>




{!!Form::close()!!}
@push ('scripts')
<script src="{{asset('js/JsBarcode.all.min.js')}}"></script>
<script src="{{asset('js/jquery.PrintArea.js')}}"></script>
<script>
	function generarBarcode() {
		codigo = $("#codigobar").val();
		JsBarcode("#barcode", codigo, {
			format: "CODE128",
			font: "OCRB",
			fontSize: 18,
			textMargin: 0
		});
	}
	$('#liAlmacen').addClass("treeview active");
	$('#liArticulos').addClass("active");


	//Código para imprimir el svg
	function imprimir() {
		$("#print").printArea();
	}
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