<?php

namespace sis_Inventario\Http\Controllers;

use Illuminate\Http\Request;

use sis_Inventario\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sis_Inventario\Http\Requests\VentaFormRequest;
use sis_Inventario\Venta;
use sis_Inventario\DetalleVenta;
use DB;
use Fpdf;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class VentaController extends Controller
{
     public function __construct(){
         $this->middleware('auth');

    }

    public function index(Request $request){
     if($request)
     {
        $query = trim($request->get('searchText'));

        $ventas = DB::table('venta as v')
        ->join('persona as p','v.idcliente','=','p.idpersona')
        ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
        ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado','v.total_venta')
        ->where('v.num_comprobante','LIKE','%'.$query.'%')
        ->orwhere('v.tipo_comprobante','LIKE','%'.$query.'%')
        ->orwhere('v.estado','LIKE','%'.$query.'%')
        ->orwhere('v.serie_comprobante','LIKE','%'.$query.'%')
        ->orderBy('v.fecha_hora','desc')
        ->groupBy('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado')
        ->paginate(5);
        return view("ventas.venta.index",["ventas" => $ventas,"searchText"=>$query]);
     }
     }

     public function create()
    {
        $personas=DB::table('persona')->where('tipo_persona','=','Cliente')->get();
        $articulos = DB::table('articulo as art')
            ->join('detalle_ingreso as di','art.idarticulo','=','di.idarticulo')
            ->select(DB::raw('CONCAT(art.codigo, " ",art.nombre) AS articulo'),'art.idarticulo','art.stock',DB::raw('avg(di.precio_venta) as precio_promedio'))
            ->where('art.estado','=','Activo')
            ->where('art.stock','>','0')
            ->groupBy('articulo','art.idarticulo','art.stock')
            ->get();
        return view("ventas.venta.create",["personas"=>$personas,"articulos"=>$articulos]);
    }


    public function store (VentaFormRequest $request)
    {
    	try{
        	DB::beginTransaction();
        	$venta=new Venta;
	        $venta->idcliente=$request->get('idcliente');
	        $venta->tipo_comprobante=$request->get('tipo_comprobante');
	        $venta->serie_comprobante=$request->get('serie_comprobante');
	        $venta->num_comprobante=$request->get('num_comprobante');
	        $venta->total_venta=$request->get('total_venta');
	        
	        $mytime = Carbon::now('America/Santiago');
	        $venta->fecha_hora=$mytime->toDateTimeString();
	        if ($request->get('impuesto')=='1')
            {
                $venta->impuesto='19';
            }
            else
            {
                $venta->impuesto='0';
            } 
	        $venta->estado='Aprobado';
	        $venta->save();

	        $idarticulo = $request->get('idarticulo');
	        $cantidad = $request->get('cantidad');
	        $descuento = $request->get('descuento');
	        $precio_venta = $request->get('precio_venta');

	        $cont = 0;

	        while($cont < count($idarticulo)){
	            $detalle = new DetalleVenta();
	            $detalle->idventa= $venta->idventa; 
	            $detalle->idarticulo= $idarticulo[$cont];
	            $detalle->cantidad= $cantidad[$cont];
	            $detalle->descuento= $descuento[$cont];
	            $detalle->precio_venta= $precio_venta[$cont];
	            $detalle->save();
	            $cont=$cont+1;            
	        }

        	DB::commit();

        }catch(\Exception $e)
        {
          	DB::rollback();
        }

        return Redirect::to('ventas/venta');
    }

    public function show($id)
    {
         $venta = DB::table('venta as v')
            ->join('persona as p', 'v.idcliente','=', 'p.idpersona')
            ->join('detalle_venta as dv', 'v.idventa', '=', 'dv.idventa')
            ->select('v.idventa', 'v.fecha_hora', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.impuesto', 'v.estado','v.total_venta')
            ->where('v.idventa','=', $id)
            ->first();
                      
                    
        $detalles=DB::table('detalle_venta as d')
            ->join('articulo as a','d.idarticulo','=','a.idarticulo')
            ->select('a.nombre as articulo','d.cantidad','d.descuento','d.precio_venta')
            ->where('d.idventa','=',$id)
            ->get();

        return view("ventas.venta.show", ["venta"=>$venta,"detalles"=>$detalles]);
    }

    public function destroy($id){
        $venta= Venta::findOrFail($id);
        $venta->Estado='Anulada';
        $venta->update();
        return Redirect::to('ventas/venta');
    }
    public function reportec($id){
        //Obtengo los datos
       
       $venta=DB::table('venta as v')
           ->join('persona as p','v.idcliente','=','p.idpersona')
           ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
           ->select('v.idventa','v.fecha_hora','p.nombre','p.direccion','p.num_documento','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado','v.total_venta')
           ->where('v.idventa','=',$id)
           ->first();

       $detalles=DB::table('detalle_venta as d')
            ->join('articulo as a','d.idarticulo','=','a.idarticulo')
            ->select('a.nombre as articulo','d.cantidad','d.descuento','d.precio_venta')
            ->where('d.idventa','=',$id)
            ->get();


       $pdf = new Fpdf();
       $pdf::AddPage();
       $pdf::SetFont('Arial','B',14);
       //Inicio con el reporte
       $pdf::SetXY(170,20);
       $pdf::Cell(0,0,utf8_decode($venta->tipo_comprobante));

       $pdf::SetFont('Arial','B',14);
       //Inicio con el reporte
       $pdf::SetXY(170,40);
       $pdf::Cell(0,0,utf8_decode($venta->serie_comprobante."-".$venta->num_comprobante));

       $pdf::SetFont('Arial','B',10);
       $pdf::SetXY(35,60);
       $pdf::Cell(0,0,utf8_decode($venta->nombre));
       $pdf::SetXY(35,69);
       $pdf::Cell(0,0,utf8_decode($venta->direccion));
       //***Parte de la derecha
       $pdf::SetXY(180,60);
       $pdf::Cell(0,0,utf8_decode($venta->num_documento));
       $pdf::SetXY(180,69);
       $pdf::Cell(0,0,substr($venta->fecha_hora,0,10));
       $total=0;

       //Mostramos los detalles
       $y=89;
       foreach($detalles as $det){
           $pdf::SetXY(20,$y);
           $pdf::MultiCell(10,0,$det->cantidad);

           $pdf::SetXY(32,$y);
           $pdf::MultiCell(120,0,utf8_decode($det->articulo));

           $pdf::SetXY(162,$y);
           $pdf::MultiCell(25,0,$det->precio_venta-$det->descuento);

           $pdf::SetXY(187,$y);
           $pdf::MultiCell(25,0,sprintf("%0.2F",(($det->precio_venta-$det->descuento)*$det->cantidad)));

           $total=$total+($det->precio_venta*$det->cantidad);
           $y=$y+7;
       }

       $pdf::SetXY(187,153);
       $pdf::MultiCell(20,0,"S/. ".sprintf("%0.2F", $venta->total_venta-($venta->total_venta*$venta->impuesto/($venta->impuesto+100))));
       $pdf::SetXY(187,160);
       $pdf::MultiCell(20,0,"S/. ".sprintf("%0.2F", ($venta->total_venta*$venta->impuesto/($venta->impuesto+100))));
       $pdf::SetXY(187,167);
       $pdf::MultiCell(20,0,"S/. ".sprintf("%0.2F", $venta->total_venta));

       $pdf::Output();
       exit;
   }
   public function reporte(){
        //Obtenemos los registros
        $registros=DB::table('venta as v')
           ->join('persona as p','v.idcliente','=','p.idpersona')
           ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
           ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado','v.total_venta')
           ->orderBy('v.idventa','desc')
           ->groupBy('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado')
           ->get();

        //Ponemos la hoja Horizontal (L)
        $pdf = new Fpdf('L','mm','A4');
        $pdf::AddPage();
        $pdf::SetTextColor(35,56,113);
        $pdf::SetFont('Arial','B',11);
        $pdf::Cell(0,10,utf8_decode("Listado de Ventas"),0,"","C");
        $pdf::Ln();
        $pdf::Ln();
        $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
        $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
        $pdf::SetFont('Arial','B',10); 
        //El ancho de las columnas debe de sumar promedio 190        
        $pdf::cell(35,8,utf8_decode("Fecha"),1,"","L",true);
        $pdf::cell(80,8,utf8_decode("Cliente"),1,"","L",true);
        $pdf::cell(45,8,utf8_decode("Comprobante"),1,"","L",true);
        $pdf::cell(10,8,utf8_decode("IVA"),1,"","C",true);
        $pdf::cell(25,8,utf8_decode("Total"),1,"","R",true);
        
        $pdf::Ln();
        $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
        $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
        $pdf::SetFont("Arial","",9);
        
        foreach ($registros as $reg)
        {
           $pdf::cell(35,8,utf8_decode($reg->fecha_hora),1,"","L",true);
           $pdf::cell(80,8,utf8_decode($reg->nombre),1,"","L",true);
           $pdf::cell(45,8,utf8_decode($reg->tipo_comprobante.': '.$reg->serie_comprobante.'-'.$reg->num_comprobante),1,"","L",true);
           $pdf::cell(10,8,utf8_decode($reg->impuesto),1,"","C",true);
           $pdf::cell(25,8,utf8_decode($reg->total_venta),1,"","R",true);
           $pdf::Ln(); 
        }

        $pdf::Output();
        exit;
   }
}