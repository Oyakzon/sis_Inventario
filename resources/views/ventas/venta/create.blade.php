@extends('layouts.admin')
@section('contenido')
<p type="hidden" {{$rol = Auth::user()->role }}></p>
<p type="hidden" {{$id = Auth::user()->id }}></p>
@if($rol == 'Administrador'|| $rol == 'Operador')
<div class="row">
  <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <h3>Nueva Venta</h3>
    @if(count($errors)>0)
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
{!!Form::open(['url'=>'ventas/venta', 'method'=>'POST','autocomplete'=>'off']) !!}
{{Form::token()}}
<div class="row">

  <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <div class="form-group">
      <label for="idresponsable">Encargado</label>
      <input type="text" min="0" name="id" value="{{Auth::user()->name}}" class="form-control" readonly="readonly"">
      <input type="hidden" min="0" name="idresponsable" value="{{Auth::user()->id}}" class="form-control" readonly="readonly"">
    </div>
  </div>
  <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <div class="form-group">
      <label for="cliente">Cliente</label>
      <select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true">
        @foreach($personas as $persona)
        <option value="{{$persona->idpersona}} ">{{$persona->nombre}} </option>
        @endforeach
      </select>
    </div>
  </div>



  <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
    <div class="form-group">
      <label>Tipo Comprobante</label>
      <select name="tipo_comprobante" id="tipo_comprobante" class="form-control">
        <option value="Boleta">Boleta</option>
        <option value="Factura">Factura</option>
        <option value="Ticket">Ticket</option>
      </select>
    </div>
  </div>

  <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
    <div class="form-group">
      <label for="serie_comprobante">Serie de Comprobante</label>
      <input type="number" min="0" name="serie_comprobante" value="{{old('serie_comprobante')}}" class="form-control" placeholder="Serie de Comprobante....">
    </div>
  </div>

  <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
    <div class="form-group">
      <label for="num_comprobante">Numero de Comprobante</label>
      <input type="number" min="0" name="num_comprobante" required value="{{old('num_comprobante')}}" class="form-control" placeholder="Numero de Comprobante....">
    </div>
  </div>

  <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    <div class="form-group">
      <label for="impuesto">Iva</label>
      <input type="checkbox" value="1" name="impuesto" id="impuesto" class="checkbox">19% Iva
    </div>
  </div>
</div>

