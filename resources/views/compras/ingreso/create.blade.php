@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Nuevo Ingreso</h3>
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
		{!!Form::open(array('url'=>'compras/ingreso','method'=>'POST','autocomplete'=>'off'))!!}
		{{Form::token()}}
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="form-group">
					<label for="proveedor">Proveedor</label>
					<select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-live-search="true">
						@foreach($personas as $persona)
						<option value="{{$persona->idpersona}}">{{$persona->nombre}}</option>
						@endforeach
					</select>
				</div>
			</div>
			
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<label>Tipo Comprobante</label>
					<select name="tipo_comprobante" class="form-control">
						<option value="Boleta">Boleta</option>
						<option value="Factura">Factura</option>
						<option value="Ticket">Ticket</option>
					</select>
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<label for="serie_comprobante">Serie Comprobante</label>
					<input type="text" name="serie_comprobante" value="{{old('serie_comprobante')}}" class="form-control" placeholder="Serie de Comprobante...">
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<label for="num_comprobante">Numero Comprobante</label>
					<input type="text" name="num_comprobante" required value="{{old('num_comprobante')}}" class="form-control" placeholder="Numero del Comprobante...">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="panel panel-primary">
				<div class="panel-body">
					<div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
						<div class="form-group">
							<label>Articulo</label>
							<select name="pidarticulo" class="form-control selectpicker" id="pidarticulo" data-live-search="true">
								@foreach($articulos as $articulo)
								<option value="{{$articulo->idarticulo}}">{{$articulo->articulo}}</option>
								@endforeach
							</select>
						</div>

					</div>
					<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
						<div class="form-group">
							<label for="cantidad">Cantidad</label>
							<input type="number" name="pcantidad" id="pcantidad" class="form-control" placeholder="Cantidad">
						</div>
					</div>

					<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
						<div class="form-group">
							<label for="precio_compra">Precio Compra</label>
							<input type="number" name="pprecio_compra" id="pprecio_compra" class="form-control" placeholder="P. Compra">
						</div>
					</div>

					<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
						<div class="form-group">
						<label for="precio_venta">Precio Venta</label>
							<input type="number" name="pprecio_venta" id="pprecio_venta" class="form-control" placeholder="P. Venta">
						</div>
					</div>

					<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
						<div class="form-group">
							<button type="button" id="bt_add" class="btn btn-primary">Agregar</button>
						</div>
					</div>
					
					<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
						<table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
							<thead style="background-color:#A9D0F5;">
								<th>Opciones</th>
								<th>Articulo</th>
								<th>Cantidad</th>
								<th>Precio Compra</th>
								<th>Precio Venta</th>
								<th>Subtotal</th>
							</thead>
							<tfoot>
								<th>Total</th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th><h4 id="total">$/.0.00</h4></th>
							</tfoot>
							<tbody>

							</tbody>
						</table>
					</div>

				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" id="guardar">
				<div class="form-group">
					<input name="_token" value="{{ csrf_token() }}" type="hidden"></input>
					<button class="btn btn-primary" type="submit">Guardar</button>
					<button class="btn btn-danger" type="reset">Cancelar</button>
				</div>
			</div>
					
		</div>
			{!!Form::close()!!}	
@push ('scripts')
<script>
		$(document).ready(function(){
			$('#bt_add').click(function(){
				agregar();
			});
		});
		var cont=0;
		total=0;
		subtotal=[];
		$("#guardar").hide();
		$("#tipo_comprobante").change(marcarImpuesto);

		function marcarImpuesto()
  {
			tipo_comprobante=$("#tipo_comprobante option:selected").text();
			if (tipo_comprobante=='Factura')
			{
				$("#impuesto").prop("checked", true); 
			}
			else
			{
				$("#impuesto").prop("checked", false);
			}
  }

		function agregar()
		{
			idarticulo=$("#pidarticulo").val();
			articulo=$("#pidarticulo option:selected").text();
			cantidad=$("#pcantidad").val();
			precio_compra=$("#pprecio_compra").val();
			precio_venta=$("#pprecio_venta").val();

			if (idarticulo!="" && cantidad!="" && cantidad>0 && precio_compra!="" && precio_venta!="")
			{
				subtotal[cont]=(cantidad*precio_compra);
				total=total+subtotal[cont];
				//ELIMINAR ARTICULO AGREGADO
				var fila='<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+');">X</button></td><td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td><td><input type="number" name="cantidad[]" value="'+cantidad+'"></td><td><input type="number" name="precio_compra[]" value="'+precio_compra+'"></td><td><input type="number" name="precio_venta[]" value="'+precio_venta+'"></td><td>  '+subtotal[cont]+'</td></tr>';
				cont++;
				limpiar();
				$("#total").html("$/. "+ total);
				evaluar();
				$('#detalles').append(fila);
			}
			else 
			{
				alert("Error al ingresar el detalle del ingreso, verifique los campos ingresados del articulo");	
			}
		}
		//LIMPIAR CASILLAS TEXT NOMBRADAS
		function limpiar(){
			$("#pcantidad").val("");
			$("#pprecio_compra").val("");
			$("#pprecio_venta").val("");
		}
		//EVALUAR SI SE INGRESARON DETALLES SCRIPT
		function evaluar(){
			if (total>0)
			{
				$("#guardar").show();
			}
			else
			{
				$("#guardar").hide();
			}
		}

		function eliminar(index)
		{
			total=total-subtotal[index];
			$("#total").html("$/. " + total);
			$("#fila" + index).remove();
			evaluar();
		}
		$('#liCompras').addClass("treeview active");
$('#liIngresos').addClass("active");
</script>
@endpush
@endsection