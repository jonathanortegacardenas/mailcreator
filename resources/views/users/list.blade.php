@extends('master')
@section('styles')
<link rel="stylesheet" href="{{asset('assets/data-tables/bootstrap3/dataTables.bootstrap.css')}}" />
@stop
@section('content')
<div class="btn-toolbar pull-right clearfix">
	<div class="btn-group">
      <a class="btn btn-circle show-tooltip" title="Agregar Usuario" href="{{url('users/add')}}"><i class="fa fa-plus"></i></a>
   </div>
   <div class="btn-group">
      <a class="btn btn-circle show-tooltip" title="Exportar a PDF" href="{{url('users/pdf')}}"><i class="fa fa-file-text-o"></i></a>
      <a class="btn btn-circle show-tooltip" title="Exportar a Excel" href="{{url('users/csv')}}"><i class="fa fa-table"></i></a>
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
                <th>Nombres</th>
                <th>Correo Electr&oacute;nico</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if(count($users)>0)
             <!--{{$i=0}}-->
                @foreach($users as $user)
                 <tr class="table-flag-blue">
                    <td>{{++$i}}</td>
                    <td>{{$user->first_name.' '.$user->last_name}}</td>
                    <td>{{$user->email}}</td>
                    <td><a href="{{url('users/edit/'.$user->id)}}"><i class="fa  fa-pencil-square-o">&nbsp;</i></a><a href="{{url('users/delete/'.$user->id)}}" onclick='return confirm("Desea eliminar este registro?")'><i class="fa fa-trash-o">&nbsp;</i></a></td>
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