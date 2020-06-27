<?php

namespace sis_Inventario\Http\Controllers;

use Illuminate\Http\Request;

use sis_Inventario\Http\Requests;
use sis_Inventario\Categoria;
use Illuminate\Support\Facades\Redirect;
use sis_Inventario\Http\Requests\CategoriaFormRequest;
use DB;

use Fpdf;


class CategoriaController extends Controller
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
            $categorias=DB::table('categoria')->where('nombre','LIKE','%'.$query.'%')
            ->where ('condicion','=','1')
            ->orderBy('idcategoria','desc')
            ->paginate(7);
            return view('almacen.categoria.index',["categorias"=>$categorias,"searchText"=>$query]);
        }
    }
    public function create()
    {
        return view("almacen.categoria.create");
    }
    public function store (CategoriaFormRequest $request)
    {
        $categoria=new Categoria;
        $categoria->nombre=$request->get('nombre');
        $categoria->descripcion=$request->get('descripcion');
        $categoria->condicion='1';
        $categoria->save();
        return Redirect::to('almacen/categoria');

    }
    public function show($id)
    {
        return view("almacen.categoria.show",["categoria"=>Categoria::findOrFail($id)]);
    }
    public function edit($id)
    {
        return view("almacen.categoria.edit",["categoria"=>Categoria::findOrFail($id)]);
    }
    public function update(CategoriaFormRequest $request,$id)
    {
        $categoria=Categoria::findOrFail($id);
        $categoria->nombre=$request->get('nombre');
        $categoria->descripcion=$request->get('descripcion');
        $categoria->update();
        return Redirect::to('almacen/categoria');
    }
    public function destroy($id)
    {
        $categoria=Categoria::findOrFail($id);
        $categoria->condicion='0';
        $categoria->update();
        return Redirect::to('almacen/categoria');
    }
    public function reporte(){
        //Obtenemos los registros
        $registros=DB::table('categoria')
           ->where ('condicion','=','1')
           ->orderBy('nombre','asc')
           ->get();

        $pdf = new Fpdf();
        $pdf::AddPage();
        $pdf::SetTextColor(35,56,113);
        $pdf::SetFont('Arial','B',11);
        $pdf::Cell(0,10,utf8_decode("Listado Categorías"),0,"","C");
        $pdf::Ln();
        $pdf::Ln();
        $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
        $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
        $pdf::SetFont('Arial','B',10); 
        //El ancho de las columnas debe de sumar promedio 190        
        $pdf::cell(50,8,utf8_decode("Nombre"),1,"","L",true);
        $pdf::cell(140,8,utf8_decode("Descripción"),1,"","L",true);
        
        $pdf::Ln();
        $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
        $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
        $pdf::SetFont("Arial","",9);
        
        foreach ($registros as $reg)
        {
           $pdf::cell(50,6,utf8_decode($reg->nombre),1,"","L",true);
           $pdf::cell(140,6,utf8_decode($reg->descripcion),1,"","L",true);
           $pdf::Ln(); 
        }

        $pdf::Output();
        exit;
   }





}
