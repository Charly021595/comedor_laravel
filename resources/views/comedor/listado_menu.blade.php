@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Listado Menu</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Listado Menu</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<section class="content">
    <div class="container-fluid">
        <div class="row" id="registro_platillo">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">Menus</div>
                    <div class="card-body">
                        <!-- Small boxes (Stat box) -->
                        <div class="row">
                            <div class=" container col-12">
                                <div class="box">
                                    <div class="box-header">
                                        <form action="" method="POST" id="form_listado_menu">
                                            {{ csrf_field() }}
                                            <div class="form-group row">
                                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                                    <label for="lblNombreComida">Nombre Comida:</label>
                                                </div>
                                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                    <input type="text" class="form-control" id="nombre_comida" name="nombre_comida" placeholder="Comida">
                                                </div>
                                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                                    <label for="lblPrecioComida">Precio:</label>
                                                </div>
                                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                    <input type="number" class="form-control" id="precio_comida" name="precio_comida" placeholder="Precio">
                                                </div>
                                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                                    <label for="lblTipoComida">Tipo comida:</label>
                                                </div>
                                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                    <select name="tipo_comida" id="tipo_comida" class="form-control">
                                                        <option value="">Selecciona una Opción</option>
                                                        <option value="3">Platillo Único</option>
                                                        <option value="4">Green Spot</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                                    <label for="lblCaloriasComida">Calorias:</label>
                                                </div>
                                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                    <input type="text" class="form-control" id="calorias" name="calorias" placeholder="Calorias">
                                                </div>
                                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                                    <label for="lblEstatusComida">Estatus:</label>
                                                </div>
                                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                    <select name="estatus_comida" id="estatus_comida" class="form-control">
                                                        <option value="">Selecciona una Opción</option>
                                                        <option value="1">Disponible</option>
                                                        <option value="0">No disponible</option>
                                                    </select>
                                                </div>
                                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                                    <label for="lblUbicacionComida">Ubicación:</label>
                                                </div>
                                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                    <select name="ubicacion_comida" id="ubicacion_comida" class="form-control">
                                                        <option value="">Selecciona una Opción</option>
                                                        <option value="1">T.OP</option>
                                                        <option value="2">APODACA</option>
                                                        <option value="3">CIENEGA DE FLORES</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                                    <label for="lblImagenComida">Imagen Comida:</label>
                                                </div>
                                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                    <!-- <input type="file" name="imagen_comida" id="imagen_comida" class="form-control" accept="image/png,image/jpeg"> -->
                                                    <input type="file" name="imagen_comida" id="imagen_comida" class="inputfile inputfile-1" accept="image/png,image/jpeg"/>
                                                    <label for="imagen_comida" id="lblImagenComida">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                                        <span class="iborrainputfile">Seleccionar Imagen</span>
                                                    </label>
                                                </div>
                                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                                    <label for="">Ver listado</label>
                                                </div>
                                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                    <label class="content-input">
                                                        <input type="checkbox" name="listado" id="listado" value="0">
                                                        <i><span class="span_no">NO</span></i>
                                                    </label>
                                                </div>
                                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                                    <button type="button" id="registrar_platillo" class="btn btn-primary btn-lg btn-block">Registrar Platillo</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="listado_platillos" style="display:none;">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">Listado</div>
                    <div class="card-body">
                        <div class="row" id="cargando_tabla">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 centrar">
                                <img id="cargando_tabla_platillos" class="cargando_tablas" src="{{ asset('img/loading.gif') }}">
                            </div>
                        </div>
                        <div class="row" id="mostrar_platillos" style="display:none;">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                                <table id="tabla_platillos" class="table table-hover table-bordered 
                                table-responsive-xs table-responsive-sm table-responsive-md 
                                table-responsive-lg table-responsive-xl table-responsive-xxl" style="width:100%;">
                                    <thead>
                                        <tr>
                                        <th scope="col">Comida</th>
                                        <th scope="col">Precio</th>
                                        <th scope="col">Tipo de Comida</th>
                                        <th scope="col">Calorias</th>
                                        <th scope="col">Estatus</th>
                                        <th scope="col">Ubicación</th>
                                        <th scope="col">Usuario</th>
                                        <th scope="col">Acciones</th> 
                                        </tr>
                                    </thead>
                                </table> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->

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

</section>
<script src="{{ asset('js/listado_menu.js') }}?t=<?=time()?>"></script>
@endsection