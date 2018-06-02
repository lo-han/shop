<?php
namespace app\contribute\controller;

use \app\contribute\controller\Common;


class Index extends Common
{
    public function index()
    {
        return $this->fetch();
    }

    public function test(){

    	echo 1;
    }

}