<div class="row">
  <div class="panel panel-primary">
    <div class="panel-body">
      <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
        <div class="form-group">
          <label>Artículo</label>
          <select name="pidarticulo" id="pidarticulo" class="form-control selectpicker" data-live-search="true">
            <option>--Selecionar--</option>
            @foreach($articulos as $articulo)
            <option value="{{$articulo->idarticulo}}_{{$articulo->stock}}_{{$articulo->precio_promedio}}">{{$articulo->articulo}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <div class="form-group">
          <label for="cantidad">Cantidad</label>
          <input type="number" min="0" name="pcantidad" id="pcantidad" class="form-control" placeholder="Cantidad">
        </div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <div class="form-group">
          <label for="stock">Stock</label>
          <input type="number" min="0" disabled name="pstock" id="pstock" class="form-control" placeholder="Stock">
        </div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <div class="form-group">
          <label for="precio_venta">Precio de Venta</label>
          <input type="number" min="0" disabled name="pprecio_venta" id="pprecio_venta" class="form-control" placeholder="Precio de venta">
        </div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <div class="form-group">
          <label for="descuento">Descuento</label>
          <input type="number" min="0" name="pdescuento" id="pdescuento" class="form-control" placeholder="Descuento">
        </div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <div class="form-group">
          <button type="button" id="bt_add" class="btn btn-primary"><i class="fa fa-cart-plus" aria-hidden="true"> Agregar</i></button>
        </div>
      </div>
      <div class="col-lg-12">
        <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
          <thead style="background-color:#A9D0F5">
            <th>Opciones</th>
            <th>Artículo</th>
            <th>Cantidad</th>
            <th>Precio Venta</th>
            <th>Descuento</th>
            <th>Sub Total</th>
          </thead>
          <tfoot>
            <tr>
              <th colspan="5">
                <p align="right">Total:</p>
              </th>
              <th>
                <p align="right"><span id="total">$/. 000</span> <input type="hidden" name="total_venta" id="total_venta"></p>
              </th>
            </tr>
            <tr>
              <th colspan="5">
                <p align="right">Total Iva (19%):</p>
              </th>
              <th>
                <p align="right"><span id="total_impuesto">$/. 000</span></p>
              </th>
            </tr>
            <tr>
              <th colspan="5">
                <p align="right">Total Pagar:</p>
              </th>
              <th>
                <p align="right"><span align="right" id="total_pagar">$/. 000</span></p>
              </th>
            </tr>
          </tfoot>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12" id="guardar">
    <div class="form-group">
      <input name="_token" value="{{ csrf_token() }}" type="hidden"></input>
      <button class="btn bg-primary" type="submit"><i class="fa fa-floppy-o" aria-hidden="true"> Guardar</i></button>
      <button class="btn btn-danger" type="reset"><i class="fa fa-times" aria-hidden="true"> Cancelar</i></button>
    </div>
  </div>
</div>

{!!Form::close()!!}

@push ('scripts')
<script>
  $(document).ready(function() {
    $('#bt_add').click(function() {
      agregar();
    });
  });

  var cont = 0;
  total = 0;
  subtotal = [];
  $("#guardar").hide();
  $("#pidarticulo").change(mostrarValores);
  $("#tipo_comprobante").change(marcarImpuesto);

  function mostrarValores() {
    datosArticulo = document.getElementById('pidarticulo').value.split('_');
    $("#pprecio_venta").val(datosArticulo[2]);
    $("#pstock").val(datosArticulo[1]);
  }

  function marcarImpuesto() {
    tipo_comprobante = $("#tipo_comprobante option:selected").text();
    if (tipo_comprobante == 'Factura') {
      $("#impuesto").prop("checked", true);
    } else {
      $("#impuesto").prop("checked", false);
    }
  }

  function agregar() {
    datosArticulo = document.getElementById('pidarticulo').value.split('_');

    idarticulo = datosArticulo[0];
    articulo = $("#pidarticulo option:selected").text();
    cantidad = $("#pcantidad").val();

    descuento = $("#pdescuento").val();
    precio_venta = $("#pprecio_venta").val();
    stock = $("#pstock").val();

    if (idarticulo != "" && cantidad != "" && cantidad > 0 && descuento != "" && precio_venta != "") {
      if (parseInt(stock) >= parseInt(cantidad)) {
        subtotal[cont] = (cantidad * precio_venta - descuento);
        total = total + subtotal[cont];

        var fila = '<tr class="selected" id="fila' + cont + '"><td><button type="button" class="btn btn-warning" onclick="eliminar(' + cont + ');">X</button></td><td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td><td><input type="number" name="cantidad[]" value="' + cantidad + '"></td><td><input type="number" name="precio_venta[]" value="' + parseFloat(precio_venta).toFixed(2) + '"></td><td><input type="number" name="descuento[]" value="' + parseFloat(descuento).toFixed(2) + '"></td><td align="right">S/. ' + parseFloat(subtotal[cont]).toFixed(2) + '</td></tr>';
        cont++;
        limpiar();
        totales();
        evaluar();
        $('#detalles').append(fila);
      } else {
        alert('La cantidad a vender supera el stock');
      }

    } else {
      alert("Error al ingresar el detalle de la venta, revise los datos del artículo");
    }
  }

  function limpiar() {
    $("#pcantidad").val("");
    $("#pdescuento").val("0");
    $("#pprecio_venta").val("");
  }

  function totales() {
    $("#total").html("$/. " + total.toFixed(2));
    $("#total_venta").val(total.toFixed(2));

    //Calcumos el impuesto
    if ($("#impuesto").is(":checked")) {
      por_impuesto = 19;
    } else {
      por_impuesto = 0;
    }
    total_impuesto = total * por_impuesto / 100;
    total_pagar = total + total_impuesto;
    $("#total_impuesto").html("$/. " + total_impuesto.toFixed(2));
    $("#total_pagar").html("$/. " + total_pagar.toFixed(2));

  }

  function evaluar() {
    if (total > 0) {
      $("#guardar").show();
    } else {
      $("#guardar").hide();
    }
  }

  function eliminar(index) {
    total = total - subtotal[index];
    totales();
    $("#fila" + index).remove();
    evaluar();

  }
  $('#liVentas').addClass("treeview active");
  $('#liVentass').addClass("active");
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