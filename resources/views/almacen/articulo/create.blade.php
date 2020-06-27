@extends ('layouts.admin')
@section ('contenido')
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
            	<label for="nombre">Nombre</label>
            	<input type="text" name="nombre" required value="{{old('nombre')}}" class="form-control" placeholder="Nombre...">
            </div>
		</div>

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
            	<label for="codigo">Código</label>
            	<input type="text" name="codigo" id="codigobar" required value="{{old('codigo')}}" class="form-control" placeholder="Código del artículo...">
                <br>
               <button class="btn btn-success" type="button" onclick="generarBarcode()">Generar</button>
                <button class="btn btn-info" onclick="imprimir()"type="button">imprimir</button>
                <div id="print">
                    <svg id="barcode"></svg>
                </div>
            </div>
    	</div>

		<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
			<div class="form-group">
            	<label for="stock">Stock</label>
            	<input type="text" name="stock" required value="{{old('stock')}}" class="form-control" placeholder="Stock del articulo...">
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
            	<input type="file" name="imagen"  class="form-control">
            </div>
		</div>
		<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
		<div class="form-group">
            	<button class="btn btn-primary" type="submit">Guardar</button>
            	<button class="btn btn-danger" type="reset">Cancelar</button>
            </div>
		</div>
	</div>
	
            
            

			{!!Form::close()!!}	
@push ('scripts')
<script src="{{asset('js/JsBarcode.all.min.js')}}"></script>
<script src="{{asset('js/jquery.PrintArea.js')}}"></script>
<script>
function generarBarcode()
{   
    codigo=$("#codigobar").val();
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
function imprimir()
{
    $("#print").printArea();
}
</script>
@endpush 

@endsection