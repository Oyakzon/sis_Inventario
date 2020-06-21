<?php

namespace sis_Inventario;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table='detalle_venta';

    protected $primaryKey='iddetalle_ingreso';

    public $timestamps=false;


    protected $fillable =[
    	'idventa',
    	'idarticulo',
        'cantidad',
        'precio_venta',
        'descuento'
    ];
}
