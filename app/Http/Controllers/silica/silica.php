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

        $leads = DB::table('leads')
            ->select('count(lead_id) as total')
            ->where('conta_id', '=', $id)->get()[0];

        $subscricoes = 0;
        $encomendas = DB::table('pedidos_clientes')
            ->select('count(pedidos_cliente_id) as total')
            ->where('expediente', '=', $id)->get()[0];

        return view('Home:site.dashboard.index', compact('templateUsuario', 'leads', 'encomendas'));
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

        $temp_parc = DB::raw('select tp.temp_parceiro_id, tp.dominio, tp.created_at, (select titulo from templates where template_id = tp.template_id) as titulo, (select referencia from templates where template_id = tp.template_id) as referencia, (select uuid from templates where template_id = tp.template_id) as template_uuid, (select status from templates where template_id = tp.template_id) as status, (select preco from templates where template_id = tp.template_id) as preco, (select if(status="Grátis","30 dias", "90 dias") from templates where template_id = tp.template_id) as prazo, (select conta_id from contas where conta_id = ?) as account_id from temp_parceiros as tp where parceiro_id = ?', [$id, $id]);

        $templateUsuario = DB::table('temp_parceiros')->select('count(template_id) as total')->where('parceiro_id', '=', $id)->get()[0];

        $subscricoes = 0;
        $encomendas = 0;

        //return view('Home:app.site.index', compact('data'));
        //return view('Meus dados:site.user-data', compact('data', 'templateUsuario', 'subscricoes', 'encomendas'))
        return view('Websites:site.dashboard.websites', compact('temp_parc'));
    }

    public function campanhas()
    {
        $campanhas = DB::table('leads')->where('conta_id', '=', Auth::user()->id)->get();

        return view('Campanhas:site.dashboard.campanhas', compact('campanhas'));
    }


    public function campanhas_mail()
    {
        return view('Mail:site.silica.campanhas_mail');
    }

    public function demandas()
    {
        $tipo = DB::table('tipo_templates')->get();
        $categorias = DB::table('categorias')->get();
        $urgencias = DB::table('urgencias')->get();

        return view('Demandas:site.dashboard.demandas', compact('tipo', 'categorias', 'urgencias'));
    }

    public function notificao()
    {
        return view('Notificações:site.dashboard.notificacao');
    }

    public function enviar_demanda(Request $request)
    {
        echo json_encode('a');
        exit;

        return response()->json($request->all());
    }
}
