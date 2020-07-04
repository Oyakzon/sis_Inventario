<?php

namespace sis_Inventario\Http\Requests;

use sis_Inventario\Http\Requests\Request;

class PerdidaFormRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'idarticulo'=>'required',
            'stock'=>'required|numeric',
            'descripcion'=>'required|max:512',
            'imagen'=>'mimes:jpeg,bmp,png',
            'fecha_hora'=>'required|date_format:Y-m-d',
            
        ];
    }
}