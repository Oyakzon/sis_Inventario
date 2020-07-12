@extends ('layouts.admin')
@section ('contenido')
<p type="hidden" {{$rol = Auth::user()->role }}></p>
@if($rol == 'Administrador' || $rol == 'Operador'|| $rol == 'Gerente')
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="form-group">
					<label for="proveedor">Proveedor</label>
					<p>{{$ingreso->nombre}}</p>
				</div>
			</div>
			
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<label>Tipo Comprobante</label>
					<p>{{$ingreso->tipo_comprobante}}</p>
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<label for="serie_comprobante">Serie Comprobante</label>
					<p>{{$ingreso->serie_comprobante}}</p>
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<label for="num_comprobante">Numero Comprobante</label>
					<p>{{$ingreso->num_comprobante}}</p>
				</div>
			</div>
			<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
            	<div class="form-group">
                	<label for="impuesto">Impuesto</label>
                	<p>{{$ingreso->impuesto}} %</p>
            	</div>
        	</div>
			
		</div>

		<div class="row">
			<div class="panel panel-primary">
				<div class="panel-body">			
					<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
						<table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
							<thead style="background-color:#A9D0F5;">
								<th>Articulo</th>
								<th>Cantidad</th>
								<th>Precio Compra</th>
								<th>Precio Venta</th>
								<th>Subtotal</th>
							</thead>
							<tfoot>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th><h4 id="total">{{$ingreso->total}}</h4></th> 
							</tfoot>
							<tbody>								
								@foreach($detalles as $det)
								<tr>
									<td>{{$det->articulo}}</td>
									<td>{{$det->cantidad}}</td>
									<td>{{$det->precio_compra}}</td>
									<td>{{$det->precio_venta}}</td>
									<td>{{$det->cantidad*$det->precio_compra}}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>

				</div>
			</div>
@push ('scripts')
<script>
$('#liCompras').addClass("treeview active");
$('#liIngresos').addClass("active");
</script>
@endpush
@endif
@if($rol == 'Visita')
<div class="alert alert-danger text-center" role="alert">
        <h3 class="alert-heading text-center">Acceso Denegado!</h3>
        <hr>
        <p class="text-center">No dispone de permisos para ingresar a esta ventana, para volver haga <a href="{{url('home')}}" class="alert-link text-center">Click Aqui</a>.</p>
    </div>
@endif					
@endsection