@extends('layouts.app_login')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 login">
            <div class="panel panel-default panel_sin_bordes">
                <div class="panel-body">
                    <div class="row ">
                        <div class="col-xs-12 col-sm-12 col-md-12 form-group imagen_inicial">
                            <img loading="lazy" src="{{ asset('img/logo.jpg') }}" id="imagen_inicio">
                        </div>
                        
                        <form method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}

                            <div class="{{ $errors->has('no_empleado') ? ' has-error' : '' }}">
                                <div class="col-xs-12 col-sm-12 col-md-12 form-group col-xs-offset-3 col-sm-offset-3 col-md-offset-3">
                                    <label for="lblno_empleado">No° Empleado:</label>
                                </div>
                                
                                <div class="col-xs-12 col-sm-12 col-md-12 form-group col-xs-offset-3 col-sm-offset-3 col-md-offset-3">
                                    <!-- <input type="text" id="username" name="username" class="form-control validanumericos my_input_class" placeholder="Usuario" required> -->
                                    <input id="no_empleado" type="text" class="form-control validanumericos my_input_class" placeholder="Usuario" name="no_empleado" value="{{ old('no_empleado') }}" required autofocus>
                                    @if ($errors->has('no_empleado'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('no_empleado') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="{{ $errors->has('password') ? ' has-error' : '' }}">
                                <div class="col-xs-12 col-sm-12 col-md-12 form-group col-xs-offset-3 col-sm-offset-3 col-md-offset-3">
                                    <label for="lblpassword">Contraseña:</label>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 form-group col-xs-offset-3 col-sm-offset-3 col-md-offset-3">
                                    <input id="password" type="password" name="password" class="form-control my_input_class" onkeyup="Validar()" placeholder="Contraseña" required>
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-xs-12 col-sm-12 col-md-12 form-group col-xs-offset-3 col-sm-offset-3 col-md-offset-3">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Recordar
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <!-- <div class="col-md-8 col-md-offset-4"> -->
                                    <!-- <button type="submit" class="btn btn-primary">
                                        Login
                                    </button> -->
                                    <div class="col-xs-12 col-sm-12 col-md-12 form-group col-xs-offset-3 col-sm-offset-3 col-md-offset-3">
                                        <button type="submit" class="EnviarContactoDetalleProducto btn-lg" id="login">Ingresar</button>
                                    </div>

                                    <!-- <a class="btn btn-link" href="{{ route('password.request') }}">
                                        Forgot Your Password?
                                    </a>
                                </div> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/login.js') }}"></script>
@endsection
