@extends('master')
@section('styles')
<link rel="stylesheet" type="text/css" href="{{asset('assets/bootstrap-colorpicker/css/colorpicker.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('assets/bootstrap-fileupload/bootstrap-fileupload.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('assets/bootstrap-switch/static/stylesheets/bootstrap-switch.css')}}" />

@stop
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
    <form action="{{$action}}" class="form-horizontal" id="validation-form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label" for="title">T&iacute;tulo de la Campa&ntilde;a:</label>
            <div class="col-sm-6 col-lg-4 controls">
                <input type="text" name="title" id="title" class="form-control" name="title" value="{{$campaign->title}}" data-rule-required="true" data-rule-minlength="3" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label" for="link">Enlace a:</label>
            <div class="col-sm-6 col-lg-4 controls">
                <input type="text" name="link" id="link" class="form-control" name="link" value="{{$campaign->link}}" data-rule-required="true" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Color del Tema</label>
            <div class="col-sm-6 col-lg-4 controls">
                <div class="input-group color colorpicker-default" data-color="{{$campaign->color}}" data-color-format="rgba">
                    <span class="input-group-addon"><i style="background-color: {{$campaign->color}}"></i></span>
                    <input type="text" class="form-control" value="{{$campaign->color}}" name="color"  data-rule-required="true" data-rule-minlength="7" >
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label" for="color">Color de Fondo:</label>
            <div class="col-sm-6 col-lg-4 controls">
                <div class="input-group color colorpicker-default" data-color="{{$campaign->background}}" data-color-format="rgba">
                    <span class="input-group-addon"><i style="background-color: {{$campaign->background}};"></i></span>
                    <input type="text" class="form-control" value="{{$campaign->background}}" name="background"  data-rule-required="true" data-rule-minlength="7" >
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label" for="facebook">Facebook:</label>
            <div class="col-sm-6 col-lg-4 controls">
            @if($campaign->facebook != '')
                <input type="text" name="facebook" id="facebook" class="form-control" data-rule-required="true" value="{{$campaign->facebook}}" data-rule-minlength="3" />
            @else
                <input type="text" name="facebook" id="facebook" class="form-control" data-rule-required="true" value="UDLAEcuador" data-rule-minlength="3" />
            @endif
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label" for="twitter">Twitter:</label>
            <div class="col-sm-6 col-lg-4 controls">
            @if($campaign->twitter != '')
                <input type="text" name="twitter" id="twitter" class="form-control" value="{{$campaign->twitter}}"  data-rule-required="true" data-rule-minlength="3" />
             @else
                <input type="text" name="twitter" id="twitter" class="form-control" value="UDLAEcuador"  data-rule-required="true" data-rule-minlength="3" />
            @endif
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label" for="linkedin">LinkedIn:</label>
            <div class="col-sm-6 col-lg-4 controls">
            @if($campaign->linkedin != '')
                <input type="text" name="linkedin" id="linkedin" class="form-control" value="{{$campaign->linkedin}}"  data-rule-required="true" data-rule-minlength="3" />
            @else
                <input type="text" name="linkedin" id="linkedin" class="form-control" value="school/universidad-de-las-americas-ecuador/"  data-rule-required="true" data-rule-minlength="3" />
            @endif
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label" for="youtube">YouTube:</label>
            <div class="col-sm-6 col-lg-4 controls">
            @if($campaign->youtube != '')
                <input type="text" name="youtube" id="youtube" class="form-control" value="{{$campaign->youtube}}" data-rule-required="true" data-rule-minlength="3" />
            @else
                <input type="text" name="youtube" id="youtube" class="form-control" value="user/UDLAUIO" data-rule-required="true" data-rule-minlength="3" />
            @endif
            </div>
        </div>
         <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label" for="instagram">Instagram:</label>
            <div class="col-sm-6 col-lg-4 controls">
            @if($campaign->instagram != '')
                <input type="text" name="instagram" id="instagram" class="form-control" value="{{$campaign->instagram}}" data-rule-required="true" data-rule-minlength="3" />
            @else
                <input type="text" name="instagram" id="instagram" class="form-control" value="udlaecuador/" data-rule-required="true" data-rule-minlength="3" />
            @endif
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Logo de Campa&ntilde;a</label>
            <div class="col-sm-3 col-lg-3 controls">
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="fileupload-new img-thumbnail" style="width: 200px; height: 150px;">
                        @if($campaign->logo=='')
                        <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" />
                        @else
                        <img src="{{asset('/uploads/'.$campaign->logo)}}" width="200" /> 
                        @endif
                    </div>
                    <div class="fileupload-preview fileupload-exists img-thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                    <div>
                        <span class="btn btn-default btn-file"><span class="fileupload-new">Cambiar Imagen</span>
                            <span class="fileupload-exists">Cambiar</span>
                            <input type="file" name="logo" class="file-input" /></span>
                        <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Quitar</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 col-lg-3 controls">
                <div class="col-sm-6 col-lg-4 controls">
                    @if($campaign->logo=="blanco.png")
                    <input type="radio" name="logo_pred" id="logo_pred" class="form-control" value="blanco.png" checked="checked"  />Logo Blanco
                    @else
                    <input type="radio" name="logo_pred" id="logo_pred" class="form-control" value="blanco.png"  />Logo Blanco
                    @endif
                </div>
            </div>
            <div class="col-sm-3 col-lg-3 controls">
                <div class="col-sm-6 col-lg-4 controls">
                    @if($campaign->logo=="rojo.png")
                    <input type="radio" name="logo_pred" id="logo_pred" class="form-control" value="rojo.png" checked="checked"  />Logo Vino
                    @else
                    <input type="radio" name="logo_pred" id="logo_pred" class="form-control" value="rojo.png"  />Logo Vino
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label" for="menu">Men&uacute;:</label>
            <div class="col-sm-6 col-lg-10 controls">
                <input type="text" name="menu_url[]" class="form-control" data-rule-url="true" value="{{$campaign->menus[0]['url']}}" placeholder="Direcci&oacute;n WEB"/>&nbsp;&nbsp;<input type="text" name="menu_nombres[]" class="form-control" value="{{$campaign->menus[0]['nombre']}}" data-rule-minlength="3" placeholder="Nombre" /><br />
                <div style="float:right"><a href="javascript:addMenu(1)" class="btn btn-success"><i class="fa fa-plus"></i></a></div>
            </div>
            <div id="menus">

                @if(count($campaign->menus)>1)
                @for($i=1; $i<count($campaign->menus);$i++)
                    <div id="{{$i}}" class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                        <input type="text" value="{{$campaign->menus[$i]['url']}}" name="menu_url[]" class="form-control" data-rule-required="true" data-rule-url="true" placeholder="Direcci&oacute;n WEB"/>&nbsp;&nbsp;
                        <input type="text" value="{{$campaign->menus[$i]['nombre']}}"  name="menu_nombres[]" class="form-control" data-rule-required="true" data-rule-minlength="3" placeholder="Nombre" /><br />
                        <div style="float:right"><a href="javascript:addMenu({{$i+1}})" class="btn btn-success" ><i class="fa fa-plus"></i></a><a href="javascript:removeMenu({{$i}})" class="btn btn-danger"><i class="fa fa-minus"></i></a></div>
                    </div>
                    @endfor
                    @endif
            </div>
        </div> 
        <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label" for="menu">Desea Enviar este correo:</label>
            <div class="col-sm-6 col-lg-10 controls">
                <div class="make-switch" data-on-label="SI" data-off-label="NO">
                  @if($campaign->envio==1)
                  <input type="checkbox" name="envio" value="1" checked />
                  @else
                  <input type="checkbox" name="envio" value="1"/>
                  @endif
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label" for="facebook">Cuenta:</label>
            <div class="col-sm-6 col-lg-4 controls">
                <input type="text" name="cuenta" id="cuenta" class="form-control" value="{{$campaign->cuenta}}" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label" for="facebook">Asunto:</label>
            <div class="col-sm-6 col-lg-4 controls">
                <input type="text" name="asunto" id="asunto" class="form-control" value="{{$campaign->asunto}}"  />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label" for="facebook">Para (separados con ;):</label>
            <div class="col-sm-6 col-lg-4 controls">
                <input type="text" name="destino" id="destino" class="form-control"  value="{{$campaign->destino}}" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                <button type="button" class="btn" onclick="window.location.href ='{{url('campaigns/list')}}'">Cancelar</button>
                <input type="submit" class="btn btn-primary" value="Guardar">
            </div>
        </div>
    </form>
    @stop
    @section('scripts')
    <script type="text/javascript" src="{{asset('assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/jquery-validation/dist/additional-methods.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/bootstrap-fileupload/bootstrap-fileupload.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/bootstrap-switch/static/js/bootstrap-switch.js')}}"></script>
    <script type="text/javascript">
                    function addMenu(num) {
                        var newNum = num + 1;
                        var html = '<div id="' + num + '" class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">' +
                                '<input type="text" name="menu_url[]" class="form-control" data-rule-required="true" data-rule-url="true" placeholder="Direcci&oacute;n WEB"/>&nbsp;&nbsp;<input type="text" name="menu_nombres[]" class="form-control" data-rule-required="true" data-rule-minlength="3" placeholder="Nombre" /><br />' +
                                '<div style="float:right"><a href="javascript:addMenu(' + newNum + ')" class="btn btn-success" ><i class="fa fa-plus"></i></a><a href="javascript:removeMenu(' + num + ')" class="btn btn-danger"><i class="fa fa-minus"></i></a></div>' +
                                '</div>';
                        $('#menus').append(html);
                        //return false;
                    }
                    function removeMenu(num) {
                        $('#' + num).remove();
                        // return false;
                    }
    </script>
    @stop