<?php



class Regex
{
	private static function hasWildcard(string $path)
	{
		$pattern = '/^\/[a-zA-Z\_]+\/\(([a-zA-Z]+):([a-zA-Z]+)\)$/';

		return (preg_match($pattern, $path, $matches)) ? $matches : null; 
	}

	public static function resolvePath(string $path)
	{
		$path = 

		if (! ($matches = static::hasWildcard($path))) {
			return $path;
		}

		var_dump($matches);exit;

		'/login/(id:numeric)';

		var_dump('aqui ainda');exit;
	}
}