<?php

namespace sis_Inventario;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table='articulo';

    protected $primaryKey='idarticulo';

    public $timestamps=false;


    protected $fillable =[
        'idcategoria',
        'codigo',
    	'nombre',
        'stock',
        'descripcion',
        'imagen',
        'estado',
    ];

    protected $guarded =[
        
    ];

}
