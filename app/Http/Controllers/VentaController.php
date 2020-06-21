<?php

namespace sis_Inventario\Http\Controllers;

use Illuminate\Http\Request;

use sis_Inventario\Http\Requests;

use sis_Inventario\Venta;
use sis_Inventario\DetalleVenta;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sis_Inventario\Http\Requests\VentaFormRequest;
use DB;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class VentaController extends Controller
{
    public function __construct()
    {

    }
    public function index(Request $request)
    {
        if ($request)
        {
            $query=trim($request->get('searchText'));
            $ventas=DB::table('venta as v')
             ->join('persona as p','v.idcliente','=','p.idpersona')
             ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
             ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado','v.total_venta')
             ->where('v.num_comprobante','LIKE','%'.$query.'%')
             ->orderBy('v.idventa','desc')
             ->groupBy('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado')
             ->paginate(7);
             return view('ventas.venta.index',["ventas"=>$ventas,"searchText"=>$query]);

        }
    }
    //FUNCION CREAR
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
    //FUNCION ALMACENAR DETALLES Y VENTAS
    public function store (VentaFormRequest $request)
    {
        try {
             DB::beginTransaction();
             $venta=new Venta;
             $venta->idcliente=$request->get('idcliente');
             $venta->tipo_comprobante=$request->get('tipo_comprobante');
             $venta->serie_comprobante=$request->get('serie_comprobante');
             $venta->num_comprobante=$request->get('num_comprobante');
             $venta->total_venta=$request->get('total_venta');

             $mytime = Carbon::now('America/Santiago');
             $venta->fecha_hora=$mytime->toDateTimeString();
             $venta->impuesto='19';
             $venta->estado='A';
             $venta->save();

             $idarticulo = $request->get('idearticulo');
             $cantidad = $request->get('cantidad');
             $descuento = $request->get('descuento');
             $precio_venta = $request->get('precio_venta');
            // PARA RECORRER EL ARRAY DE PRECIO VENTA | DESCUENTO | ARTICULOS | DETALLES DESDE LA POSICION 0

             $cont= 0;
            //DATOS ENVIADOS DESDE REGISTRO DE INGRESOS
            //MIENTRAS SEA MENOS QUE
             while($cont < count($idarticulo))
             {  $detalle = new DetalleVenta();
                $detalle->idventa= $venta->idventa;
                $detalle->idarticulo= $idarticulo[$cont];
                $detalle->cantidad= $cantidad[$cont];
                $detalle->descuento= $descuento[$cont];
                $detalle->precio_venta= $precio_venta[$cont];
                $detalle ->save();
                $cont=$cont+1;     
             }

             DB::commit();
             
        } catch (\Exception $e) 
        {   
            DB::rollback();
        }

        return Redirect::to('ventas/venta');
    }
    //MOSTRAR VENTAS Y DETALLES EN UNA VISTA
    public function show($id)
    {
        //MOSTRAR DATOS
        $venta=DB::table('venta as v')
            ->join('persona as p','v.idcliente','=','p.idpersona')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado', 'v.total_venta')
            ->where('v.idventa','=',$id)
            ->first();

        $detalles=DB::table('detalle_venta as d')
            ->join('articulo as a','d.idarticulo','=','a.idarticulo')
            ->select('a.nombre as articulo','d.cantidad','d.descuento','d.precio_venta')
            ->where('d.idventa','=',$id)
            ->get();
        return view("ventas.venta.show",["venta"=>$venta,"detalles"=>$detalles]);
    }
//CAMBIAR ESTADO DEL OBJETO
    public function destroy($id)
    {
        $venta=Venta::findOrFail($id);
        $venta->Estado='C';
        $venta->update();
        return Redirect::to('ventas/venta');
    }
}
