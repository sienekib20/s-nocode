<?php

namespace Gbs\Kibo\Regex;

class RegexBuilder
{
	public static function buildRegexRoutePattern($route)
	{
		$partRoute = explode('/', ltrim($route, '/'));
		$route = $partRoute[0];
		$wildcard = array_slice($partRoute, 1);
		$params = $wilds = [];

		foreach($wildcard as $wild) {
			
			list($param, $wildcard) = explode(':', str_replace(['(', ')'], '', $wild));
			if ($wildcard === 'any') $wildcard = RouterRegex::any->value;
			if ($wildcard === 'alpha') $wildcard = RouterRegex::alpha->value;
			if ($wildcard === 'numeric') $wildcard = RouterRegex::numeric->value;

			$params[] = $param;
			$wilds[] = $wildcard;
		}
		
		$route = "$route/" . implode('/', $wilds);

		return  [
			'pattern' => "/^".str_replace('/', '\/', "/$route")."$/",
			'param' => $params
		];
	}
}