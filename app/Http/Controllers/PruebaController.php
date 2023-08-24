<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\User;
// use App\Pedidos;
// use App\PedidosCS;
// use App\PedidosGS;
// use App\Platillos;
// use App\JugadoresTenis;

class PruebaController extends Controller
{
    function pasar_usuarios(){
        try {
            DB::beginTransaction();
            // $pedidoss = DB::connection("sqlsrv2")->select("RHMet_ObtenerDatosEmpleados");
            // var_dump($datos_usuarios);
            // die();
            $datos_usuarios = DB::connection("sqlsrv2")->select("RHMet_ObtenerDatosEmpleados");
            foreach ($datos_usuarios as $datos_usuario) {
                if (is_object($datos_usuario)) {
                    $usuario_actualizado = User::select("*")
                    ->where("no_empleado", "=", $datos_usuario->Empleado)
                    ->first();
                    if (is_object($usuario_actualizado) && $datos_usuario->Sede != null && $datos_usuario->Turno != null) {
                        // $usuario_actualizado->Turno = $datos_usuario->Turno;
                        // $usuario_actualizado->Sede = $datos_usuario->Sede;
                        // if ($usuario_actualizado->update()) {
                        //     DB::commit();
                        //     echo "Se actualizo correctamente";   
                        // }else{
                        //     echo "No se logro actualizar";
                        // }
                    }else{
                        $valores = array($datos_usuario->Empleado); 
                        $datos = DB::connection("sqlsrv2")->select("Obtener_Usuario_Password ?", $valores);
                        // var_dump(bcrypt($datos[0]->Password));
                        $nombres = explode(" ", strtolower(mb_convert_encoding($datos_usuario->NombreCompleto, "HTML-ENTITIES", "UTF-8")));
                        // var_dump(strtolower(mb_convert_encoding($datos_usuario->NombreCompleto, 'ISO-8859-1', 'UTF-8')));
                        // var_dump(strtolower(utf8_decode($datos_usuario->NombreCompleto)));
                        // var_dump(strtolower(mb_convert_encoding($datos_usuario->NombreCompleto, "HTML-ENTITIES", "UTF-8")));
                        // die();
                        $usuario_creado = new User();
                        $usuario_creado->nombre = isset($datos_usuario->NombreCompleto) ? strtolower(mb_convert_encoding($datos_usuario->NombreCompleto, "HTML-ENTITIES", "UTF-8")) : '';
                        $usuario_creado->no_empleado = $datos_usuario->Empleado;
                        $usuario_creado->sede = isset($datos_usuario->Sede) ? strtolower(mb_convert_encoding($datos_usuario->Sede, "HTML-ENTITIES", "UTF-8")) : '';
                        $usuario_creado->tipo_empleado = isset($datos_usuario->Turno) ? $datos_usuario->Turno : 2;
                        $usuario_creado->email = isset($datos_usuario->email) && $datos_usuario->email != '' ? $nombres[0].'.'.$nombres[1].'_'.$datos_usuario->Empleado.'@arzyz.com' : $nombres[0].'.'.$nombres[1].'_'.$datos_usuario->Empleado.'@arzyz.com';
                        $usuario_creado->password = isset($datos[0]->Password) ? bcrypt($datos[0]->Password) : '';
                        $usuario_creado->password_update = 3;
                        // $usuario_creado->save();
                        if ($usuario_creado->save()) {
                            DB::commit();
                            echo "Usuario Creado";
                        }else{
                            echo "Usuario No Creado";
                        }
                    }
                }
                echo "<br>";
            }
            die();

            $data = array(
                'message' => 'Login Incorrecto',
                'status' => 'error',
                'code' => 400
            );

        } catch (\Throwable $th) {
            DB::rollBack();
            $data = array(
                'message' => $th->getmessage(),
                'status' => 'error',
                'code' => 400
            );
        }
        echo $data;
    }

