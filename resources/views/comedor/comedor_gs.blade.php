@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Comedor Green Spot</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Comedor Green Spot</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">Green Spot</div>
            <div class="card-body">
                <!-- Small boxes (Stat box) -->
				<div class="row">
                    <div class=" container col-xs-12">
                        <div class="box">
                            <div class="box-header">
                                <form action="" method="POST" id="form_greenspot_comedor_semanal">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                            <label for="lblNombreVisita">Fecha:</label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <input type="text" style="background: #FFF" class="form-control" id="txtFechaSeleccionado" name="daterange" placeholder="Fecha" readonly>
                                        </div>
                                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                            <button type="button" class="btn btn-secondary boton-secundario" id="btnClearDate" style="width: 37px; height: 35px; padding:3px;margin-left: -17px; border-radius: 5px;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                            <button type="button" class="btn btn-primary btn-lg btn-block" onclick="MostrarInforme();" >Buscar listado</button>
                                        </div>
                                    </div>
                                </form>
                                <!--Filtros de numero empleado-->
                                <div class="form-group row">
                                    <div id="div_fake" class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="display:none;">
                                    </div>
                                    <div id="div_fake_dos" class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="display:none;">
                                    </div>
                                    <div id="filrado_empleado" class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="display:none;">
                                        <label for="lblNombreVisita">Filtrar por No. Empleado:</label>
                                    </div>
                                    <div id="input_text_no_empleado" class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="display:none;">
                                        <input type="text"  class="form-control" maxlength="6" id="txtNumeroEmpleado" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                    </div>
                                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                        <button type="button" class="btn btn-primary btn-lg btn-block" onclick="CargarPedido();" >Hacer Pedido</button>
                                    </div>
                                </div>

                                <!--Descargar Archivos-->
                                <div style="text-align:right;display:none;" id="boton_descarga_excel">
                                    <button class="btn btn-primary" onclick="DescargarTabla()">Export to XLS</button>
                                    <button id="btn_nomina" class="btn btn-primary">Pasar a Nómina</button> 
                                    <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" onclick="AbrirModal()">Enivar Listado.</button> -->
                                </div>
                            </div>
                            <div id="box-body" style="padding: 0px 10px 10px 10px;">  
                            <section id="Industria" class="CeroPadCeroMar ">
                                <div class="col-md-12 col-xs-12">
                                    <center>
                                    <h2 class="MoverInnovacion" style="font-size: 5vmin;color: #0F196C;font-family: Lettera Text Std;">
                                        
                                    </h2>
                                    </center>
                                </div>
                            </section>
                        </div>
                    </div>
				</div>
            </div>
        </div>
        
        
    </div><!-- /.container-fluid -->
    
    <!-- Listado del green spot -->
    <!-- <div id="div_tabla" class="row" style="display:none;">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">Listado Green Spot</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="container col-xs-12 col-sm-12 col-md-12">
                                    <div class="box">
                                        <div class="box-header">
                                            <div class="col-12 centrar">
                                                <img id="loading_comedor" src="{{ asset('img/loading.gif') }}" style="display:none;">
                                                <div id="EspacioTabla" style="display:none;" class="col-md-12 col-xs-12 scroll_pedido_tabla_principal_gs">
                                                    <table  id='TablaComedor' class='table table-bordered table-hover TablaResponsiva'>
                                                        <thead>
                                                            <tr class='table-header'>
                                                                <th scope='col'>No. Orden</th>
                                                                <th scope='col'>No. Empleado</th>
                                                                <th scope='col' >Empleado</th>
                                                                <th scope='col'>No. Platillo</th>
                                                                <th scope='col'>Platillo</th>
                                                                
                                                                <th scope='col'>Precio </th>
                                                                <th scope='col'>Total</th>
                                                                <th scope='col'>Ubicación</th>
                                                                <th scope='col'>FechaPedido</th>
                                                                <th scope='col'>Estatus Enviado</th>
                                                                <th scope='col'>Estatus Comedor</th>
                                                                <th scope='col' colspan='2'>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id='ContenidoListados'>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <div id="div_tabla" class="row" style="display:none;">
        <div class="card">
            <div class="card-header">Listado Green Spot</div>
            <div class="card-body">
                <div id="EspacioTabla" style="display:none;" class="col-md-12 col-xs-12 scroll_pedido_tabla_principal_gs">
                    <table id='TablaComedor' class='table table-bordered table-hover TablaResponsiva'>
                        <thead>
                            <tr class='table-header'>
                                <th scope='col'>No. Orden</th>
                                <th scope='col'>No. Empleado</th>
                                <th scope='col' >Empleado</th>
                                <th scope='col'>No. Platillo</th>
                                <th scope='col'>Platillo</th>
                                
                                <th scope='col'>Precio </th>
                                <th scope='col'>Total</th>
                                <th scope='col'>Ubicación</th>
                                <th scope='col'>FechaPedido</th>
                                <th scope='col'>Estatus Enviado</th>
                                <th scope='col'>Estatus Comedor</th>
                                <th scope='col' colspan='2'>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id='ContenidoListados'>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="div_cargando" class="row" style="display:none;">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">Listado Green Spot</div>
                <div class="card-body">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 centrar">
                        <img id="loading_comedor" src="{{ asset('img/loading.gif') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="ModalCargaEvidenciaVisual" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="titulo_modal_crear_pedido">Crear pedido</h4>
                    <button type="button" class="close" data-dismiss="modal"><i id="cross_cerrar" class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">Formulario Pedido</div>
                                <div class="card-body">
                                    <section id="Industria" class="CeroPadCeroMar">
                                        <form action="" method="post" id="form_solicitar_platillos_gs">
                                            {{ csrf_field() }}
                                            <div class="form-group row" id="divIDVisita">
                                                <label for="lblNombreVisita" class="col-sm-3 col-form-label">No. Empleado:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" maxlength="6" name="txtNumEmpleadoLogeado" id="txtNumEmpleadoLogeado" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                                </div>
                                            </div>
                                            <div class="form-group row" id="divIDVisita">
                                                <label for="lblNombreVisita" class="col-sm-3 col-form-label">Empleado:</label>
                                                <div class="col-sm-8">
                                                <input type="text" class="form-control" name="txtNombreEmpleadoLogeado" id="txtNombreEmpleadoLogeado" disabled>
                                                </div>
                                            </div>
                                            <div class="form-group row" style="display:none;">
                                                <label for="lblNombreVisita" class="col-sm-3 col-form-label">Fecha:</label>
                                                <div class="col-sm-8">
                                                    <input type="date"  class="form-control" name="txtFechaDia" id="txtFechaDia" disabled>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="divIDVisita" style="display:none;">
                                                <label for="lblNombreVisita" class="col-sm-3 col-form-label">Ubicación:</label>
                                                <div class="col-sm-8">
                                                    <select class="form-control" name="txtUbicacion" id="txtUbicacion" disabled>
                                                        <option value="0"> Seleccione Ubicación</option>
                                                        <option value="1"> Torre TOP</option>
                                                        <option value="2"> Apodaca</option>
                                                        <option value="3"> Cienega</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="divIDVisita" style="display:none;">
                                                <label for="lblNombreVisita" class="col-sm-3 col-form-label">Tipo de platillo:</label>
                                                <div class="col-sm-8">
                                                    <select class="form-control" name="txtTipoPlatillo" id="txtTipoPlatillo" onchange="TipoPlatillo()" disabled>
                                                        <option value="0"> Seleccione el tipo de platillo</option>
                                                        <option value="4">Platillo Especial</option>
                                                        
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="DivTotal" style="display:none;">
                                                <label for="lblNombreVisita" class="col-sm-3 col-form-label">Total:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="txtTotalPlatillo" id="txtTotalPlatillo" value="0.00" disabled>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="DivPrecio" style="display:none;">
                                                <label for="lblNombreVisita" class="col-sm-3 col-form-label">Precio:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="txtPrecioPlatillo" id="txtPrecioPlatillo" value="49.00" disabled>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="DivComentario" style="display:none;">
                                                <label for="lblNombreVisita" class="col-sm-3 col-form-label">Comentarios:</label>
                                                <div class="col-sm-8">
                                                    <textarea name="txtComentarioPlatillo" id="txtComentarioPlatillo" class="form-control" rows="5" cols="200"  maxlength="250"></textarea>
                                                </div>
                                            </div>
                                            <div id="ComidaGR" style="display:none;" class="form-group row" >
                                                <div class="form-group col-md-3 col-xs-3" id="divIDVisita">
                                                    <label for="lblNombreVisita" class="col-sm-12 col-xs-12 col-form-label">Platillo:</label>
                                                </div>
                                                <div class="form-group col-sm-8 col-xs-8">
                                                    <select class="form-control" name="txtProductoSeleccionadoGR" id="txtProductoSeleccionadoGR" onchange="InfoPlatillo()">
                                                    </select>
                                                </div>
                                                <div class="col-md-12 col-xs-12 " id="divIDVisita" style='display:none;'>
                                                    <label for="lblNombreVisita" class="col-sm-12 col-form-label">Kcal (c/u):</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control" id="txtCaloriasGR"  disabled >
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3 col-xs-3" id="divIDVisita">
                                                    <label for="lblNombreVisita" class="col-sm-12 col-form-label">Cantidad:</label>
                                                </div>
                                                <div class="form-group col-sm-8 col-xs-8">
                                                    <input type="text" class="form-control" id="txtNumPlatilloGR" pattern="\d*" maxlength="2" min="1" value="1" onchange="ValidarPlatillosGR()" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                                </div>
                                                <div class="col-md-12 col-xs-12 " id="divIDVisita" style="display:none;">
                                                    <label for="lblNombreVisita" class="col-sm-12 col-form-label">Precio:</label>
                                                    <div class="col-sm-12">
                                                        
                                                        <input type="text" class="form-control" id="txtPrecioTotal" value="0.00" disabled >
                                                        <input type="text" class="form-control" id="txtPrecioGR" value="0.00" disabled style="display:none">
                                                        <input type="text" class="form-control" id="txtTipoPlatilloGR" disabled style="display:none" >
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-xs-12 " id="divIDVisita" style="display:none;">
                                                    <label for="lblNombreVisita" class="col-sm-12 col-form-label">Comentarios:</label>
                                                    <div class="col-sm-12">
                                                        <textarea id="txtComentariosGR" class="form-control" rows="5" cols="200" maxlength="250"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-12 col-xs-12 row">
                                                    <div class="col-md-12 col-xs-12 row" id="divIDVisita">
                                                        <button type="button" class="btn btn-primary ValidaBoton ajustar_btn" onclick="AgregarComidaGr();" id="GuardarOrden" >Agregar</button>
                                                    </div>
                                                </div>
                                                <div id="div_mostrar_tabla_pedido" class="form-group col-md-12 col-xs-12 row scroll_pedio_gs" style="display:none;">
                                                    <table id ="TablaGreenSpot"  class="table table-bordered table-hover TablaResponsiva">
                                                        <thead>
                                                        <tr>
                                                            <td scope="col" style="display:none">Posición</td>
                                                            <td scope="col" style="display:none">Id. Platillo</td>
                                                            <td scope="col" >Platillo</td>
                                                            <td scope="col" style="display:none">Comentario</td>
                                                            <td scope="col" style="display:none">Tipo Platillo</td>
                                                            <td scope="col" style='display:none;'>Kcal.</td>
                                                            <td scope="col" >Cantidad</td>
                                                            <td scope="col" style="display:none">Precios</td>
                                                            <td scope="col" style="display:none">Total</td>
                                                            <td scope="col">Acciones</td>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="ListadoComidaGr">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="DivFecha" style="display:none;">
                                                <label for="lblNombreVisita" class="col-sm-3 col-form-label">Fecha:</label>
                                                <div class="col-sm-8">
                                                <input type="text" class="form-control" id="txtFechaPedido" disabled>
                                                </div>
                                            </div>
                                        </form>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary ValidaBoton" id="solicitar_comida">Solicitar Comida</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Modal-->
</section>
<script src="{{ asset('js/comedor_gs.js') }}?t=<?=time()?>"></script>
@endsection