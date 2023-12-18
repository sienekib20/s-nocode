<?php

use App\Http\Controllers\app;
use App\Http\Controllers\editor;
use App\Http\Controllers\templates;
use Sienekib\Mehael\Router\Anotation\Route;

//Route::api('api:name')->add('GET', '/', [templates::class, 'store']);
Route::add('POST', '/create', [templates::class, 'store']);
