<?php

use App\Http\Controllers\LinkController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

Route::post('/shorten', [LinkController::class, 'shorten']);



Route::get('/horarios', function (Request $request) {
    $date = $request->query('date');

    $response = Http::withToken('f62cfa835ccb11f6ae940b09cef84a54cb353b8989f79cfab9e0a846997a575e')
        ->get('https://teste.xl4y3r.com/api/available-times', [
            'date' => $date
        ]);

    return response()->json($response->json(), $response->status());
});

Route::post('/agendamentos', function (Request $request) {
    $payload = $request->all();

    $response = Http::withToken('f62cfa835ccb11f6ae940b09cef84a54cb353b8989f79cfab9e0a846997a575e')
        ->post('https://teste.xl4y3r.com/api/appointments', $payload);

    return response()->json($response->json(), $response->status());
});