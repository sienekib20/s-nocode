<?php

namespace app\controllers;

use core\database\Database;
use Exception;

class Templates
{

  public function load_templates()
  {
    $templates = Database::select('select t.template_id, t.titulo, t.autor, (select file from files where file_id = t.file_id) as file, (select tipo_template from tipo_templates where tipo_template_id = t.tipo_template_id) as tipo_template, t.referencia from templates as t');

    var_dump($templates);
    exit;

    return view('app.explorar', compact('templates'));
  }
  public function upload_template()
  {
    try {

      if (request()->methodIs('POST')) {

        $request = (object) request()->all();

        $files = request()->file();

        $reference = Struct::create_structure($request->temp_title, $files);

        if ($reference) {
          $capa = pathinfo($files['temp_cover']['name']);
          $capa_temp = $capa['filename'];
          $capa_ext = $capa['extension'];

          $capa_template_name = time() . md5($capa_temp) . ".$capa_ext";

          upload($files['temp_cover']['tmp_name'], storage_path() . '/img/templates_covers/' . $capa_template_name);

          $file_id = Database::table('files')->insert(['file' => $capa_template_name])->lastId();

          $preco = Presets::formatNumber($request->temp_price ?? '0', 2);

          Database::table('templates')->insert([
            'titulo' => $request->temp_title, 'tipo_template_id' => database()->select('select tipo_template_id from tipo_templates where tipo_template = ?', [$request->temp_type])[0]->tipo_template_id, 'referencia' => $reference, 'file_id' => $file_id, 'descricao' => $request->temp_description, 'autor' => 'Sílica', 'editar' => $request->temp_editable, 'estado_pagamento' => $request->temp_payment_status, 'preco' => $preco
          ]);

          response()->setHttpResponseCode(200);
          return header('Location: /');
        }
        throw new Exception("Erro ao cria um novo template", 1);
      }
    } catch (Exception $ex) {

      repport('Erro de armazenamento', $ex, 500);
      
      
    }
  }
}
