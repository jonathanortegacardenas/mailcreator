@extends('master')
@section('styles')
<link rel="stylesheet" href="{{asset('assets/data-tables/bootstrap3/dataTables.bootstrap.css')}}" />
<style>
.form-control-feedback{
    top:0 !important;
}

    .has-search .form-control-feedback {
        right: initial;
        left: 0;
        color: #ccc;
    }
    .has-search .form-control {
        padding-right: 12px;
        padding-left: 34px;
        }
</style>
@stop
@section('content')
<div class="btn-toolbar pull-right clearfix">
	<div class="btn-group">
      <a class="btn btn-circle show-tooltip" title="Agregar Campa&ntilde;a" href="{{url('campaigns/add')}}"><i class="fa fa-plus"></i></a>
   </div>
   <div class="btn-group">
      <a class="btn btn-circle show-tooltip" title="Exportar a PDF" href="{{url('campaigns/pdf')}}"><i class="fa fa-file-text-o"></i></a>
      <a class="btn btn-circle show-tooltip" title="Exportar a Excel" href="{{url('campaigns/csv')}}"><i class="fa fa-table"></i></a>
   </div>
   <div class="btn-group">
   	<a class="btn btn-circle show-tooltip" title="Actualizar" href="javascript:location.reload();"><i class="fa fa-repeat"></i></a>
   </div>
</div>
<br/><br/>
<div class="clearfix"></div>
<div class="table-responsive" style="border:0">
    <div>
        <div class="col-md-6">
            <form>
                <div class="form-group has-feedback has-search">
                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    <input value="{{ isset($_GET['buscando'])?$_GET['buscando']:'' }}" type="text" class="form-control" name="buscando" placeholder="Buscar">
                </div>
            </form>
        </div>    
        <div class="col-md-6" style="text-align:right;">{!! str_replace('/?', '?', $campaigns->appends(\Request::query())->render()) !!}</div>
    </div>
    
    <table class="table ">
        <thead>
            <tr>
                <th style="width:18px">#</th>
                <th>Campa&ntilde;a</th>
                <th>Link</th>
                <th>Fecha de Creaci&oacute;n</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if(count($campaigns)>0)
            <!--{{$i=0}}-->
                @foreach($campaigns as $campaign)
                 <tr class="table-flag-blue">
                    <td>{{++$i}}</td>
                    <td>{{$campaign->title}}</td>
                    <td>{{$campaign->link}}</td>
                    <td>{{$campaign->created_at}}</td>
                    <td><a class="btn btn-success show-tooltip" data-original-title="Previsualización" href="{{url('view/'.$campaign->id)}}" target='_blank'><i class="fa fa-search"></i></a>&nbsp;<a class="btn btn-success show-tooltip" data-original-title="Editar" href="{{url('campaigns/edit/'.$campaign->id)}}"><i class="fa  fa-pencil-square-o"></i></a>&nbsp;<a class="btn btn-info show-tooltip" data-original-title="Bloques del Mail" href="{{url('blocks/list/'.$campaign->id)}}" ><i class="fa fa-stack-exchange"></i></a>&nbsp;<a href="{{url('campaigns/delete/'.$campaign->id)}}" class="btn btn-danger show-tooltip" data-original-title="Eliminar" onclick='return confirm("Desea eliminar este registro?")'><i class="fa fa-trash-o"></i></a>&nbsp;<a href="{{url('campaigns/download/'.$campaign->id)}}" class="btn btn-lime show-tooltip" data-original-title="Descargar" onclick='return confirm("Desea descargar este correo?")'><i class="fa fa-download"></i></a>&nbsp;<a class="btn btn-info show-tooltip" data-original-title="Copiar Campaña" href="{{url('campaigns/copy/'.$campaign->id)}}" onclick="return confirm('Desea copiar este registro?');"><i class="fa fa-files-o"></i></a>&nbsp;
                    @if($type==0)
                      <a href="{{url('campaigns/users/'.$campaign->id)}}" class="btn btn-lime show-tooltip" data-original-title="Asignar Usuarios"><i class="fa fa-users"></i></a>
                    @endif
                    @if($campaign->envio==1)
                    <a class="btn btn-success show-tooltip" data-original-title="Enviar Mail" href="{{url('send/'.$campaign->id)}}" onclick="return confirm('Desea Enviar este correo?')" ><i class="fa fa-paper-plane-o"></i>
                    @endif
                    </td>
                </tr>
                @endforeach
            @else
            <tr><td colspan="4">No existen registros</td></tr>
            @endif

        </tbody>
    </table>
    <center>{!! str_replace('/?', '?', $campaigns->appends(\Request::query())->render()) !!}</center>
</div>
@stop
@section('scripts')
<script type="text/javascript" src="{{asset('assets/data-tables/jquery.dataTables.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/data-tables/bootstrap3/dataTables.bootstrap.js')}}"></script>
@stop