<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;

use Illuminate\Support\Facades\DB;
use App\Models\FailedJob;

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

Route::post('/send-email', [EmailController::class, 'sendEmailNotification']);
Route::get('/send-email', [EmailController::class, 'showForm']);

Route::get('/send-email', function () {
  
    $failedJobs = DB::table('failed_jobs')->orderBy('failed_at', 'desc')->get();
    $jobs = DB::table('jobs')->orderBy('id', 'desc')->get();
    
    return view('send-email', ['failedJobs' => $failedJobs,'jobs'=>$jobs]);
});


Route::post('/retry-job/{id}', [EmailController::class, 'retryJob'])->name('retry-job');
Route::post('/queue/work', [EmailController::class, 'work'])->name('queue.work');




