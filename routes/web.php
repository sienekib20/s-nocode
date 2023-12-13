<?php

use App\Http\Controllers\app;
use App\Http\Controllers\editor;
use App\Http\Controllers\templates;
use Sienekib\Mehael\Router\Anotation\Route;

Route::add('GET', '/', [app::class, 'index']);
Route::add('GET', '/login', [app::class, 'index']);
Route::add('GET', '/nocode', [app::class, 'index']);
Route::add('GET', '/user/[0-9]+', [app::class, 'update']);

Route::add('GET', '/templates', [templates::class, 'index']);
Route::add('GET', '/api/template', [Templates::class, 'upload_template']);

Route::add('GET', '/editor/[0-9]+', [Templates::class, 'open_editor']);

Route::add('GET', '/editor/[0-9]+', [Editor::class, 'open_template']);

Route::add('GET', '/web_builder', [editor::class, 'web_builder']);
