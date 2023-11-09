<?php

// Permita o acesso a partir de qualquer origem (não recomendado para produção)

namespace app\controllers;

use core\database\Database;

class Editor
{
    public function open_template($template)
    {
        $reference = Database::select('select referencia from templates where template_id = ?', [$template->id])[0];
        $to = [
            'url' => url('localhost', 7000) . 'nocode/' . $reference->referencia
        ];
        //var_dump($reference->referencia);exit;
        //header('Location: /nocode/' . $reference->referencia);
        return view('app.editor', compact('to'));
    }
}
