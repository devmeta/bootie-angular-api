<?php namespace Controller;

class HomeController {
	
	static $layout = "";

	public function index(){
		$top = [];
		$middle = [];
		$packs = [];

		$data = \Controller\BlogController::find_by_tag('landing-top',null,3);

		if(!empty($data)){
			foreach($data as $i => $row){
				$files = [];
				
				$row->load();
				$row->files->load();
				unset($row->content);
				$top[$i] = $row->data;

				foreach($row->files() as $file){
					$file->load();
					if($file->position < 3){
						$files[] = $file->data;
					}
				}
				$top[$i]['files'] = $files;
			}
		}

		$data = \Controller\BlogController::find_by_tag('landing-middle');
		if(!empty($data)){
			foreach($data as $i => $row){
				$files = [];
				
				$row->load();
				$row->files->load();
				unset($row->content);
				$middle[$i] = $row->data;

				foreach($row->files() as $file){
					$file->load();
					if($file->position < 3){
						$files[] = $file->data;
					}
				}
				$middle[$i]['files'] = $files;
			}
		}

		return \Bootie\App::json([
			'header' => $top,
			'middle' => $middle,
			'packs' => \Model\Pack::select('fetch','id,title,slug,caption,content,typcn,price,color')
		]);
	}
}