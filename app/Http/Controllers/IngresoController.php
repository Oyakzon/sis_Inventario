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
             $ingreso->impuesto='19';
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
}
