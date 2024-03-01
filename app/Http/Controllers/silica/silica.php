<?php

namespace App\Http\Controllers\silica;

use App\Http\Controllers\Controller;
use Sienekib\Mehael\Http\Request;
use Sienekib\Mehael\Database\Factory\DB;
use Sienekib\Mehael\Support\Auth;

class silica extends Controller
{
    public function index(Request $request)
    {
        $id = Auth::user()->id;

        $templateUsuario = DB::table('temp_parceiros')
            ->select('count(template_id) as total')
            ->where('parceiro_id', '=', $id)->get()[0];

        $subscricoes = 0;
        $encomendas = 0;

        return view('Home:site.dashboard.index', compact('templateUsuario', 'subscricoes', 'encomendas'));
    }

    public function get_user_uuid(Request $request)
    {
        $data = DB::table('contas')
            ->where('conta_id', '=', $request->id)
            ->select('uuid')
            ->get()[0];

        return response()->json($data);
    }

    public function websites()
    {
        $id = Auth::user()->id;

        $data = DB::raw('select tp.dominio, tp.created_at, (select titulo from templates where template_id = tp.template_id) as titulo, (select uuid from templates where template_id = tp.template_id) as template_uuid, (select status from templates where template_id = tp.template_id) as status, (select preco from templates where template_id = tp.template_id) as preco, (select if(status="Grátis","30 dias", "90 dias") from templates where template_id = tp.template_id) as prazo, (select conta_id from contas where conta_id = ?) from temp_parceiros as tp where parceiro_id = ?', [$id, $id]);

        $templateUsuario = DB::table('temp_parceiros')->select('count(template_id) as total')->where('parceiro_id', '=', $id)->get()[0];

        $subscricoes = 0;
        $encomendas = 0;

        //return view('Home:app.site.index', compact('data'));
        //return view('Meus dados:site.user-data', compact('data', 'templateUsuario', 'subscricoes', 'encomendas'))
        return view('Websites:site.dashboard.websites', compact('data'));
    }

    public function campanhas()
    {
        return view('Campanhas:site.silica.campanhas');
    }

    public function campanhas_mail()
    {
        return view('Mail:site.silica.campanhas_mail');
    }

    public function demandas()
    {
        $categorias = DB::table('tipo_templates')->get();

        return view('Demandas:site.silica.demandas', compact('categorias'));
    }
}