    function Agregar_platillos_sucursales(){
        try {
            DB::beginTransaction();
            
            $platillos = DB::connection("sqlsrv5")->table("RHCom_Platillo")->where("Ubicacion", "=", 1)->get();
            $i = 1;
            // foreach ($platillos as $platillo) {
            //     $insertar_platillos = new Platillos();
            //     $insertar_platillos->Comida = $platillo->Comida;
            //     $insertar_platillos->Precio = $platillo->Precio;
            //     $insertar_platillos->TipoComida = $platillo->TipoComida;
            //     $insertar_platillos->Calorias = $platillo->Calorias;
            //     $insertar_platillos->Estatus = $platillo->Estatus;
            //     $insertar_platillos->Ubicacion = 3;
                
            //     if ($insertar_platillos->save()) {
            //         DB::commit();
            //         echo "Pedido Insertado numero: $i";
            //     }else{
            //         echo "Pedido No Insertado";
            //     }
            //     echo "<br />";
            //     $i++;
            // }
            die();

            $data = array(
                'message' => 'Login Incorrecto',
                'status' => 'error',
                'code' => 400
            );

        } catch (\Throwable $th) {
            DB::rollBack();
            $data = array(
                'message' => $th->getmessage(),
                'status' => 'error',
                'code' => 400
            );
        }
        echo $data;
    }

    function actualizar_foto_miss(){
        $data = null;
        $jugadores_guardados_arreglo = array();
        try {
            // DB::connection("sqlsrv4")->beginTransaction();
            // DB::beginTransaction();
            echo "-------------------------------------------------------------DOBLES----------------------------------------------------------------------------------------------------------------<br/>";
            $jugadoresD = DB::connection("sqlsrv4")->select("GS_ObtenerJugadoresDobles");
            $i = 1;
            foreach ($jugadoresD as $jugadorD) {
                // var_dump($jugadorD->NombreCompleto); 
                $jugador_ApPaterno = $jugadorD->ApPaterno != '' ? $jugadorD->ApPaterno : '-';
                $jugador_ApMaterno = $jugadorD->ApMaterno != '' ? $jugadorD->ApMaterno : '-';
                $club = $jugadorD->Club != '' ? $jugadorD->Club : '-';
                $rama = $jugadorD->Rama != '' ? $jugadorD->Rama : 1;
                // $img = $jugadorD->Img != '' ? $jugadorD->Img : '';
                // $imgqr = $jugadorD->ImgQR != '' ? $jugadorD->ImgQR : '';
                $valores = array($jugadorD->Nombre, $jugador_ApPaterno, $jugador_ApMaterno); 
                $jugador_validado = DB::connection("sqlsrv4")->select("ObtenerJugadorCopia  ?, ?, ?", $valores);
                if (count($jugador_validado) == 0) {
                    echo "Jugador Insertado Numero: ".$i."<br />";
                    var_dump($jugadorD);
                    die();
                    // $jugadores_guardados_arreglo = array($jugadorD->Categoria, $jugadorD->Nombre, $jugador_ApPaterno, $jugador_ApMaterno, $club, $rama); 
                    // $jugadores_guardados = DB::connection("sqlsrv4")->select("GS_GuardarJugadores  ?, ?, ?, ?, ?, ?", $jugadores_guardados_arreglo);
                    // $jugadores_guardados = DB::connection("sqlsrv4")->table("Jugadores");
                    // var_dump($jugadores_guardados->Nombre);
                    // die();
                    $jugadores_tenis = new JugadoresTenis();
                    $jugadores_tenis->Categoria = $jugadorD->Categoria;
                    $jugadores_tenis->Nombre = $jugadorD->Nombre;
                    $jugadores_tenis->ApPaterno = $jugador_ApPaterno;
                    $jugadores_tenis->ApMaterno = $jugador_ApMaterno;
                    $jugadores_tenis->Club = $club;
                    $jugadores_tenis->Rama = $rama;
                    $jugadores_tenis->save();
                    // DB::commit();
                    $i++;
                }else{
                }
            }
        } catch (\Throwable $th) {
            // DB::rollBack();
            // DB::connection("sqlsrv4")->rollBack();
            $data = array(
                'message' => $th->getmessage(),
                'status' => 'error',
                'code' => 400
            );
            var_dump($data);
            die();
        }
        // $jugadores = DB::connection("sqlsrv4")->table("jugadores")->get();
        // echo "-------------------------------------------------------------ORIGINAL----------------------------------------------------------------------------------------------------------------<br/>";
        // $jugadores = DB::connection("sqlsrv4")->select("GS_ObtenerJugadores");
        // foreach ($jugadores as $jugador) {
        //     var_dump($jugador->NombreCompleto);  
        // }
        // echo "<br />";
        // echo "-------------------------------------------------------------COPIA----------------------------------------------------------------------------------------------------------------<br/>";
        // $jugadoresC = DB::connection("sqlsrv4")->select("GS_ObtenerJugadoresCopia");
        // foreach ($jugadoresC as $jugadorC) {
        //     var_dump($jugadorC->NombreCompleto);  
        // }
        // echo "<br />";
    }

