<?php
namespace App\Http\Controllers;

use App\Models\User;
use Symfony\Component\HttpFoundation\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class UserController extends Controller
{
	public function dashboard(){
		return view('app',array('title'=>'Bienvenidos','message'=>"Bienvenido ". Auth::user()->first_name));
	}

	public function listUsers(){
		$users = User::all();
		return view('users/list',array('title'=>'Listado de Usuarios','users'=>$users,'message'=>'Mira los usuarios existentes y administralos','class'=>'users'));
	}

	public function createUser(Request $request){
		if($request->has('_token')){
			try{
				$user = $request->all();
				$session = new Session();
				$user['password']=bcrypt($user['password']);
				unset($user['confirm-password']);
				$user_locale = User::where('email','=',$user['email'])->select('id')->first();
				if(isset($user_locale->id)&&$user_locale->id>0){
					Session::flash('message',"El correo electr&oacute;nico ya no est&aacute; disponible por que ha sido ya utilizado.");
					Session::flash('code',"400");
				}else{
					User::create($user);
					Session::flash('message',"El usuario se ha ingresado exitosamente");
					Session::flash('code','200');
				}
			}catch(Throwable $e){
				Session::flash('message',"Se ha presentado un error por favor comun&iacute;quese con el administrador");
				Session::flash('code',"400");
			}
		}
		$user = new User();
		$user->first_name='';
		$user->last_name='';
		$user->email='';

		return view('users/form',array('title'=>'Crear Usuario','action'=>url('users/add'),'message'=>'Crea un nuevo usuario para la aplicaci&oacute;n','user'=>$user,'class'=>'users'));
	}

	public function editUser($id, Request $request){
		$user = User::find($id);
		if($request->has('_token')){
			try{
				$userRequest = $request->all();
				unset($userRequest['confirm-password']);
				unset($userRequest['_token']);
				$session = new Session();
				foreach($userRequest as $row=>$value){
					if($row=="password"){
						$user->password = bcrypt($value);
					}else{
						$user->$row = $value;
					}
				}
				$user->save();
				Session::flash('message',"El usuario se ha editado exitosamente");
				Session::flash('code','200');
			}catch(Throwable $e){
				Session::flash('message',"Se ha presentado un error por favor comun&iacute;quese con el administrador");
				Session::flash('code',"400");
			}
		}
		return view('users/form',array('title'=>'Editar Usuario: '.$user->first_name.' '.$user->last_name,'action'=>url('users/edit/'.$user->id),'user'=>$user,'message'=>'Edita un usuario de la aplicaci&oacute;n'));
	}

	public function deleteUser($id){
		$user = User::find($id);
		$user->delete();
		return redirect('users/list');
	}
}
