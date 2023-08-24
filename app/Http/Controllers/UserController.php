<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Exceptions;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use App\User;

class UserController extends Controller
{
    public function traer_sede(Request $request){
        $data = null;
        try {
            DB::beginTransaction();

            $usuario = \Auth::user();

            DB::commit();

            if (count($usuario) != 0) {
                $data = array(
                    'data' => $usuario,
                    'status' => 'success',
                    'code' => 200
                );
            }else{
                $data = array(
                    'message' => 'No hay registros',
                    'status' => 'error',
                    'code' => 400
                );
            }

        } catch (\Throwable $th) {
            DB::rollBack();
            $data = array(
                'message' => $th->getmessage(),
                'status' => 'error',
                'code' => 400
            );
        }
        return response()->json($data, $data["code"]);
    }

    public function traer_datos_usuarios(Request $request){
        $data = null;
        try {
            DB::beginTransaction();

            $no_empleado = $request->empleado;

            $usuario = User::select("nombre", "no_empleado", "Turno", "Sede")
            ->where("no_empleado", "=", $no_empleado)
            ->first();

            DB::commit();

            if (count($usuario) != 0) {
                $data = array(
                    'data' => $usuario,
                    'status' => 'success',
                    'code' => 200
                );
            }else{
                $data = array(
                    'message' => 'No hay registros',
                    'status' => 'error',
                    'code' => 400
                );
            }

        } catch (\Throwable $th) {
            DB::rollBack();
            $data = array(
                'message' => $th->getmessage(),
                'status' => 'error',
                'code' => 400
            );
        }
        return response()->json($data, $data["code"]);
    }
}
