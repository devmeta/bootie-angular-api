<?php namespace Controller\Admin;

class UserController extends \Controller\BaseController {
	
	static $layout = "";

	public function index(){
		$data = \Model\User::fetch();
		$rows = [];

		foreach($data as $i => $row){
			$row->load();
			$row->role->load();
			$row->group->load();
			$row->files->load();
			unset($row->pass);
			$rows[$i] = $row->data;
			$rows[$i]['role'] = $row->role->data;
			$rows[$i]['files'] = $row->files->data;
			$rows[$i]['group'] = $row->group->data;
		}

		return \Bootie\App::json($rows);
	}

	public function delete($id){

		if(is_numeric($id))
		{
			$user_id = session('user_id');

			$entry = \Model\Group::row([
				'id' => $id
			]);

			if( $entry )
			{

				$title = $entry->title;
				$entry->delete();

				return redirect('/admin/users',[
					'success' => "Entry <strong>{$title}</strong> has been deleted"
				]);
			}
		}

		return redirect('/admin/users',[
			'danger' => "Entry was not found"
		]);
	}
}