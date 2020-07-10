<?php

namespace sis_Inventario\Http\Controllers;

use Illuminate\Http\Request;

use sis_Inventario\Http\Requests;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sis_Inventario\Http\Requests\PerdidaFormRequest;
use sis_Inventario\Perdida;
use DB;
use Carbon\Carbon;

use Fpdf;

class PerdidaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        if ($request)
        {   //recibe los parametros
            $query=trim($request->get('searchText'));
            $perdidas=DB::table('perdida as p')
            ->join('articulo as a','p.idarticulo','=','a.idarticulo')
            ->select('a.idarticulo','a.nombre as nombre','a.codigo','p.descripcion','a.imagen','a.estado','p.stock','p.fecha_hora','p.idperdida')
            ->where('p.descripcion','LIKE','%'.$query.'%')
            ->orwhere('a.codigo','LIKE','%'.$query.'%')
            ->orwhere('a.nombre','LIKE','%'.$query.'%')
            ->orwhere('p.fecha_hora','LIKE','%'.$query.'%')
            ->orderBy('p.fecha_hora','desc')
            ->paginate(7);
            return view('perdidas.perdida.index',["perdidas"=>$perdidas,"searchText"=>$query]);
        }
    }
    public function create()
    {
        $articulos=DB::table('articulo')->where('estado','=','Activo')->get();
        return view("perdidas.perdida.create",["articulos"=>$articulos]);
    }
    public function store (PerdidaFormRequest $request)
    {
        $perdida=new Perdida();
        $perdida->idarticulo=$request->get('idarticulo');
        $perdida->stock=$request->get('stock');
        $perdida->descripcion=$request->get('descripcion');
        $mytime = Carbon::now('America/Santiago');
        $perdida->fecha_hora=$mytime->toDateTimeString();
        $perdida->save();
        return Redirect::to('perdidas/perdida');

    }
    public function show($id)
    {
        $perdida=DB::table('perdida as p')
            ->join('articulo as a','a.idarticulo','=','p.idarticulo',)
            ->select('p.idperdida','p.fecha_hora','p.stock','p.descripcion','p.idarticulo','a.codigo','a.stock as real','a.nombre','a.idcategoria')
            ->where('p.idperdida','=',$id)
            ->first();       
        return view("perdidas.perdida.show",["perdida"=>$perdida]);
    }
    public function edit($id)
    {
        $perdida=Perdida::findOrFail($id);
        $articulos=DB::table('articulo')->where('estado','=','Activo')->get();
        return view("perdidas.perdida.edit",["perdida"=>$perdida,"articulos"=>$articulos]);
    }
    public function update(PerdidaFormRequest $request,$id)
    {
        $perdida=Perdida::findOrFail($id);
        $perdida->idarticulo=$request->get('idarticulo');
        $perdida->stock=$request->get('stock');
        $perdida->descripcion=$request->get('descripcion');
        $mytime = Carbon::now('America/Santiago');
        $perdida->fecha_hora=$mytime->toDateTimeString(); 
        $perdida->update();
        return Redirect::to('perdidas/perdida');
    }
    public function destroy($id)
    {
        $perdida = DB::table('perdida')->where('idperdida', '=', $id)->delete();
        return Redirect::to('perdidas/perdida');
    }
    public function reporte(){
        //Obtenemos los registros
        $perdidas=DB::table('perdida as p')
           ->join('articulo as a','p.idarticulo','=','a.idarticulo')
           ->select('p.idperdida','a.nombre','a.codigo','p.stock','p.fecha_hora','p.descripcion')
           ->orderBy('p.fecha_hora','desc')
           ->get();
        
        $pdf = new Fpdf();
        $pdf::AddPage();
        $pdf::SetTextColor(35,56,113);
        $pdf::SetFont('Arial','B',11);
        $pdf::Cell(0,10,utf8_decode("Listado de Perdidas"),0,"","C");
        $pdf::Ln();
        $pdf::Ln();
        $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
        $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
        $pdf::SetFont('Arial','B',10); 
        //El ancho de las columnas debe de sumar promedio 190        
        $pdf::cell(10,8,utf8_decode("ID"),1,"","L",true);
        $pdf::cell(25,8,utf8_decode("Codigo"),1,"","L",true);
        $pdf::cell(60,8,utf8_decode("Nombre"),1,"","L",true);
        $pdf::cell(23,8,utf8_decode("Descripcion"),1,"","L",true);
        $pdf::cell(40,8,utf8_decode("Fecha"),1,"","L",true);
        $pdf::cell(35,8,utf8_decode("Perdida Registrada"),1,"","L",true);
        
        $pdf::Ln();
        $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
        $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
        $pdf::SetFont("Arial","",9);
        
        foreach ($perdidas as $perd)
        {
           $pdf::cell(10,6,utf8_decode($perd->idperdida),1,"","L",true);
           $pdf::cell(25,6,utf8_decode($perd->codigo),1,"","L",true);
           $pdf::cell(60,6,utf8_decode($perd->nombre),1,"","L",true);
           $pdf::cell(23,6,utf8_decode($perd->descripcion),1,"","L",true);
           $pdf::cell(40,6,utf8_decode($perd->fecha_hora),1,"","L",true);
           $pdf::cell(35,6,utf8_decode($perd->stock),1,"","L",true);
           $pdf::Ln(); 
        }

        $pdf::Output();
        exit;
   }
}
