<?php

// Permita o acesso a partir de qualquer origem (não recomendado para produção)

namespace app\controllers;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

use core\database\Database;
use core\support\Presets;
use core\support\Struct;
use Exception;

class Templates
{

    public function load_templates()
    {
        //$templates = [];
        $templates = Database::select('select t.template_id, t.titulo, t.autor, (select file from files where file_id = t.file_id) as file, (select tipo_template from tipo_templates where tipo_template_id = t.tipo_template_id) as tipo_template, t.referencia from templates as t');

        return view('app.explorar', compact('templates'));
    }
    public function upload_template()
    {
        try {

            if (request()->methodIs('POST')) {
                $json = file_get_contents('php://input');
                $data = json_decode($json, true);

                if ($data === null) {
                    http_response_code(400);
                    encode(['mensagem' => 'Erro1 na decodificação JSON']);
                } else {

                    $requestFiles = (object) $data['dataForm'];
                    $structure = Struct::create_structure($requestFiles->temp_title);

                    $cover = $data['cover'];
                    $coverFileName = '';

                    $file = explode('base64', $cover[0]['file'])[1];
                    $file = base64_decode($file);

                    $uploadCover = 'storage/images/' . $cover[0]['name'];
                    $coverFileName = $cover[0]['name'];



                    file_put_contents($uploadCover, $file);

                    //  encode();

                    $rootFolder = str_replace('pages', '', rtrim($structure['views/pages'], '/'));


                    $images = $data['images'];
                    $assets = $data['assets'];
                    $pages = $data['pages'];


                    foreach ($images as $index => $image) {
                        $file = explode('base64', $image['file'])[1];
                        $file = base64_decode($file);
                        $uploadFile = $structure['app/assets/img'] . $image['name'];

                        file_put_contents($uploadFile, $file);
                    }


                    foreach ($assets as $index => $asset) {
                        $file = explode('base64', $asset['file'])[1];
                        $file = base64_decode($file);

                        $extAsset = explode('.', $asset['name']);
                        $extAsset = end($extAsset);

                        $folder =  ($extAsset == 'css') ? $structure['app/assets/css'] : $structure['app/assets/js'];

                        $uploadFile = $folder . $asset['name'];

                        file_put_contents($uploadFile, $file);
                    }

                    foreach ($pages as $index => $page) {
                        $file = explode('base64', $page['file'])[1];
                        $file = base64_decode($file);

                        $namePage = explode('.', $page['name'])[0];
                        $folder =  ($namePage == 'index') ? $rootFolder : $structure['views/pages'];

                        $uploadFile = $folder . $page['name'];

                        file_put_contents($uploadFile, $file);
                    }

                    $file_id = Database::table('files')->insert(['file' => $coverFileName])->lastId();

                    $preco = Presets::formatNumber($requestFiles->temp_price ?? '0', 2);

                    Database::table('templates')->insert([
                        'titulo' => $requestFiles->temp_title,
                        'tipo_template_id' => database()->select('select tipo_template_id from tipo_templates where tipo_template = ?', [$requestFiles->temp_type])[0]->tipo_template_id,
                        'referencia' => $structure['reference'],
                        'file_id' => $file_id,
                        'descricao' => $requestFiles->temp_description,
                        'autor' => 'Sílica',
                        'editar' => $requestFiles->temp_editable,
                        'estado_pagamento' => $requestFiles->temp_payment_status,
                        'preco' => $preco
                    ]);

                    //http_response_code(200);
                    encode($structure['reference']);
                }
            }
        } catch (Exception $ex) {

            repport('Erro de armazenamento', $ex, 500);
        }
    }
}
