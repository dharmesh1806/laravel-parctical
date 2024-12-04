<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckUserAuthentication;


Route::get('/', function () {
    return view('register');
});

Route::get('/login', function () {
    return view('login');
});
Route::get('/role', function () {
    return view('role');
});
Route::get('/users', function () {
    return view('users');
});

Route::post('/register', [UserController::class, 'register']);
Route::get('/roles', [UserController::class, 'roleListForRegister']);
Route::post('/user-login', [UserController::class, 'login']);
Route::get('/role-list', [UserController::class, 'roleList'])->middleware(CheckUserAuthentication::class);
Route::delete('/delete-role', [UserController::class, 'deleteRole'])->middleware(CheckUserAuthentication::class);
Route::post('/add-role', [UserController::class, 'addRole'])->middleware(CheckUserAuthentication::class);
Route::put('edit-role', [UserController::class, 'editRole'])->middleware(CheckUserAuthentication::class);
Route::get('user-list', [UserController::class, 'userList'])->middleware(CheckUserAuthentication::class);
Route::delete('delete-user', [UserController::class, 'deleteUser'])->middleware(CheckUserAuthentication::class);
Route::get('user-data', [UserController::class, 'userData'])->middleware(CheckUserAuthentication::class);
