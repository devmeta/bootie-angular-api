<?php namespace Controller\Admin;

class PackController extends \Controller\BaseController {
	
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

	public function delete3($id){

		if(is_numeric($id))
		{
			$user_id = session('user_id');

			$entry = \Model\Pricing::row([
				'id' => $id
			]);

			if( $entry )
			{

				$title = $entry->title;
				$entry->delete();

				return redirect('/admin/pricing',[
					'success' => "Entry <strong>{$title}</strong> has been deleted"
				]);
			}
		}

		return redirect('/admin/pricing',[
			'danger' => "Entry was not found"
		]);
	}
}