<?php

use App\Http\Controllers\app;
use App\Http\Controllers\auth\authenticacao;
use App\Http\Controllers\browse;
use App\Http\Controllers\contacts;
use App\Http\Controllers\editor;
use App\Http\Controllers\encomendas;
use App\Http\Controllers\faqs;
use App\Http\Controllers\pacotes;
use App\Http\Controllers\producao\producao;
use App\Http\Controllers\templates;
use App\Http\Controllers\user\data;
use App\Http\Controllers\silica\silica;
use App\Http\Controllers\silica\slogin;
use Sienekib\Mehael\Router\Anotation\Route;

Route::get('/salvando/{id}', [templates::class, 'store']);
Route::post('/api/create', [templates::class, 'store']);

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

Route::get('/silica/{dominio}', [producao::class, 'index']);

Route::get('/templates', [templates::class, 'index']);
Route::get('/contactos', [contacts::class, 'index']);
//Route::get('/api/template', [templates::class, 'upload_template']);

//Route::get('/editor/{uuid}', [editor::class, 'open_template']);

Route::get('/browse', [browse::class, 'load']);
Route::get('/browse/categorias/todas', [browse::class, 'load']);
Route::get('/browse/find/{categoria}', [browse::class, 'load_specific']);

Route::get('/faqs', [faqs::class, 'index']);
Route::post('/faqs-get', [faqs::class, 'auto_fill']);
Route::post('/purpose', [faqs::class, 'duvida']);

//Route::post('/browse', [browse::class, 'generate']);
Route::get('/browse/{id}', [browse::class, 'load_specfic']);
Route::post('/browse-get', [browse::class, 'get_browse']);
Route::post('/browse-get-id', [browse::class, 'get_browse_id']);
Route::post('/browse-get-id_2', [browse::class, 'get_browse_id_2']);

// Authorized routes
Route::group('auth:authorize', function () {
    Route::get('/dados/{id}', [data::class, 'carregar']);
    Route::get('/planos', [pacotes::class, 'index']);
    Route::get('/aderir/{id}', [pacotes::class, 'aderir']);
    Route::post('/adesao', [pacotes::class, 'adesao_planos']);
    Route::get('/encomenda', [encomendas::class, 'index']);

    Route::get('/editor/{dominio}/{uuid}', [editor::class, 'open_template']);
    //Route::get('/editor/{uuid}', [editor::class, 'open_template']);
    Route::get('/edit/{dominio}/{uuid}', [editor::class, 'open_template_edit']);

    Route::get('/web_builder', [editor::class, 'web_builder']);
    Route::get('/live/{template}', [templates::class, 'preview']);
    Route::post('/shot', [templates::class, 'get_shot']);
    Route::get('/live/shot', [templates::class, 'get_shot']);

    Route::get('/view/{uuid}', [data::class, 'choose']);

    Route::post('/contactar', [contacts::class, 'store']);
    Route::post('/salvar', [data::class, 'save_template']);
    Route::post('/salvar_edit', [data::class, 'save_template_edit']);
    Route::post('/remove_template', [data::class, 'remove_saved_template']);

    Route::post('/save/delivered', [editor::class, 'save_delivered']);
});

Route::prefix('site')->group('auth:authorize', function () {
    // Editor
    Route::get('/intro', [editor::class, 'create']);
    Route::get('/blank', [editor::class, 'blank']);
});


Route::prefix('favoritos')->group('auth:authorize', function () {
    // Editor
    Route::post('/add', [templates::class, 'set_favoritos']);
});

Route::prefix('user')->group('auth:authorize', function () {
    Route::get('/{id}/view', [silica::class, 'index']);
    Route::get('/{id}/websites', [silica::class, 'websites']);
    Route::get('/{id}/encomendas', [silica::class, 'demandas']);
    Route::get('/{id}/campanhas', [silica::class, 'campanhas']);
    Route::get('/{id}/campanhas/mail', [silica::class, 'campanhas_mail']);
    Route::get('/{id}/notificao', [silica::class, 'notificao']);
    Route::post('/send_demand', [silica::class, 'enviar_demanda']);
});


// Carregar template
Route::get('/my/{name}', [producao::class, 'load']);






Route::post('/userId', [silica::class, 'get_user_uuid']);
Route::post('/introMail', [contacts::class, 'intro_mail']);
Route::post('/ownMail', [contacts::class, 'own_mail']);

/*Route::post('/usar', [data::class, 'validar_uso']);*/
Route::get('/meus-templates', [templates::class, 'temp_usuario']);
