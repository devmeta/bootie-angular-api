<?php namespace Controller;

class ApiController 
{
	static $layout = "";
	
	public function index() 
	{
		return \Bootie\App::view('index');
	}
}