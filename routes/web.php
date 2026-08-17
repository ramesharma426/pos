<?php

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

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/{vue_capture?}', function (?string $vue_capture = null) {
    // Missing build/storage assets must 404 (not fall through to the SPA shell),
    // otherwise a stale/renamed chunk request returns index.html as text/html and
    // the browser rejects it: "Expected a JavaScript module but got text/html".
    if ($vue_capture !== null && (str_starts_with($vue_capture, 'build/') || str_starts_with($vue_capture, 'storage/'))) {
        abort(404);
    }
    return view('index');
})->where('vue_capture', '[\/\w\.-]*');
