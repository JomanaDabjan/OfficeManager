<?php

use App\Http\Controllers\admin\WebsiteController;
use Illuminate\Support\Facades\Route;

/*Route::get('/', function () {
    return view('welcome');
});*/

/* Start Website Controller */

Route::resource('/', WebsiteController::class)->names([
    'index' => '/'
]);
Route::get('/project', [WebsiteController::class, 'project'])->name('project');
Route::get('/task', [WebsiteController::class, 'task'])->name('task');
Route::get('/team', [WebsiteController::class, 'team'])->name('team');
Route::get('/user', [WebsiteController::class, 'user'])->name('user');
Route::get('/prostatus', [WebsiteController::class, 'projectstatus'])->name('prostatus');
Route::get('/report', [WebsiteController::class, 'report'])->name('report');

/* End Website Controller */
