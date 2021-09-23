<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
ini_set('memory_limit', '-1');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function() {
	if(!Auth::user()){
		return redirect('auth/login');
	}else{
		return redirect('home');
	}
});

Route::get("logout", function() {
	Auth::logout();
	return redirect('/');
});

Route::get('/auth', [App\Http\Controllers\Auth\LoginController::class]);
Route::post('/auth', [App\Http\Controllers\Auth\LoginController::class]);

Route::get('/home', [App\Http\Controllers\UserController::class, 'dashboard']);

Route::get('/users/list', [App\Http\Controllers\UserController::class, 'listUsers']);
Route::get('/users/add', [App\Http\Controllers\UserController::class, 'createUser']);
Route::post('/users/add', [App\Http\Controllers\UserController::class, 'createUser']);
Route::match(['get', 'post'], '/users/edit/{id}', [App\Http\Controllers\UserController::class, 'editUser']);
Route::get('/users/delete/{id}', [App\Http\Controllers\UserController::class, 'deleteUser']);

Route::get('/campaigns/list', [App\Http\Controllers\CampaignsController::class, 'listCampaigns']);
Route::match(['get', 'post'], '/campaigns/add', [App\Http\Controllers\CampaignsController::class, 'createCampaign']);
Route::match(['get', 'post'], '/campaigns/edit/{id}', [App\Http\Controllers\CampaignsController::class, 'editCampaign']);
Route::get('/campaigns/delete/{id}', [App\Http\Controllers\CampaignsController::class, 'deleteCampaign']);
Route::get('/campaigns/download/{id}', [App\Http\Controllers\CampaignsController::class, 'downloadCampaign']);

Route::get('/blocks/list/{id}', [App\Http\Controllers\BlocksController::class, 'listBlocks']);
Route::match(['get', 'post'], '/blocks/add', [App\Http\Controllers\BlocksController::class, 'addBlock']);
Route::match(['get', 'post'], '/blocks/edit/{id}', [App\Http\Controllers\BlocksController::class, 'editBlock']);
Route::get('/blocks/delete/{id}', [App\Http\Controllers\BlocksController::class, 'deleteBlock']);
Route::get('/view/{id}', [App\Http\Controllers\CampaignsController::class, 'viewCampaign']);

Route::get('/campaigns/copy/{id}', [App\Http\Controllers\CampaignsController::class, 'copyCampaign']);

Route::match(['get', 'post'], '/campaigns/users/{id}', [App\Http\Controllers\CampaignsController::class, 'users']);

Route::get('/campaigns/users/delete/{id}', [App\Http\Controllers\CampaignsController::class, 'deleteUser']);

Route::get('/send/{id}', [App\Http\Controllers\CampaignsController::class, 'sendMail']);
