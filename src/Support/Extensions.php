<?php

namespace Sienekib\Mehael\Support;

class Extensions
{
	public static function load()
	{	
		$extensionName = 'zip'; // Replace with the name of the extension you want to load
		/*if (!extension_loaded($extensionName)) {
		    if (function_exists('dl')) {
		        if (dl($extensionName . '.' . PHP_SHLIB_SUFFIX)) {
		            echo "$extensionName extension loaded dynamically.";
		        } else {
		            echo "Failed to load $extensionName extension dynamically.";
		        }
		    } else {
		        echo "The dl function is disabled. You cannot load extensions dynamically.";
		    }
		} else {
		    echo "$extensionName extension is already loaded.";
		}*/
	}
}