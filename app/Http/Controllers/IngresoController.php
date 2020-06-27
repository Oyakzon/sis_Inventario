<?php

namespace sis_Inventario\Http\Controllers;

use Illuminate\Http\Request;

use sis_Inventario\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sis_Inventario\Http\Requests\IngresoFormRequest;
use sis_Inventario\Ingreso;
use sis_Inventario\DetalleIngreso;
use DB;

use Fpdf;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class IngresoController extends Controller
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
            $ingresos=DB::table('ingreso as i')
             ->join('persona as p','i.idproveedor','=','p.idpersona')
             ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
             ->select('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra) as total'))
             ->where('i.num_comprobante','LIKE','%'.$query.'%')
             ->orderBy('i.idingreso','desc')
             ->groupBy('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
             ->paginate(7);
             return view('compras.ingreso.index',["ingresos"=>$ingresos,"searchText"=>$query]);

        }
    }
    //FUNCION CREAR
    public function create()
    {
        
        $personas=DB::table('persona')->where('tipo_persona','=','Proveedor')->get();
        $articulos = DB::table('articulo as art')
            ->select(DB::raw('CONCAT(art.codigo, " ",art.nombre) AS articulo'),'art.idarticulo')
            ->where('art.estado','=','Activo')
            ->get();
        return view("compras.ingreso.create",["personas"=>$personas,"articulos"=>$articulos]);
        
    }
    //FUNCION ALMACENAR DETALLES Y INGRESOS
    public function store (IngresoFormRequest $request)
    {
        try {
             DB::beginTransaction();
             $ingreso=new Ingreso;
             $ingreso->idproveedor=$request->get('idproveedor');
             $ingreso->tipo_comprobante=$request->get('tipo_comprobante');
             $ingreso->serie_comprobante=$request->get('serie_comprobante');
             $ingreso->num_comprobante=$request->get('num_comprobante');

             $mytime = Carbon::now('America/Santiago');
             $ingreso->fecha_hora=$mytime->toDateTimeString();
             
             
             if ($request->get('impuesto')=='1')
            {
                $ingreso->impuesto='19';
            }
            else
            {
                $ingreso->impuesto='0';
            }
             $ingreso->estado='Aprobado';
             $ingreso->save();

             $idarticulo = $request->get('idarticulo');
             $cantidad = $request->get('cantidad');
             $precio_compra = $request->get('precio_compra');
             $precio_venta = $request->get('precio_venta');
            // PARA RECORRER EL ARRAY DE PRECIO VENTA | PRECIO COMPRAS | ARTICULOS | DETALLES DESDE LA POSICION 0

             $cont = 0;
            //DATOS ENVIADOS DESDE REGISTRO DE INGRESOS
            //MIENTRAS SEA MENOS QUE
             while($cont < count($idarticulo))
             {  $detalle = new DetalleIngreso();
                $detalle->idingreso= $ingreso->idingreso;
                $detalle->idarticulo= $idarticulo[$cont];
                $detalle->cantidad= $cantidad[$cont];
                $detalle->precio_compra= $precio_compra[$cont];
                $detalle->precio_venta= $precio_venta[$cont];
                $detalle ->save();
                $cont=$cont+1;     
             }

             DB::commit();
             
        } catch (\Exception $e) 
        {   
            DB::rollback();
        }

        return Redirect::to('compras/ingreso');
    }
    //MOSTRAR INGRESOS Y DETALLES EN UNA VISTA
    public function show($id)
    {
        //MOSTRAR DATOS
        $ingreso=DB::table('ingreso as i')
            ->join('persona as p','i.idproveedor','=','p.idpersona')
            ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
            ->select('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra) as total'))
            ->where('i.idingreso','=',$id)
            ->first();

        $detalles=DB::table('detalle_ingreso as d')
            ->join('articulo as a','d.idarticulo','=','a.idarticulo')
            ->select('a.nombre as articulo','d.cantidad','d.precio_compra','d.precio_venta')
            ->where('d.idingreso','=',$id)
            ->get();
            
        return view("compras.ingreso.show",["ingreso"=>$ingreso,"detalles"=>$detalles]);
    }
