@extends('master')
@section('styles')
<link rel="stylesheet" href="{{asset('assets/data-tables/bootstrap3/dataTables.bootstrap.css')}}" />
@stop
@section('content')
<form id="users" action="{{url('campaigns/users/'.$campaign_id)}}" method="post">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group">
        <label class="col-sm-3 col-lg-2 control-label" for="username">Usuarios:</label>
        <div class="col-sm-6 col-lg-4 controls">
            <select name="user" class="form-control" data-rule-required="true">
              @if(count($users)>0)
                @foreach($users as $user)
                  <option value="{{$user->id}}">{{$user->first_name . ' ' . $user->last_name}}</option>
                @endforeach
              @endif
            </select>
        </div>
        <button class="btn btn-info" type="submit">Agregar Usuario</button>
    </div>
</form>
<div class="btn-toolbar pull-right clearfix">
	<div class="btn-group">
      <a class="btn btn-circle show-tooltip" title="Agregar Campa&ntilde;a" href="{{url('campaigns/users')}}"><i class="fa fa-plus"></i></a>
   </div>
   <div class="btn-group">
   	<a class="btn btn-circle show-tooltip" title="Actualizar" href="javascript:location.reload();"><i class="fa fa-repeat"></i></a>
   </div>
</div>
<br/><br/>
<div class="clearfix"></div>
<div class="table-responsive" style="border:0">
    <table class="table table-advance" id="table1">
        <thead>
            <tr>
                <th style="width:18px">#</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Quitar</th>
            </tr>
        </thead>
        <tbody>
            @if(count($campaign_users)>0)
            <!--{{$i=0}}-->
                @foreach($campaign_users as $user)
                <tr class="table-flag-blue">
                    <td>{{++$i}}</td>
                    <td>{{$user->first_name.' '.$user->last_name}}</td>
                    <td>{{$user->email}}</td>
                    <td><a href="{{url('campaigns/users/delete/'.$user->id)}}" class="btn btn-danger show-tooltip" data-original-title="Quitar" onclick='return confirm("Desea eliminar este registro?")'><i class="fa fa-trash-o"></i></a>&nbsp;</td>
                </tr>
                @endforeach
            @else
            <tr><td colspan="4">No existen registros</td></tr>
            @endif
        </tbody>
    </table>
</div>
@stop
@section('scripts')
<script type="text/javascript" src="{{asset('assets/data-tables/jquery.dataTables.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/data-tables/bootstrap3/dataTables.bootstrap.js')}}"></script>
@stop