    function mover_registros(){
        try {
            DB::beginTransaction();
            $datos_pedidos= DB::connection("sqlsrv3")
            ->table("RHCom_Pedidos")
            ->where("Ubicacion", "=", 2)
            ->whereRaw('CONVERT(VARCHAR, FechaPedido, 23) LIKE ?', '%2023-07-20%')
            ->get();
            // var_dump($datos_pedidos);
            // die();
            $id_ultimo = 19916;
            // foreach ($datos_pedidos as $pedidos) {
            //     $id_ultimo++;
            //     // $valores = array($pedidos->IdPedido, $pedidos->NoEmpleado, $pedidos->NombreEmpleado, $pedidos->TipoPlatillo, $pedidos->Ubicacion, $pedidos->FechaPedido, 
            //     // $pedidos->FechaInserccion, $pedidos->Procesado, $pedidos->Pedidoporcomedor, $pedidos->EstatusEnviado, $pedidos->EstatusComedor, $pedidos->Tipo_Empleado, 
            //     // $pedidos->IDPlatillo, $pedidos->Tipo_Comedor, $pedidos->RangoFecha, $pedidos->PedidoUsuario);
            //     // var_dump($valores);
            //     // die();
            //     $pedido_creado = new Pedidos();
            //     $pedido_creado->id = $id_ultimo;
            //     $pedido_creado->IdPedido = $pedidos->IdPedido;
            //     $pedido_creado->NoEmpleado = $pedidos->NoEmpleado;
            //     $pedido_creado->NombreEmpleado = $pedidos->NombreEmpleado;
            //     $pedido_creado->TipoPlatillo = $pedidos->TipoPlatillo;
            //     $pedido_creado->Ubicacion = $pedidos->Ubicacion;
            //     $pedido_creado->FechaPedido = $pedidos->FechaPedido;
            //     $pedido_creado->FechaInserccion = $pedidos->FechaInserccion;
            //     $pedido_creado->Procesado = $pedidos->Procesado;
            //     $pedido_creado->Pedidoporcomedor = $pedidos->Pedidoporcomedor;
            //     $pedido_creado->EstatusEnviado = $pedidos->EstatusEnviado;
            //     $pedido_creado->EstatusComedor = $pedidos->EstatusComedor;
            //     $pedido_creado->Tipo_Empleado = $pedidos->Tipo_Empleado;
            //     $pedido_creado->IDPlatillo = $pedidos->IDPlatillo;
            //     $pedido_creado->Tipo_Comedor = $pedidos->Tipo_Comedor;
            //     $pedido_creado->RangoFecha = $pedidos->RangoFecha;
            //     $pedido_creado->PedidoUsuario = $pedidos->PedidoUsuario;
               
            //     if ($pedido_creado->save()) {
            //         DB::commit();
            //         echo "Pedido Insertado";
            //     }else{
            //         echo "Pedido No Insertado";
            //     }
            //     echo "<br />";
            // }
            die();
        } catch (\Throwable $th) {
            DB::rollBack();
            $data = array(
                'message' => $th->getmessage(),
                'status' => 'error',
                'code' => 400
            );
        }
        echo $data;
    }

