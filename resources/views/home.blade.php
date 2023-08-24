@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                Comedor ARZYZ
            </div>
            <div class="card-body">
                <form action="" method="post" id="form_solicitar_platillos">
                    {{ csrf_field() }}
                    <input type="hidden" id="tipo_empleado" name="tipo_empleado" value="0">
                    <div class="row form-group">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <label for="lblUbicacion">Ubicación:</label>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <select class="form-control" id="txtUbicacion" name="txtUbicacion">
                                <option value="0"> Seleccione Ubicación</option>
                                <option value="1" selected> Torre TOP</option>
                                <option value="2"> Apodaca</option>
                                <option value="3"> Cienega</option>
                            </select>
                        </div>   
                    </div>
                    <div class="row form-group">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <label for="lblTipo_Platillo">Tipo de platillo:</label>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <select class="form-control" name="txtTipoPlatillo" id="txtTipoPlatillo" onchange="TipoPlatillo()">
                                <option value="0"> Seleccione el tipo de platillo</option>
                                <option value="3">Platillo Único</option>
                                <option value="4">Platillo Green Spot</option>
                            </select>
                        </div>   
                    </div>
                    <div class="row form-group" id="div_comida_express" style="display:none;">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <label for="lblNumero_platillos">No. Platillos:</label>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <input type="text" class="form-control" name="txtNumPlatillo" id="txtNumPlatillo" onkeypress="return event.charCode >= 48 && event.charCode <= 57" min="1" value="1" onchange="ValidarPlatillos()">
                        </div>   
                    </div>
                    <div class="form-group row" id="div_total" style="display:none;">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <label for="lblTotal">Total:</label>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <input type="text" class="form-control" name="txtTotalPlatillo" id="txtTotalPlatillo" value="0.00" disabled>
                        </div>
					</div>
                    <div class="form-group row" id="DivPrecio" style="display:none;">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <label for="lblNombreVisita">Precio:</label>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <input type="text" class="form-control" name="txtPrecioPlatillo" id="txtPrecioPlatillo" value="47.50" disabled>
                        </div>
					</div>
                    <div class="form-group row" id="DivComentarios" style="display:none;">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <label for="lblNombreVisita">Comentarios:</label>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <textarea name="txtComentarioPlatillo" id="txtComentarioPlatillo" class="form-control" rows="5" cols="200"  maxlength="250"></textarea>  
                        </div>
					</div>
                    <div class="row form-group" id="div_comida_especial" style="display:none;">
                        <center>
                            <h4>Pedido Platillo Green Spot</h4>
                        </center>
                        <div class="form-group col-md-12 col-xs-12 row">
                            <div class="row">
                                <div class="col-md-6 col-xs-6 " id="divIDVisita">
                                    <label for="lblNombreVisita">Platillo:</label>
                                    <select class="form-control" name="txtProductoSeleccionadoGR" id="txtProductoSeleccionadoGR" onchange="InfoPlatillo()">
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-xs-12 " id="divIDVisita" style="display:none;">
                                <label for="lblNombreVisita" class="">Kcal (c/u):</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="txtCaloriasGR"  disabled >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-xs-6 " id="divIDVisita">
                                    <label for="lblNombreVisita">Cantidad:</label>
                                    <input type="number" class="form-control" id="txtNumPlatilloGR" min="1" value="1" onchange="ValidarPlatillosGR()" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                </div>
                            </div>
                            <div class="col-md-2 col-xs-12 " id="divIDVisita" style="display:none;">
                                <label for="lblNombreVisita" class="col-sm-4 col-form-label">Precio:</label>
                                <div class="col-sm-8">
                                    
                                    <input type="text" class="form-control" id="txtPrecioTotal" value="0.00" disabled >
                                    <input type="text" class="form-control" id="txtPrecioGR" value="0.00" disabled style="display:none">
                                    <input type="text" class="form-control" id="txtTipoPlatilloGR" disabled style="display:none" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-xs-6 row" id="divIDVisita">
                                    <label for="lblNombreVisita"></label>
                                    <center>
                                        <button type="button" class="btn btn-primary  btn-block ValidaBoton" onclick="AgregarComidaGr();" id="GuardarOrden" >Agregar</button>
                                    </center>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12 col-xs-12 row" style="display:none;">
                            <div class="col-md-4 col-xs-12 " id="divIDVisita">
                            <label for="lblNombreVisita" class="col-sm-4 col-form-label">Comentarios:</label>
                            <div class="col-sm-8">
                                <textarea id="txtComentariosGR" class="form-control" rows="5" cols="200" maxlength="250"></textarea>
                            </div>
                            </div>
                        </div>
                        <div   class="form-group col-md-12 col-xs-12 row">
                            <table id ="TablaGreenSpot"  class="table table-bordered table-hover TablaResponsiva">
                                <thead>
                                    <tr>
                                        <td scope="col" style="display:none">Posición</td>
                                        <td scope="col" style="display:none">Id. Platillo</td>
                                        <td scope="col" >Platillo</td>
                                        <td scope="col" style="display:none">Comentario</td>
                                        <td scope="col" style="display:none">Tipo Platillo</td>
                                        <td scope="col" style="display:none;" >Kcal.</td>
                                        <td scope="col" >Cantidad</td>
                                        <td scope="col" style="display:none;">Precios</td>
                                        <td scope="col"style="display:none;">Total</td>
                                        <td scope="col">Acciones</td>
                                    </tr>
                                </thead>
                                <tbody id="ListadoComidaGr">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="container" id="menu_listado" style="">
                        <div class="row">
                            <div class="col-12">
                                <h1 class="centrar">Platillos Green Spot</h1>
                            </div>
                        </div>
                        <div id="listado_platillos_gs" class="row">
                            <div class="col-12">
                                <h1 class="centrar">imagenes prueba</h1>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <button type="button" class="btn btn-primary btn-lg btn-block ValidaBoton" id="solicitar_comida">Solicitar Comida</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<script src="{{ asset('js/dashboard.js') }}?t=<?=time()?>"></script>
@endsection
