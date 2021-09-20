<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
ini_set('memory_limit', '-1');
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function(){
	if(!Auth::user()){
		return redirect('auth/login');
	}else{
		return redirect('home');
	}
});
Route::get("logout",function(){
	Auth::logout();
	return redirect('/');
});
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\ResetPassword',
]);

Route::get('home',array('middleware' => 'auth','uses'=>"UserController@dashboard"));

Route::get('users/list',array('middleware' => 'auth','uses'=>'UserController@listUsers'));
Route::get('users/add',array('middleware' => 'auth','uses'=>'UserController@createUser'));
Route::post('users/add',array('middleware' => 'auth','uses'=>'UserController@createUser'));
Route::match(['get', 'post'],'users/edit/{id}',array('middleware' => 'auth','uses'=>'UserController@editUser'));
Route::get('users/delete/{id}',array('middleware' => 'auth','uses'=>'UserController@deleteUser'));

Route::get('campaigns/list',array('middleware' => 'auth','uses'=>'CampaignsController@listCampaigns'));
Route::match(['get','post'],'campaigns/add',array('middleware' => 'auth','uses'=>'CampaignsController@createCampaign'));
Route::match(['get','post'],'campaigns/edit/{id}',array('middleware' => 'auth','uses'=>'CampaignsController@editCampaign'));
Route::get('campaigns/delete/{id}',array('middleware' => 'auth','uses'=>'CampaignsController@deleteCampaign'));
Route::get('campaigns/download/{id}',array('middleware' => 'auth','uses'=>'CampaignsController@downloadCampaign'));

Route::get('blocks/list/{id}',array('middleware' => 'auth','uses'=>'BlocksController@listBlocks'));
Route::match(['get','post'],'blocks/add',array('middleware' => 'auth','uses'=>'BlocksController@addBlock'));
Route::match(['get','post'],'blocks/edit/{id}',array('middleware' => 'auth','uses'=>'BlocksController@editBlock'));
Route::get('blocks/delete/{id}',array('middleware' => 'auth','uses'=>'BlocksController@deleteBlock'));
Route::get('view/{id}','CampaignsController@viewCampaign');

Route::get('campaigns/copy/{id}',array('middleware'=>'auth','uses'=>'CampaignsController@copyCampaign'));

Route::match(['get','post'],'campaigns/users/{id}',array('middleware'=>'auth','uses'=>'CampaignsController@users'));

Route::get('campaigns/users/delete/{id}',array('middleware'=>'auth','uses'=>'CampaignsController@deleteUser'));

Route::get('send/{id}',['middleware'=>'auth','uses'=>'CampaignsController@sendMail']);