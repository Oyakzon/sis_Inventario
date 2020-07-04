<?php

namespace sis_Inventario;

use Illuminate\Database\Eloquent\Model;

class Perdida extends Model
{
    protected $table='perdida';

    protected $primaryKey='idperdida';

    public $timestamps=false;

    protected $fillable =[
        'idarticulo',
        'stock',
    	'descripcion',
        'imagen',
        'fecha_hora'
    ];

    protected $guarded =[

    ];
}
