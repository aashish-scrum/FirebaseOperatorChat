<?php

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

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/chats', [HomeController::class, 'chat'])->name('chat');
    Route::get('/settings', [HomeController::class, 'settings'])->name('settings');
    Route::get('/department/add', [VisitorController::class, 'visitors'])->name('department.add');
    Route::post('/invite-members', [HomeController::class, 'invite_members'])->name('invite-members');
    
    Route::get('/chat/visitors', [VisitorController::class, 'visitors'])->name('visitors');
    Route::get('/chat/operator/status/{operator_id}/{status}', [HomeController::class, 'operator_status'])->name('visitors');
    Route::post('/visitor/chat-end',[HomeController::class, 'chat_end']);
});
