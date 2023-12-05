<?php

namespace Gbs\Kibo\Regex;

class RegexChecker
{
	public static function findWildcard($route) : int
	{
		$pattern = "/.*\/\(.+\)$/";

		return preg_match($pattern, $route);
	}
}