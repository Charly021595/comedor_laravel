<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Exceptions;
use App\Platillos;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use App\User;

class ComedorController extends Controller
{
    public function tipo_platillo(Request $request){
        $data = null;
        try {
            DB::connection("sqlsrv3")->beginTransaction();

            $tipoplatillo = $request->tipoplatillo;
            $ubicacion = $request->txtUbicacion;

            $valores = array($tipoplatillo, $ubicacion);

            // $datos_platillos = DB::connection("sqlsrv2")->select("RHCom_ObtenerPlatillos");
            $datos_platillos = DB::connection("sqlsrv3")->select("RHCom_ObtenerPlatillos ?, ?", $valores);
            DB::connection("sqlsrv3")->commit();
            $data = $datos_platillos != null ? $datos_platillos : array();

            if (count($datos_platillos) != 0) {
                $data = array(
                    'data' => $datos_platillos,
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

    public function info_platillo(Request $request){
        $data = null;
        try {
            DB::connection("sqlsrv3")->beginTransaction();

            $InfoPlatillo = $request->InfoPlatillo;
        
            $valores = array($InfoPlatillo);

            $datos_info_platillos = DB::connection("sqlsrv3")->select("RHCom_ObtenerInfoPlatillo ?", $valores);
            DB::connection("sqlsrv3")->commit();
            $data = $datos_info_platillos != null ? $datos_info_platillos : array();

            if (count($datos_info_platillos) != 0) {
                $data = array(
                    'data' => $datos_info_platillos,
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
            DB::connection("sqlsrv3")->rollBack();
            $data = array(
                'message' => $th->getmessage(),
                'status' => 'error',
                'code' => 400
            );
        }
        return response()->json($data, $data["code"]);
    }

    public function guardar_platillo(Request $request){
        $data = null;
        $valores = array();
        $valores_validar_pedidos = array();
        $valores_comedor_subsidiado = array();
        $valores_comedor_green_spot = array();
        $mensaje = '';
        try {
            DB::connection("sqlsrv3")->beginTransaction();
            $user = \Auth::user();
            $NoEmpleado = $user->no_empleado;
            $NombreEmpleado = $user->nombre;
            $TipoPlatillo = $request->txtTipoPlatillo;
            $Ubicacion =  $request->txtUbicacion;
            $FechaDeOrden =  $request->FechaDeOrden;
            $arrayListadoPlatilloUnico = json_decode($request->arrayListadoPlatilloUnico, true);
            $arrayListadoGreenSpot = json_decode($request->arrayListadoGreenSpot, true);
            $Tipo_Empleado = $user->Turno;
            $pedidoporcomedor = $request->pedidoporcomedor;

            $valores = array($NoEmpleado, $NombreEmpleado, $TipoPlatillo, $FechaDeOrden, $Ubicacion, $pedidoporcomedor, $Tipo_Empleado);
            $valores_validar_pedidos = array(date("Y-m-d", strtotime($FechaDeOrden)), $NoEmpleado);

            if ($TipoPlatillo == 3 && $NoEmpleado != 20000) {
                $validar_pedidos = DB::connection("sqlsrv3")->select("RHCom_ValidarPedidos ?,?", $valores_validar_pedidos);
                if (count($validar_pedidos) != 0) {
                    throw new Exception("pedido_duplicado", 1);
                }
            }

            $insertar_pedidos = DB::connection("sqlsrv3")->select("RHCom_GuardaPedido ?,?,?,?,?,?,?", $valores);
            switch ($TipoPlatillo) {
                case '3':
                    //Insertaria En Pedido de Subsidiado
                    foreach ($arrayListadoPlatilloUnico as $ListadoPlatilloUnico) {
                        $IdPedidoInsertado = $insertar_pedidos[0]->IdPedido;
                        $Precio = $ListadoPlatilloUnico['Precio'];
                        $NoPlatillos = $ListadoPlatilloUnico['NoPlatillos'];
                        $TipoPlatillo = $ListadoPlatilloUnico['TipoPlatillo'];
                        $Total = $ListadoPlatilloUnico['Total'];
                        $FechaPedido = $ListadoPlatilloUnico['FechaPedido'];
                        $Comentario =  utf8_decode($ListadoPlatilloUnico['Comentario']);
                        $Platillo  = $ListadoPlatilloUnico['Platillo'];
                        $valores_comedor_subsidiado = array($IdPedidoInsertado, $Precio, $NoPlatillos, $TipoPlatillo, $Total, $FechaPedido, $Comentario, $Platillo);
                        
                        $insertar_pedidos_subsidiado = DB::connection("sqlsrv3")->select("RHCom_GuardaPedidoComedorSubsidiado ?,?,?,?,?,?,?,?", $valores_comedor_subsidiado);
                        DB::connection("sqlsrv3")->commit();
                    }
                break;

                case '4':
                    //Insertaria En Pedido de green spot
                    foreach ($arrayListadoGreenSpot as $ListadoGreenSpot) {
                        $IdPedidoInsertado = $insertar_pedidos[0]->IdPedido;
                        $Posicion = $ListadoGreenSpot['Posicion'];
                        $IdPlatillo = $ListadoGreenSpot['IdPlatillo'];
                        $Platillo = utf8_decode($ListadoGreenSpot['Platillo']);
                        $Comentario = utf8_decode($ListadoGreenSpot['Comentario']);
                        $TipoPlatillo = $TipoPlatillo;
                        $KCal =  utf8_decode($ListadoGreenSpot['KCal']);
                        $Cantidad = $ListadoGreenSpot['Cantidad'];
                        $Precios = $ListadoGreenSpot['Precios'];
                        $Total = $ListadoGreenSpot['Total'];
                        $FechaPedido = $FechaDeOrden;
                        $valores_comedor_green_spot = array($IdPedidoInsertado, $Posicion, $IdPlatillo, $Platillo, $Comentario, $TipoPlatillo, $KCal, $Cantidad, $Precios, $Total, $FechaPedido);
                        // var_dump($valores_comedor_green_spot);
                            
                        $insertar_pedidos_green_spot = DB::connection("sqlsrv3")->select("RHCom_GuardaPedidoComedorGreenSpot ?,?,?,?,?,?,?,?,?,?,?", $valores_comedor_green_spot);
                        DB::connection("sqlsrv3")->commit();
                    }
                break;
                
                default:
                    $mensaje = 'El tipo de comida no es soportado';
                break;
            }
                
            if ($mensaje != '') {
                $data = array(
                    "estatus" => "success",
                    'code' => 200
                );
            }else{
                $data = array(
                    "estatus" => "success",
                    "mensaje" => $mensaje,
                    'code' => 200
                );
            }

        } catch (\Exception $e) {
            DB::connection("sqlsrv3")->rollBack();
            $data = array(
                'estatus' => $e->getMessage(),
                'code' => 400
            );
        }
        
        return $data;
    }

    public function guardar_platillo_gs(Request $request){
        $data = null;
        $valores = array();
        $valores_comedor_green_spot = array();
        $mensaje = '';
        $contador = 0;
        try {
            DB::connection("sqlsrv3")->beginTransaction();
            $NoEmpleado = $request->txtNumEmpleadoLogeado;
            $user = User::select("*")
            ->where("no_empleado", "=", $NoEmpleado)
            ->first();
            $NombreEmpleado = $user->nombre;
            $TipoPlatillo = $request->TipoPlatillo;
            $Ubicacion =  $request->Ubicacion;
            $FechaDeOrden =  $request->FechaDeOrden;
            $arrayListadoGreenSpot = json_decode($request->arrayListadoGreenSpot, true);
            $Tipo_Empleado = $user->Turno;
            $pedidoporcomedor = $request->pedidoporcomedor;
            $valores = array($NoEmpleado, $NombreEmpleado, $TipoPlatillo, $FechaDeOrden, $Ubicacion, $pedidoporcomedor, $Tipo_Empleado);

            $insertar_pedidos = DB::connection("sqlsrv3")->select("RHCom_GuardaPedido ?,?,?,?,?,?,?", $valores);
            switch ($TipoPlatillo) {
                case '4':
                    //Insertaria En Pedido de green spot
                    foreach ($arrayListadoGreenSpot as $ListadoGreenSpot) {
                        $IdPedidoInsertado = $insertar_pedidos[0]->IdPedido;
                        // $IdPedidoInsertado = "RHCom-000005";
                        $Posicion = $ListadoGreenSpot['Posicion'];
                        $IdPlatillo = $ListadoGreenSpot['IdPlatillo'];
                        $Platillo = $ListadoGreenSpot['Platillo'];
                        $Comentario = $ListadoGreenSpot['Comentario'];
                        $TipoPlatillo = $TipoPlatillo;
                        $KCal =  $ListadoGreenSpot['KCal'];
                        $Cantidad = $ListadoGreenSpot['Cantidad'];
                        $Precios = $ListadoGreenSpot['Precios'];
                        $Total = $ListadoGreenSpot['Total'];
                        $FechaPedido = $FechaDeOrden;
                        $valores_comedor_green_spot = array($IdPedidoInsertado, $Posicion, $IdPlatillo, $Platillo, $Comentario, $TipoPlatillo, $KCal, $Cantidad, $Precios, $Total, $FechaPedido);
                        // var_dump($valores_comedor_green_spot);
                            
                        $insertar_pedidos_green_spot = DB::connection("sqlsrv3")->select("RHCom_GuardaPedidoComedorGreenSpot ?,?,?,?,?,?,?,?,?,?,?", $valores_comedor_green_spot);
                        DB::connection("sqlsrv3")->commit();
                    }
                break;
                
                default:
                    $mensaje = 'El tipo de comida no es soportado';
                break;
            }
                
            if ($mensaje != '') {
                $data = array(
                    "estatus" => "success",
                    'code' => 200
                );
            }else{
                $data = array(
                    "estatus" => "success",
                    "mensaje" => $mensaje,
                    'code' => 200
                );
            }

        } catch (\Exception $e) {
            DB::connection("sqlsrv3")->rollBack();
            $data = array(
                'estatus' => $e->getMessage(),
                'code' => 400
            );
        }
        
        return $data;
    }

    public function vista_comedor_gs(){
        return view("comedor.comedor_gs");
    }

    public function listar_comida_gs(Request $request){
        $data = null;
        try {
            DB::connection("sqlsrv3")->beginTransaction();

            $Fecha = isset($request->daterange) ? $request->daterange : '';
			$fecha_inicial = "";
			$fecha_final = "";
			$numero_empleado = isset($request->numero_empleado) ? $request->numero_empleado : '';
            $ubicacion = isset($request->ubicacion) ? $request->ubicacion : '';
            $valores = '';

            if ($Fecha != '') {
				list($f_inicio, $f_final) = explode(" - ", $Fecha);//Extrae la fecha inicial y la fecha final en formato espa?ol
				list ($dia_inicio,$mes_inicio,$anio_inicio) = explode("/", $f_inicio);//Extrae fecha inicial 
				$fecha_inicial="$anio_inicio-$mes_inicio-$dia_inicio";//Fecha inicial formato ingles
				list($dia_fin,$mes_fin,$anio_fin) = explode("/",$f_final);//Extrae la fecha final
				$fecha_final = "$anio_fin-$mes_fin-$dia_fin";
			}

			if (($fecha_inicial != "" && $fecha_inicial != null) && ($fecha_final != "" && $fecha_final != null) && ($ubicacion != "")) {
                $valores = array($fecha_inicial, $fecha_final, $numero_empleado, $ubicacion);
				$listado_pedidos = DB::connection("sqlsrv3")->select("RHCom_ListadoPedidoGreenSpotSemanal ?, ?, ?, ?", $valores);
			}
            DB::connection("sqlsrv3")->commit();

            if (count($listado_pedidos) != 0) {
                $data = array(
                    'data' => $listado_pedidos,
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
            DB::connection("sqlsrv3")->rollBack();
            $data = array(
                'message' => $th->getmessage(),
                'status' => 'error',
                'code' => 400
            );
        }
        return $data;
    }

    public function guardar_platillo_semanal(Request $request){
        $data = null;
        $valores = array();
        $valores_validar_pedidos = array();
        $valores_comedor_subsidiado = array();
        $valores_comedor_green_spot = array();
        $mensaje = '';
        try {
            DB::connection("sqlsrv3")->beginTransaction();
            $user = User::select("*")
            ->where("no_empleado", "=", $request->no_empleado)
            ->first();
            $NoEmpleado = $request->no_empleado;
            $NombreEmpleado = $request->nombre;
            $TipoPlatillo = $request->txtTipoPlatillo;
            $Ubicacion =  $request->txtUbicacion;
            $FechaDeOrden =  $request->FechaDeOrden;
            $arrayListadoPlatilloUnico = json_decode($request->arrayListadoPlatilloUnico, true);
            $arrayListadoGreenSpot = json_decode($request->arrayListadoGreenSpot, true);
            $Tipo_Empleado = $user->Turno;
            $pedidoporcomedor = $request->pedidoporcomedor;

            $valores = array($NoEmpleado, $NombreEmpleado, $TipoPlatillo, $FechaDeOrden, $Ubicacion, $pedidoporcomedor, $Tipo_Empleado);
            $valores_validar_pedidos = array(date("Y-m-d", strtotime($FechaDeOrden)), $NoEmpleado);

            if ($TipoPlatillo == 3 && $NoEmpleado != 20000) {
                $validar_pedidos = DB::connection("sqlsrv3")->select("RHCom_ValidarPedidos ?,?", $valores_validar_pedidos);
                if (count($validar_pedidos) != 0) {
                    throw new Exception("pedido_duplicado", 1);
                }
            }

            $insertar_pedidos = DB::connection("sqlsrv3")->select("RHCom_GuardaPedido ?,?,?,?,?,?,?", $valores);
            switch ($TipoPlatillo) {
                case '3':
                    //Insertaria En Pedido de Subsidiado
                    foreach ($arrayListadoPlatilloUnico as $ListadoPlatilloUnico) {
                        $IdPedidoInsertado = $insertar_pedidos[0]->IdPedido;
                        $Precio = $ListadoPlatilloUnico['Precio'];
                        $NoPlatillos = $ListadoPlatilloUnico['NoPlatillos'];
                        $TipoPlatillo = $ListadoPlatilloUnico['TipoPlatillo'];
                        $Total = $ListadoPlatilloUnico['Total'];
                        $FechaPedido = $ListadoPlatilloUnico['FechaPedido'];
                        $Comentario =  utf8_decode($ListadoPlatilloUnico['Comentario']);
                        $Platillo  = $ListadoPlatilloUnico['Platillo'];
                        $valores_comedor_subsidiado = array($IdPedidoInsertado, $Precio, $NoPlatillos, $TipoPlatillo, $Total, $FechaPedido, $Comentario, $Platillo);
                        
                        $insertar_pedidos_subsidiado = DB::connection("sqlsrv3")->select("RHCom_GuardaPedidoComedorSubsidiado ?,?,?,?,?,?,?,?", $valores_comedor_subsidiado);
                        DB::connection("sqlsrv3")->commit();
                    }
                break;

                case '4':
                    //Insertaria En Pedido de green spot
                    foreach ($arrayListadoGreenSpot as $ListadoGreenSpot) {
                        $IdPedidoInsertado = $insertar_pedidos[0]->IdPedido;
                        $Posicion = $ListadoGreenSpot['Posicion'];
                        $IdPlatillo = $ListadoGreenSpot['IdPlatillo'];
                        $Platillo = utf8_decode($ListadoGreenSpot['Platillo']);
                        $Comentario = utf8_decode($ListadoGreenSpot['Comentario']);
                        $TipoPlatillo = $TipoPlatillo;
                        $KCal =  utf8_decode($ListadoGreenSpot['KCal']);
                        $Cantidad = $ListadoGreenSpot['Cantidad'];
                        $Precios = $ListadoGreenSpot['Precios'];
                        $Total = $ListadoGreenSpot['Total'];
                        $FechaPedido = $FechaDeOrden;
                        $valores_comedor_green_spot = array($IdPedidoInsertado, $Posicion, $IdPlatillo, $Platillo, $Comentario, $TipoPlatillo, $KCal, $Cantidad, $Precios, $Total, $FechaPedido);
                        // var_dump($valores_comedor_green_spot);
                            
                        $insertar_pedidos_green_spot = DB::connection("sqlsrv3")->select("RHCom_GuardaPedidoComedorGreenSpot ?,?,?,?,?,?,?,?,?,?,?", $valores_comedor_green_spot);
                        DB::connection("sqlsrv3")->commit();
                    }
                break;
                
                default:
                    $mensaje = 'El tipo de comida no es soportado';
                break;
            }
                
            if ($mensaje != '') {
                $data = array(
                    "estatus" => "success",
                    'code' => 200
                );
            }else{
                $data = array(
                    "estatus" => "success",
                    "mensaje" => $mensaje,
                    'code' => 200
                );
            }

        } catch (\Exception $e) {
            DB::connection("sqlsrv3")->rollBack();
            $data = array(
                'estatus' => $e->getMessage(),
                'code' => 400
            );
        }
        
        return $data;
    }

    public function cambiar_estatus_pedido(Request $request){
        $data = null;
        $valores = array();
        $validar = false;
        try {
            DB::connection("sqlsrv3")->beginTransaction();
            $id_pedido = $request->id_pedido;
			$estatus_comedor = $request->estatus_comedor;

            $valores = array($id_pedido, $estatus_comedor);

            $cambiar_estatus_pedido = DB::connection("sqlsrv3")->update("RHCom_EstatusComedor ?, ?", $valores);
            $validar = true;
                
            if ($validar) {
                $data = array(
                    "estatus" => "success",
                    'code' => 200
                );
                DB::connection("sqlsrv3")->commit();
            }else{
                $data = array(
                    "estatus" => "error",
                    "mensaje" => "ocurrio un error",
                    'code' => 400
                );
                $validar = false;
            }

        } catch (\Exception $e) {
            DB::connection("sqlsrv3")->rollBack();
            $data = array(
                "estatus" => "error_sistema",
                'message' => $e->getMessage(),
                'code' => 400
            );
        }
        
        return $data;
    }

    public function nomina(Request $request){
        $data = null;
        $valores = array();
        $listado_estatus = array();
        try {
            DB::connection("sqlsrv3")->beginTransaction();
			$Fecha = $request->daterange;
			$fecha_inicial = "";
			$fecha_final = "";
			$numero_empleado = isset($request->numero_empleado) ? $request->numero_empleado : '' ;
			if ($Fecha != '') {
				list($f_inicio, $f_final) = explode(" - ", $Fecha);//Extrae la fecha inicial y la fecha final en formato espa?ol
				list ($dia_inicio,$mes_inicio,$anio_inicio) = explode("/", $f_inicio);//Extrae fecha inicial 
				$fecha_inicial="$anio_inicio-$mes_inicio-$dia_inicio";//Fecha inicial formato ingles
				list($dia_fin,$mes_fin,$anio_fin) = explode("/",$f_final);//Extrae la fecha final
				$fecha_final = "$anio_fin-$mes_fin-$dia_fin";
			}

			if (($fecha_inicial != "" && $fecha_inicial != null) && ($fecha_final != "" && $fecha_final != null)) {
                $valores = array($fecha_inicial, $fecha_final, $numero_empleado);
                $listado_estatus = DB::connection("sqlsrv3")->select("RHCom_validar_estatus_Green_Spot_Semanal ?, ?, ?", $valores);
			}

            DB::connection("sqlsrv3")->commit();

            if (count($listado_estatus) != 0) {
                $data = array(
                    'data' => $listado_estatus,
                    'estatus' => 'success',
                    'code' => 200
                );
            }else{
                $data = array(
                    'mensaje' => 'No hay registros',
                    'estatus' => 'error',
                    'code' => 400
                );
            }

        } catch (\Exception $e) {
            DB::connection("sqlsrv3")->rollBack();
            $data = array(
                'estatus' => 'error_sql',
                'mensaje' => $e->getMessage(),
                'code' => 400
            );
        }
        
        return $data;
    }

    public function enviar_nomina(Request $request){
        $data = null;
        $valores = array();
        $valores_actualizar_estatus = array();
        $valores_insertar_conciliacion = array();
        $listado_procesadas = array();
        $sp_listado_procesadas = array();
        $validar = false;
        try {
            DB::connection("sqlsrv3")->beginTransaction();
            $id_conciliado = '';
			$datos = isset($request->datos) ? $request->datos : '';
			$Fecha = isset($request->Fecha) ? $request->Fecha : '';
			$fecha_inicial = "";
			$fecha_final = "";
			$estatus_enviado = isset($request->estatus_enviado) ? $request->estatus_enviado : '';
			$listado_procesadas = isset($request->listado_procesadas) ? $request->listado_procesadas : 0;
			$numero_conciliado = isset($request->numero_conciliado) ? $request->numero_conciliado : '';
			$numero_empleado = isset($request->numero_empleado) ? $request->numero_empleado : 0;
			
			if ($Fecha != '') {
				list($f_inicio, $f_final) = explode(" - ", $Fecha);//Extrae la fecha inicial y la fecha final en formato espa?ol
				list ($dia_inicio,$mes_inicio,$anio_inicio) = explode("/", $f_inicio);//Extrae fecha inicial 
				$fecha_inicial="$anio_inicio-$mes_inicio-$dia_inicio";//Fecha inicial formato ingles
				list($dia_fin,$mes_fin,$anio_fin) = explode("/",$f_final);//Extrae la fecha final
				$fecha_final = "$anio_fin-$mes_fin-$dia_fin";
			}

			if (($fecha_inicial != "" && $fecha_inicial != null) && ($fecha_final != "" && $fecha_final != null)) {
                $valores = array($fecha_inicial, $fecha_final, 1, $numero_conciliado, $numero_empleado);
                $sp_listado_procesadas = DB::connection("sqlsrv3")->select("RHCom_Listar_Procesadas ?, ?, ?, ?, ?", $valores);
			}

            $id_conciliado = DB::connection("sqlsrv3")->select("RHCom_Obtener_IdConciliado");
            
            if (count($id_conciliado) != 0) {
                $id_consecutivo = $id_conciliado != NULL && $id_conciliado != '' ? str_pad(substr($id_conciliado, 6) + 1, 6, '0', STR_PAD_LEFT) : '000001';
                $id_conciliado_nuevo = $id_conciliado != '' && $id_conciliado != NULL ? 'RHCon-'.$id_consecutivo : 'RHCon-'.$id_consecutivo;
            }else{
                $id_conciliado_nuevo = "RHCon-000001";
            }

            if ($listado_procesadas == 0) {
                foreach ($datos['data'] as $dato) {
                    if ($dato['EstatusComedor'] == 1 && $dato['EstatusEnviado'] == 0) {
                        $IdPedido = $dato["IdPedido"];
                        $valores_actualizar_estatus = array($IdPedido, $estatus_enviado);
                        $actualizar_estatus = DB::connection("sqlsrv3")->update("RHCom_AcualizarEstatus ?, ?", $valores_actualizar_estatus);
                        DB::connection("sqlsrv3")->commit();
                        $validar = true;
                    }
                }
            }else{
                foreach ($sp_listado_procesadas as $dato) {
                    if ($dato['EstatusComedor'] == 1 && $dato['EstatusEnviado'] == 1) {
                        $IdPedido = $dato["IdPedido"];
                        $valores_actualizar_estatus = array($IdPedido, $estatus_enviado);
                        $actualizar_estatus = DB::connection("sqlsrv3")->update("RHCom_AcualizarEstatus ?, ?", $valores_actualizar_estatus);

                        $id_pedido = $dato["IdPedido"];
                        $no_empleado = $dato["NoEmpleado"];
                        $total_platillos = $dato["NoPlatillo"];
                        $total_pagar = $dato["Total"];
                        $estatus = 0;
                        $tipo_empleado = $dato["Tipo_Empleado"];
                        
                        $parametros = array($id_conciliado_nuevo, $id_pedido, $no_empleado, $total_platillos, $total_pagar, $estatus, $tipo_empleado);
                        $insertar_conciliacion = DB::connection("sqlsrv3")->select("RHCom_Insertar_Con ?, ?, ?, ?, ?, ?, ?", $valores_insertar_conciliacion);

                        DB::connection("sqlsrv3")->commit();
                        $validar = true;
                    }
                }
            }

            if ($validar) {
                $data = array(
                    "estatus" => "success",
                    "Datos" => $datos,
                    "code" => 200
                );
            }else{
                $data = array(
                    'mensaje' => 'No hay registros',
                    'estatus' => 'error',
                    'code' => 400
                );
            }

        } catch (\Exception $e) {
            DB::connection("sqlsrv3")->rollBack();
            $data = array(
                'estatus' => 'error_sql',
                'mensaje' => $e->getMessage(),
                'code' => 400
            );
        }
        
        return $data;
    }

    public function vista_listado_menu(){
        return view("comedor.listado_menu");
    }

    public function guardar_nuevo_platillo(Request $request){
        $data = null;
        try {
            DB::beginTransaction();
            $user = \Auth::user();

            $nombre_comida = $request->nombre_comida;
            $precio_comida = $request->precio_comida;
            $tipo_comida = $request->tipo_comida;
            $calorias = $request->calorias;
            $estatus_comida = $request->estatus_comida;
            $ubicacion_comida = $request->ubicacion_comida;
            $NoEmpleado = $user->no_empleado;

            // $validate = $this->validate($request, [
            //     'nombre_alumno' => 'required',
            //     'body' => 'required'
            // ]);

            $crear_platillo = new Platillos();
            $crear_platillo->Comida = $nombre_comida;
            $crear_platillo->Precio = $precio_comida;
            $crear_platillo->TipoComida = $tipo_comida;
            $crear_platillo->Calorias = $calorias;
            $crear_platillo->Estatus = $estatus_comida;
            $crear_platillo->Ubicacion = $ubicacion_comida;
            $crear_platillo->usuario = $NoEmpleado;

            if ($crear_platillo->save()) {
                DB::commit();
                $data = array(
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

    public function listar_platillos(){
        $data = null;
        try {
            DB::beginTransaction();
            $listado_platillos = Platillos::select()
            ->orderBy('Ubicacion', 'asc')
            ->get();

            if (count($listado_platillos) != 0) {
                DB::commit();
                $data = array(
                    'data' => $listado_platillos,
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
