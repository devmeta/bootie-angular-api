<?php namespace Controller;

class PackController  {
	
	static $layout = "";

	public function index(){
		$data = \Model\Pack::fetch();
		$rows = [];
		foreach($data as $i => $row){
			$row->load();
			$rows[$i] = $row->data;
		}
		return \Bootie\App::json($rows);
	}

	public function show($slug){
		$row = \Model\Pack::row(['slug' => $slug]);
		$row->load();
		return \Bootie\App::json([$row->data]);
	}
}