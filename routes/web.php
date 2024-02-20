<?php

use App\Http\Controllers\app;
use App\Http\Controllers\auth\authenticacao;
use App\Http\Controllers\browse;
use App\Http\Controllers\contacts;
use App\Http\Controllers\editor;
use App\Http\Controllers\encomendas;
use App\Http\Controllers\pacotes;
use App\Http\Controllers\producao\producao;
use App\Http\Controllers\templates;
use App\Http\Controllers\user\data;
use App\Http\Controllers\silica\silica;
use App\Http\Controllers\silica\slogin;
use Sienekib\Mehael\Router\Anotation\Route;

Route::post('/api/create', [templates::class, 'store']);
Route::post('/salvando', [templates::class, 'store']);

Route::get('/', [app::class, 'index']);

Route::get('/entrar', [authenticacao::class, 'login']);
Route::post('/entrar', [authenticacao::class, 'autenticar']);

/*
Silica auth
 */
Route::post('/authenticate', [slogin::class, 'index']);

//Route::post('/autenticar', [authenticacao::class, 'autenticar']);
//Route::get('/entrar', [authenticacao::class, 'login']);

Route::get('/register', [authenticacao::class, 'register']);
Route::post('/registe', [authenticacao::class, 'user_create']);
Route::get('/logout', [authenticacao::class, 'destroy']);


Route::get('/nocode', [app::class, 'index']);
Route::get('/user/[0-9]+', [app::class, 'update']);

Route::get('/silica/(dominio:any)', [producao::class, 'index']);

Route::get('/templates', [templates::class, 'index']);
Route::get('/contactos', [contacts::class, 'index']);
//Route::get('/api/template', [templates::class, 'upload_template']);

//Route::get('/editor/(uuid:any)', [editor::class, 'open_template']);

Route::get('/browse', [browse::class, 'load']);
//Route::post('/browse', [browse::class, 'generate']);
Route::get('/browse/(id:numeric)', [browse::class, 'load_specfic']);
// Authorized routes
Route::group('auth:authorize', function () {
    Route::get('/dados/(id:numeric)', [data::class, 'carregar']);
    Route::get('/planos', [pacotes::class, 'index']);
    Route::get('/aderir/(id:numeric)', [pacotes::class, 'aderir']);
    Route::post('/adesao', [pacotes::class, 'adesao_planos']);
    Route::get('/encomenda', [encomendas::class, 'index']);
    Route::get('/editor/(dominio:alpha)/(uuid:any)', [editor::class, 'open_template']);
    Route::get('/edit/(dominio:alpha)/(uuid:any)', [editor::class, 'open_template_edit']);

    Route::get('/web_builder', [editor::class, 'web_builder']);
    Route::get('/preview/(template:any)', [templates::class, 'preview']);
    Route::get('/usar/(uuid:any)', [data::class, 'choose']);
    Route::post('/contactar', [contacts::class, 'store']);
    Route::post('/salvar', [data::class, 'save_template']);
    Route::post('/salvar_edit', [data::class, 'save_template_edit']);
});

// Sílica
Route::prefix('user')->group('auth:authorize', function () {
    Route::get('/(id:any)/home', [silica::class, 'index']);
    Route::get('/(id:any)/websites', [silica::class, 'websites']);
    Route::get('/(id:any)/encomendas', [silica::class, 'demandas']);
    Route::get('/(id:any)/campanhas', [silica::class, 'campanhas']);
    Route::get('/(id:any)/campanhas/mail', [silica::class, 'campanhas_mail']);
});

/*Route::post('/usar', [data::class, 'validar_uso']);*/
Route::get('/meus-templates', [templates::class, 'temp_usuario']);
