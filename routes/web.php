<?php

use App\Http\Controllers\Front\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::post('/broadcasting/auth', function (Request $request) {
    $key = config('broadcasting.connections.pusher.key');
    $secret = config('broadcasting.connections.pusher.secret');

    $socketId = $request->input('socket_id');
    $channelName = $request->input('channel_name');

    $stringToSign = $socketId . ':' . $channelName;
    $signature = hash_hmac('sha256', $stringToSign, $secret);

    return response()->json([
        'auth' => $key . ':' . $signature,
    ]);
});


Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard/{page?}', [DashboardController::class, 'index'])->name('dashboard');