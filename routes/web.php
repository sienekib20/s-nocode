<?php

use App\Http\Controllers\app;
use App\Http\Controllers\auth\authenticacao;
use App\Http\Controllers\browse;
use App\Http\Controllers\editor;
use App\Http\Controllers\templates;
use App\Http\Controllers\user\data;
use Sienekib\Mehael\Router\Anotation\Route;

Route::add('POST', '/api/create', [templates::class, 'store']);
Route::add('POST', '/salvando', [templates::class, 'store']);

Route::add('GET', '/', [app::class, 'index']);
Route::add('GET', '/entrar', [authenticacao::class, 'login']);
Route::add('GET', '/nocode', [app::class, 'index']);
Route::add('GET', '/user/[0-9]+', [app::class, 'update']);

Route::add('GET', '/templates', [templates::class, 'index']);
//Route::add('GET', '/api/template', [templates::class, 'upload_template']);

//Route::add('GET', '/editor/(uuid:any)', [editor::class, 'open_template']);
Route::add('GET', '/editor/(dominio:alpha)/(uuid:any)', [editor::class, 'open_template']);
Route::add('GET', '/web_builder', [editor::class, 'web_builder']);

Route::add('GET', '/browse', [browse::class, 'load']);
Route::add('GET', '/browse/(id:numeric)', [browse::class, 'load_specfic']);
// Authorized routes
Route::group('auth:authorize', function () {
    Route::add('GET', '/dados/(user:alpha)', [data::class, 'carregar']);
});

Route::add('GET', '/usar/(uuid:any)', [data::class, 'choose']);
Route::add('POST', '/usar', [data::class, 'validar_uso']);
Route::add('POST', '/salvar', [data::class, 'save_template']);
Route::add('GET', '/meus-templates', [templates::class, 'temp_usuario']);
Route::add('GET', '/preview/(template:any)', [templates::class, 'preview']);
