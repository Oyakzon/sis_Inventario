<?php

namespace sis_Inventario\Http\Controllers;

use Illuminate\Http\Request;

use sis_Inventario\Http\Requests;
use sis_Inventario\Persona;
use Illuminate\Support\Facades\Redirect;
use sis_Inventario\Http\Requests\PersonaFormRequest;
use DB;

use Fpdf;

class ProveedorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        if ($request)
        {
            $query=trim($request->get('searchText'));
            $personas=DB::table('persona')
            ->where('nombre','LIKE','%'.$query.'%')
            ->where ('tipo_persona','=','Proveedor')
            ->orwhere('nombre','LIKE','%'.$query.'%')
            ->where ('tipo_persona','=','Proveedor')
            ->orderBy('idpersona','desc')
            ->paginate(7);
            return view('compras.proveedor.index',["personas"=>$personas,"searchText"=>$query]);
        }
    }
    public function create()
    {
        return view("compras.proveedor.create");
    }
    public function store (PersonaFormRequest $request)
    {
        $persona=new Persona;
        $persona->tipo_persona=('Proveedor');
        $persona->nombre=$request->get('nombre');
        $persona->tipo_documento=$request->get('tipo_documento');
        $persona->num_documento=$request->get('num_documento');
        $persona->direccion=$request->get('direccion');
        $persona->telefono=$request->get('telefono');
        $persona->email=$request->get('email');
        $persona->save();
        return Redirect::to('compras/proveedor');

    }
    public function show($id)
    {
        return view("compras.proveedor.show",["persona"=>Persona::findOrFail($id)]);
    }
    public function edit($id)
    {
        return view("compras.proveedor.edit",["persona"=>Persona::findOrFail($id)]);
    }
    public function update(PersonaFormRequest $request,$id)
    {
        $persona=Persona::findOrFail($id);
        $persona->nombre=$request->get('nombre');
        $persona->tipo_documento=$request->get('tipo_documento');
        $persona->num_documento=$request->get('num_documento');
        $persona->direccion=$request->get('direccion');
        $persona->telefono=$request->get('telefono');
        $persona->email=$request->get('email');
        $persona->update();
        return Redirect::to('compras/proveedor');
    }
    public function destroy($id)
    {
        $persona=Persona::findOrFail($id);
        $persona->tipo_persona='Inactivo';
        $persona->update();
        return Redirect::to('compras/proveedor');
    }
    public function reporte(){
        //Obtenemos los registros
        $registros=DB::table('persona')
           ->where ('tipo_persona','=','Proveedor')
           ->orderBy('idpersona','desc')
           ->get();

        $pdf = new Fpdf();
        $pdf::AddPage();
        $pdf::SetTextColor(35,56,113);
        $pdf::SetFont('Arial','B',11);
        $pdf::Cell(0,10,utf8_decode("Listado de Proveedores"),0,"","C");
        $pdf::Ln();
        $pdf::Ln();
        $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
        $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
        $pdf::SetFont('Arial','B',10); 
        //El ancho de las columnas debe de sumar promedio 190        
        $pdf::cell(50,8,utf8_decode("Nombre"),1,"","L",true);
        $pdf::cell(30,8,utf8_decode("Tipo Documento"),1,"","L",true);
        $pdf::cell(35,8,utf8_decode("N° Documento"),1,"","L",true);
        $pdf::cell(50,8,utf8_decode("Email"),1,"","L",true);
        $pdf::cell(25,8,utf8_decode("Teléfono"),1,"","L",true);
        
        $pdf::Ln();
        $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
        $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
        $pdf::SetFont("Arial","",9);
        
        foreach ($registros as $reg)
        {
           $pdf::cell(50,6,utf8_decode($reg->nombre),1,"","L",true);
           $pdf::cell(30,6,utf8_decode($reg->tipo_documento),1,"","L",true);
           $pdf::cell(35,6,utf8_decode($reg->num_documento),1,"","L",true);
           $pdf::cell(50,6,utf8_decode($reg->email),1,"","L",true);
           $pdf::cell(25,6,utf8_decode($reg->telefono),1,"","L",true);
           $pdf::Ln(); 
        }

        $pdf::Output();
        exit;
   }
}
