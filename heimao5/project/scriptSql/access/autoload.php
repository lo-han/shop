<?php
class Autoload{
    public static function useClass($class)
	{
	    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
	    $file = dirname(__DIR__) . DIRECTORY_SEPARATOR . $path . '.class.php';
	    if (file_exists($file)) {
	        require_once $file;
	    }
	}
	
	public static function classLoader($class)
	{
	    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
	    $file = $path . '.php';
		
	    if (file_exists($file)) {	
	        include $file;
	    }
	}

	
}

