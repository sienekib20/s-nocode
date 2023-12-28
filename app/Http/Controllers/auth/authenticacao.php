<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;
use Sienekib\Mehael\Http\Request;
use Sienekib\Mehael\Database\Factory\DB;

class authenticacao extends Controller
{

	public function login()
	{
		return view('entrar:site.auth.login');
	}

}