@extends('master')
@section('content')

@if (Session::has('code')&&Session::has('message'))
    @if(Session::get('code')!='200')
<div class="alert alert-danger">
    @else
<div class="alert alert-success">    
    @endif
    <button class="close" data-dismiss="alert">×</button>
    <strong>Resultado:</strong> {{Session::get('message')}}.<br><br>
</div>
@endif
<form action="{{$action}}" class="form-horizontal" id="validation-form" method="post">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group">
        <label class="col-sm-3 col-lg-2 control-label" for="username">Nombres:</label>
        <div class="col-sm-6 col-lg-4 controls">
            <input type="text" name="first_name" id="first_name" class="form-control" name="first_name" value="{{$user->first_name}}" data-rule-required="true" data-rule-minlength="3" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-lg-2 control-label" for="username">Apellidos:</label>
        <div class="col-sm-6 col-lg-4 controls">
            <input type="text" name="last_name" id="last_name" class="form-control" name="last_name" value="{{$user->last_name}}" data-rule-required="true" data-rule-minlength="3" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-lg-2 control-label" for="email">Correo Electr&oacute;nico:</label>
        <div class="col-sm-6 col-lg-4 controls">
            <input type="email" name="email" id="email" class="form-control"  value="{{$user->email}}" data-rule-required="true" data-rule-email="true" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 col-lg-2 control-label" for="password">Contrase&ntilde;a:</label>
        <div class="col-sm-6 col-lg-4 controls">
            <input type="password" name="password" id="password" class="form-control" data-rule-required="true" data-rule-minlength="6" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-lg-2 control-label" for="confirm-password">Confirmar Contrase&ntilde;a:</label>
        <div class="col-sm-6 col-lg-4 controls">
            <input type="password" name="confirm-password" id="confirm-password" class="form-control" data-rule-required="true" data-rule-minlength="6" data-rule-equalTo="#password" />
        </div>
    </div>      
    <div class="form-group">
        <label class="col-sm-3 col-lg-2 control-label" for="confirm-password">Tipo:</label>
        <div class="col-sm-6 col-lg-4 controls">
            <select name="type" id="type" class="form-control" data-rule-required="true">
                <option value="0">Administrador</option>
                <option value="1">Usuario</option>
            </select>
        </div>
    </div>                             
    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
            <button type="button" class="btn" onclick="window.location.href='{{url('users/list')}}'">Cancelar</button>
            <input type="submit" class="btn btn-primary" value="Guardar">
        </div>
    </div>
</form>
@stop
@section('scripts')
<script type="text/javascript" src="assets/jquery-validation/dist/jquery.validate.min.js"></script>
<script type="text/javascript" src="assets/jquery-validation/dist/additional-methods.min.js"></script>
@stop