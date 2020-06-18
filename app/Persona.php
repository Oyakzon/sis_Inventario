<?php

namespace sis_Inventario;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table='persona';

    protected $primaryKey='idpersona';

    public $timestamps=false;


    protected $fillable =[
    	'tipo_persona',
    	'nombre',
        'tipo_documento',
        'direccion',
        'telefono',
        'email'
    ];

    protected $guarded =[

    ];
}
