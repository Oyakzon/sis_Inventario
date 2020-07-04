<?php

namespace sis_Inventario\Http\Controllers;

use Illuminate\Http\Request;

use sis_Inventario\Http\Requests;

use sis_Inventario\User;
use Illuminate\Support\Facades\Redirect;
use sis_Inventario\Http\Requests\UsuarioFormRequest;
use DB;
use Fpdf;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        if ($request) {
            $query = trim($request->get('searchText'));
            $usuarios = DB::table('users')->where('name', 'LIKE', '%' . $query . '%')
                ->orderBy('id', 'desc')
                ->paginate(7);
            return view('seguridad.usuario.index', ["usuarios" => $usuarios, "searchText" => $query]);
        }
    }

    public function create()
    {
        return view("seguridad.usuario.create");
    }
    public function store(UsuarioFormRequest $request)
    {
        $usuario = new User;
        $usuario->name = $request->get('name');
        $usuario->role = $request->get('role');
        $usuario->email = $request->get('email');
        $usuario->password = bcrypt($request->get('password'));
        $usuario->save();
        return Redirect::to('seguridad/usuario');
    }

    public function edit($id)
    {
        return view("seguridad.usuario.edit", ["usuario" => User::findOrFail($id)]);
    }
    public function update(UsuarioFormRequest $request, $id)
    {
        $usuario = User::findOrFail($id);
        $usuario->name = $request->get('name');
        $usuario->role = $request->get('role');
        $usuario->email = $request->get('email');
        $usuario->password = bcrypt($request->get('password'));
        $usuario->update();
        return Redirect::to('seguridad/usuario');
    }
    public function destroy($id)
    {
        $usuario = DB::table('users')->where('id', '=', $id)->delete();
        return Redirect::to('seguridad/usuario');
    }
    public function reporte()
    {
        //Obtenemos los registros


        $usuario = DB::table('users')
            ->select('id', 'name', 'role', 'email','created_at','updated_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = new Fpdf();
        $pdf::AddPage();
        $pdf::SetTextColor(35, 56, 113);
        $pdf::SetFont('Arial', 'B', 11);
        $pdf::Cell(0, 10, utf8_decode("Listado de Usuarios"), 0, "", "C");
        $pdf::Ln();
        $pdf::Ln();
        $pdf::SetTextColor(0, 0, 0);  // Establece el color del texto 
        $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
        $pdf::SetFont('Arial', 'B', 10);
        //El ancho de las columnas debe de sumar promedio 190        
        $pdf::cell(10, 8, utf8_decode("ID"), 1, "", "L", true);
        $pdf::cell(35, 8, utf8_decode("Nombre"), 1, "", "L", true);
        $pdf::cell(25, 8, utf8_decode("Rol"), 1, "", "L", true);
        $pdf::cell(55, 8, utf8_decode("Email"), 1, "", "L", true);
        $pdf::cell(35, 8, utf8_decode("Creado"), 1, "", "L", true);
        $pdf::cell(35, 8, utf8_decode("Actualizado"), 1, "", "L", true);

        $pdf::Ln();
        $pdf::SetTextColor(0, 0, 0);  // Establece el color del texto 
        $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
        $pdf::SetFont("Arial", "", 9);

        foreach ($usuario as $usu) {
            $pdf::cell(10, 6, utf8_decode($usu->id), 1, "", "L", true);
            $pdf::cell(35, 6, utf8_decode($usu->name), 1, "", "L", true);
            $pdf::cell(25, 6, utf8_decode($usu->role), 1, "", "L", true);
            $pdf::cell(55, 6, utf8_decode($usu->email), 1, "", "L", true);
            $pdf::cell(35, 6, utf8_decode($usu->created_at), 1, "", "L", true);
            $pdf::cell(35, 6, utf8_decode($usu->updated_at), 1, "", "L", true);
            $pdf::Ln();
        }

        $pdf::Output();
        exit;
    }
}
