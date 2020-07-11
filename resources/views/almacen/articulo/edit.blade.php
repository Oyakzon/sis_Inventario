@extends ('layouts.admin')
@section ('contenido')
<p type="hidden" {{$rol = Auth::user()->role }}></p>
@if($rol == 'Administrador'||$rol == 'Operador')
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<h3>Editar Articulo: {{ $articulo->nombre}}</h3>
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

{!!Form::model($articulo,['method'=>'PATCH','route'=>['almacen.articulo.update',$articulo->idarticulo],'files'=>'true'])!!}
{{Form::token()}}
<div class="row">
	<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
		<div class="form-group">
			<label>Categoria</label>
			<select name="idcategoria" class="form-control selectpicker" data-live-search="true">
				@foreach ($categorias as $cat)
				@if ($cat->idcategoria==$articulo->idcategoria)
				<option value="{{$cat->idcategoria}}" selected>{{$cat->nombre}}</option>
				@else
				<option value="{{$cat->idcategoria}}">{{$cat->nombre}}</option>
				@endif
				@endforeach
			</select>
		</div>
	</div>

	<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
		<div class="form-group">
			<label>Proveedor</label>
			<select name="idproveedor" class="form-control selectpicker" data-live-search="true">
				@foreach ($persona as $per)
				@if ($per->idpersona==$articulo->idproveedor)
				<option value="{{$per->idpersona}}" selected>{{$per->nombre}}</option>
				@else
				<option value="{{$per->idpersona}}">{{$per->nombre}}</option>
				@endif
				@endforeach
			</select>
		</div>
	</div>

	<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
		<div class="form-group">
			<label for="codigo">Código</label>
			<input type="number" name="codigo" id="codigobar" min="0" value="{{$articulo->codigo}}" class="form-control">
			<hr>
			<button class="btn btn-success" type="button" onclick="generarBarcode()"><i class="fa fa-barcode" aria-hidden="true"> Generar</i></button>
			<button class="btn btn-info" onclick="imprimir()" type="button"><i class="fa fa-print" aria-hidden="true"> Imprimir</i></button>
			<hr>
			<div id="print">
				<svg id="barcode"></svg>
			</div>

		</div>
	</div>
	
	<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
		<div class="form-group">
			<label for="nombre">Nombre</label>
			<input type="text" name="nombre" required value="{{$articulo->nombre}}" class="form-control">
		</div>
	</div>

	<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
		<div class="form-group">
			<label for="stock">Stock</label>
			<input type="number" name="stock" min="0" value="{{$articulo->stock}}" class="form-control">
		</div>
	</div>

	<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
		<div class="form-group">
			<label for="descripcion">Descripcion</label>
			<input type="text" name="descripcion" required value="{{$articulo->descripcion}}" class="form-control" placeholder="Descripcion del articulo...">
		</div>
	</div>

	<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
		<div class="form-group">
			<label for="imagen">Imagen</label>
			<input type="file" name="imagen" class="form-control">
			@if (($articulo->imagen)!="")
			<img src="{{asset('imagenes/articulos/'.$articulo->imagen)}}" width="100px" height="100px">
			@endif
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
	generarBarcode();
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