<?php

namespace App\Http\Controllers\silica;

use App\Http\Controllers\Controller;

class _connectdb extends Controller
{
	public static function initialize()
	{
		return (_aqdb::getInstance());
	}
}