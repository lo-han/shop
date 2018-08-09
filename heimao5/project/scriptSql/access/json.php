<?php
namespace access;

final class json{
	
	public function put($path,array $arr = [])
	{		
		$this->makeDir($this->verifyPath($path));	//验证创建文件夹

		return file_put_contents($path, json_encode($arr));
	}

	public function get()
	{

	}


	public function verifyPath($path)
	{
		$path = pathinfo($path);
		return $path['dirname'];
	}


	public function makeDir($destFolder,$chmod = 0777)
  	{
	    if (! is_dir($destFolder) && $destFolder != './' && $destFolder != '../')
	    {
	          $dirname = '';
	          $folders = explode( DIRECTORY_SEPARATOR , $destFolder);
	           foreach ($folders as $folder)
	          {
	                $dirname .= $folder . DIRECTORY_SEPARATOR;
	                 if ($folder != '' && $folder != '.' && $folder != '..' && ! is_dir($dirname))
	                {
	                      mkdir($dirname,$chmod);
	                }
	          }
	    
	     // chmod($destFolder,0777);
	    }
	}
}

?>