<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VisitorController;
use App\Models\Company;
use Illuminate\Support\Facades\Request;
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

Route::middleware(['auth','company'])->group(function () {
    Route::get('/dashboard/{company_id?}', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/chats/{company_id?}', [HomeController::class, 'chat'])->name('chat');
    Route::get('/settings/{company_id?}', [HomeController::class, 'settings'])->name('settings');
    Route::get('/department/{company_id?}/create', [DepartmentController::class, 'create'])->name('department.add');
    Route::post('/invite-members/{company_id?}', [DepartmentController::class, 'invite_members'])->name('invite.members');
});
Route::get('/chat/visitors', [VisitorController::class, 'visitors'])->name('visitors');
Route::get('/chat/operator/status/{operator_id}/{status}', [HomeController::class, 'operator_status'])->name('visitors');
Route::post('/visitor/chat-end', [HomeController::class, 'chat_end']);

Route::get('/company/create', [CompanyController::class, 'create'])->middleware('auth')->name('company.create');
Route::post('/company/save', [CompanyController::class, 'save'])->middleware('auth')->name('company.save');

Route::get('/member-verify', [CompanyController::class,'verify'])->name('verify.email');