    function mover_registros_cs(){
        try {
            DB::beginTransaction();
            $datos_pedidos= DB::connection("sqlsrv3")
            ->table("RHCom_ComedorSubsidiado")
            ->whereRaw('CONVERT(VARCHAR, FechaPedido, 23) LIKE ?', '%2023-07-20%')
            ->get();
            $id_ultimo = 12080;
            // foreach ($datos_pedidos as $pedidos) {
            //     $id_ultimo++;
            //     // $valores = array($pedidos->idComedorSub, $pedidos->idPedido, $pedidos->Precio, $pedidos->NoPlatillo, $pedidos->TipoPlatillo, $pedidos->Total, 
            //     // $pedidos->FechaPedido, $pedidos->FechaInserccion, $pedidos->Comentarios, $pedidos->Platillo, $pedidos->Precio_Break, $pedidos->PedidoUsuario);
            //     // var_dump($valores);
            //     // die();
            //     $pedido_creado = new PedidosCS();
            //     $pedido_creado->id = $id_ultimo;
            //     $pedido_creado->idComedorSub = $pedidos->idComedorSub;
            //     $pedido_creado->idPedido = $pedidos->idPedido;
            //     $pedido_creado->Precio = $pedidos->Precio;
            //     $pedido_creado->NoPlatillo = $pedidos->NoPlatillo;
            //     $pedido_creado->TipoPlatillo = $pedidos->TipoPlatillo;
            //     $pedido_creado->Total = $pedidos->Total;
            //     $pedido_creado->FechaPedido = $pedidos->FechaPedido;
            //     $pedido_creado->FechaInserccion = $pedidos->FechaInserccion;
            //     $pedido_creado->Comentarios = $pedidos->Comentarios;
            //     $pedido_creado->Platillo = $pedidos->Platillo;
            //     $pedido_creado->Precio_Break = $pedidos->Precio_Break;
            //     $pedido_creado->PedidoUsuario = $pedidos->PedidoUsuario;
               
            //     if ($pedido_creado->save()) {
            //         DB::commit();
            //         echo "Pedido Insertado CS";
            //     }else{
            //         echo "Pedido No Insertado CS";
            //     }
            //     echo "<br />";
            // }
            die();
        } catch (\Throwable $th) {
            DB::rollBack();
            $data = array(
                'message' => $th->getmessage(),
                'status' => 'error',
                'code' => 400
            );
        }
        echo $data;
    }

    function mover_registros_gs(){
        try {
            DB::beginTransaction();
            $datos_pedidos= DB::connection("sqlsrv3")
            ->table("RHCom_comedorGreenSpot")
            ->whereRaw('CONVERT(VARCHAR, FechaPedido, 23) LIKE ?', '%2023-07-20%')
            ->get();
            // foreach ($datos_pedidos as $pedidos) {
            //     // $valores = array($pedidos->IdPedido, $pedidos->NoEmpleado, $pedidos->NombreEmpleado, $pedidos->TipoPlatillo, $pedidos->Ubicacion, $pedidos->FechaPedido, 
            //     // $pedidos->FechaInserccion, $pedidos->Procesado, $pedidos->Pedidoporcomedor, $pedidos->EstatusEnviado, $pedidos->EstatusComedor, $pedidos->Tipo_Empleado, 
            //     // $pedidos->IDPlatillo, $pedidos->Tipo_Comedor, $pedidos->RangoFecha, $pedidos->PedidoUsuario);
            //     // var_dump($valores);
            //     // die();
            //     $pedido_creado = new PedidosGS();
            //     $pedido_creado->id = 10;
            //     $pedido_creado->IdComedorGr = $pedidos->IdComedorGr;
            //     $pedido_creado->IdPedido = $pedidos->IdPedido;
            //     $pedido_creado->Posicion = $pedidos->Posicion;
            //     $pedido_creado->IdPlatillo = $pedidos->IdPlatillo;
            //     $pedido_creado->Platillo = $pedidos->Platillo;
            //     $pedido_creado->Comentario = $pedidos->Comentario;
            //     $pedido_creado->TipoPlatillo = $pedidos->TipoPlatillo;
            //     $pedido_creado->Kcal = $pedidos->Kcal;
            //     $pedido_creado->Cantidad = $pedidos->Cantidad;
            //     $pedido_creado->Precio = $pedidos->Precio;
            //     $pedido_creado->Total = $pedidos->Total;
            //     $pedido_creado->FechaPedido = $pedidos->FechaPedido;
            //     $pedido_creado->FechaInsercion = $pedidos->FechaInsercion;
            //     $pedido_creado->PedidoUsuario = $pedidos->PedidoUsuario;
               
            //     if ($pedido_creado->save()) {
            //         DB::commit();
            //         echo "Pedido Insertado gs";
            //     }else{
            //         echo "Pedido No Insertado gs";
            //     }
            //     echo "<br />";
            // }
            die();
        } catch (\Throwable $th) {
            DB::rollBack();
            $data = array(
                'message' => $th->getmessage(),
                'status' => 'error',
                'code' => 400
            );
        }
        echo $data;
    }
}
