<?php

use App\Http\Controllers\app;
use App\Http\Controllers\auth\authenticacao;
use App\Http\Controllers\editor;
use App\Http\Controllers\templates;
use Sienekib\Mehael\Router\Anotation\Route;

Route::add('POST', '/api/create', [templates::class, 'store']);

Route::add('GET', '/', [app::class, 'index']);
Route::add('GET', '/login', [authenticacao::class, 'login']);
Route::add('GET', '/nocode', [app::class, 'index']);
Route::add('GET', '/user/[0-9]+', [app::class, 'update']);

Route::add('GET', '/templates', [templates::class, 'index']);
Route::add('GET', '/api/template', [Templates::class, 'upload_template']);


Route::add('GET', '/editor/(any:uuid)', [editor::class, 'open_template']);
Route::add('GET', '/web_builder', [editor::class, 'web_builder']);

// Authorized routes

Route::group('auth:authorize', function() {
});

Route::add('GET', '/meus-templates', [templates::class, 'temp_usuario']);
Route::add('GET', '/preview/(any:template)', [templates::class, 'preview']);