//CAMBIAR ESTADO DEL OBJETO
    public function destroy($id)
    {
        $ingreso=Ingreso::findOrFail($id);
        $ingreso->Estado='Anulado';
        $ingreso->update();
        return Redirect::to('compras/ingreso');
    }
    public function reportec($id){
        //Obtengo los datos
       
   $ingreso=DB::table('ingreso as i')
           ->join('persona as p','i.idproveedor','=','p.idpersona')
           ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
           ->select('i.idingreso','i.fecha_hora','p.nombre','p.direccion','p.num_documento','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra) as total'))
           ->where('i.idingreso','=',$id)
           ->first();

       $detalles=DB::table('detalle_ingreso as d')
            ->join('articulo as a','d.idarticulo','=','a.idarticulo')
            ->select('a.nombre as articulo','d.cantidad','d.precio_compra','d.precio_venta')
            ->where('d.idingreso','=',$id)
            ->get();


       $pdf = new Fpdf();
       $pdf::AddPage();
       $pdf::SetFont('Arial','B',14);
       //Inicio con el reporte
       $pdf::SetXY(170,20);
       $pdf::Cell(0,0,utf8_decode($ingreso->tipo_comprobante));

       $pdf::SetFont('Arial','B',14);
       //Inicio con el reporte
       $pdf::SetXY(170,40);
       $pdf::Cell(0,0,utf8_decode($ingreso->serie_comprobante."-".$ingreso->num_comprobante));

       $pdf::SetFont('Arial','B',10);
       $pdf::SetXY(35,60);
       $pdf::Cell(0,0,utf8_decode($ingreso->nombre));
       $pdf::SetXY(35,69);
       $pdf::Cell(0,0,utf8_decode($ingreso->direccion));
       //***Parte de la derecha
       $pdf::SetXY(180,60);
       $pdf::Cell(0,0,utf8_decode($ingreso->num_documento));
       $pdf::SetXY(180,69);
       $pdf::Cell(0,0,substr($ingreso->fecha_hora,0,10));
       $total=0;

       //Mostramos los detalles
       $y=89;
       foreach($detalles as $det){
           $pdf::SetXY(20,$y);
           $pdf::MultiCell(10,0,$det->cantidad);

           $pdf::SetXY(32,$y);
           $pdf::MultiCell(120,0,utf8_decode($det->articulo));

           $pdf::SetXY(162,$y);
           $pdf::MultiCell(25,0,$det->precio_compra);

           $pdf::SetXY(187,$y);
           $pdf::MultiCell(25,0,sprintf("%0.2F",($det->precio_compra*$det->cantidad)));

           $total=$total+($det->precio_compra*$det->cantidad);
           $y=$y+7;
       }

       $pdf::SetXY(187,153);
       $pdf::MultiCell(20,0,"".sprintf("%0.2F", $ingreso->total-($ingreso->total*$ingreso->impuesto/($ingreso->impuesto+100))));
       $pdf::SetXY(187,160);
       $pdf::MultiCell(20,0,"".sprintf("%0.2F", ($ingreso->total*$ingreso->impuesto/($ingreso->impuesto+100))));
       $pdf::SetXY(187,167);
       $pdf::MultiCell(20,0,"".sprintf("%0.2F", $ingreso->total));

       $pdf::Output();
       exit;
   }
   public function reporte(){
        //Obtenemos los registros
        $registros=DB::table('ingreso as i')
           ->join('persona as p','i.idproveedor','=','p.idpersona')
           ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
           ->select('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra) as total'))
           ->orderBy('i.idingreso','desc')
           ->groupBy('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
           ->get();

        //Ponemos la hoja Horizontal (L)
        $pdf = new Fpdf('L','mm','A4');
        $pdf::AddPage();
        $pdf::SetTextColor(35,56,113);
        $pdf::SetFont('Arial','B',11);
        $pdf::Cell(0,10,utf8_decode("Listado Compras"),0,"","C");
        $pdf::Ln();
        $pdf::Ln();
        $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
        $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
        $pdf::SetFont('Arial','B',10); 
        //El ancho de las columnas debe de sumar promedio 190        
        $pdf::cell(35,8,utf8_decode("Fecha"),1,"","L",true);
        $pdf::cell(80,8,utf8_decode("Proveedor"),1,"","L",true);
        $pdf::cell(45,8,utf8_decode("Comprobante"),1,"","L",true);
        $pdf::cell(10,8,utf8_decode("Imp"),1,"","C",true);
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
           $pdf::cell(25,8,utf8_decode($reg->total),1,"","R",true);
           $pdf::Ln(); 
        }

        $pdf::Output();
        exit;
   }
}
