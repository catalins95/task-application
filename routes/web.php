<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/create_task', [TaskController::class, 'index_create']);

Route::middleware(['auth'])->group(function() {
    Route::resource('tasks', 'TaskController', [
        'only' => [
            'update' , 'index', 'store'
        ]
    ]);
    Route::delete('tasks/{task}', [TaskController::class, 'delete'])->name('delete_task');
    Route::post('tasks/{task}', [TaskController::class, 'update_unmark'])->name('unmark_task');
    Route::get('view_task/{task}', [TaskController::class, 'view'])->name('view_task');
    Route::post('edit_task/{task}', [TaskController::class, 'edit'])->name('edit_task');

});
