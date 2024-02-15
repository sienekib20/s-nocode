<?php

namespace App\Http\Controllers\silica;

use App\Http\Controllers\Controller;
use Sienekib\Mehael\Http\Request;
use Sienekib\Mehael\Database\Factory\DB;
use Sienekib\Mehael\Support\Auth;

class silica extends Controller
{
	public function index()
	{
		return view('Home:site.silica.users');
	}

	public function websites()
	{
		return view('Websites:site.silica.websites');
	}

	public function campanhas()
	{
		return view('Campanhas:site.silica.campanhas');
	}
}