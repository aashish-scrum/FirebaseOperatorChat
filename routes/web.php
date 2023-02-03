<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VisitorController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

require __DIR__ . '/auth.php';
Route::get('/widget-load',function(){
    $propertyKey = '63b7f7c3c2f1ac1e202c06a4';
    $widgetId = '1gm39vli1';
    return response()->view('widget-loader',compact('propertyKey','widgetId'))->header('Content-Type', 'application/javascript');
});
Route::middleware(['auth','company'])->group(function () {
    Route::get('/dashboard/{company_id?}', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/chats/{company_id?}', [HomeController::class, 'chat'])->name('chat');
    Route::get('/settings/{company_id?}', [HomeController::class, 'settings'])->name('settings');
    Route::get('/invite-members/{company_id?}/index', [DepartmentController::class, 'members_create'])->name('invite.members.index');
    Route::post('/invite-members/{company_id?}', [DepartmentController::class, 'invite_members'])->name('invite.members');
    Route::get('/department/{company_id?}/add', [DepartmentController::class, 'index'])->name('department.add');
    Route::post('/department/{company_id?}/create', [DepartmentController::class, 'create'])->name('department.create');
    Route::get('/department/{company_id?}/view/{id}', [DepartmentController::class, 'edit'])->name('department.view');
    Route::get('/department/{company_id?}/destroy/{id}', [DepartmentController::class, 'destroy'])->name('department.delete');
});
Route::get('/chat/visitors', [VisitorController::class, 'visitors'])->name('visitors');
Route::get('/chat/operator/status/{operator_id}/{status}', [HomeController::class, 'operator_status'])->name('visitors');
Route::post('/visitor/chat-end', [HomeController::class, 'chat_end']);

Route::get('/company/create', [CompanyController::class, 'create'])->middleware('auth')->name('company.create');
Route::post('/company/save', [CompanyController::class, 'save'])->middleware('auth')->name('company.save');

Route::get('/member-verify', [CompanyController::class,'verify'])->name('verify.email');