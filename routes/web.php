<?php

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('/chats', [App\Http\Controllers\HomeController::class, 'chat'])->name('chat');
Route::get('/chat/visitors', [App\Http\Controllers\VisitorController::class, 'visitors'])->name('visitors');
Route::get('/chat/operator/status/{operator_id}/{status}', [App\Http\Controllers\HomeController::class, 'operator_status'])->name('visitors');
Route::post('/visitor/chat-end',[App\Http\Controllers\HomeController::class, 'chat_end']);