<?php namespace Controller\Admin;

class PostController extends \Controller\BaseController {
	
	public function index(){
		$data = \Model\Post::fetch(null,0,0,['updated' => "DESC"]);
		$rows = [];

		foreach($data as $i => $row){
			$row->load();
			$row->user->load();
			$row->files->load();
			$row->post_tags->load();

			$files=[];
			foreach($row->files() as $file){
				$file->load();
				$files[]=$file->data;
			}

			$rows[$i] = $row->data;
			$rows[$i]['author'] = $row->user->title;
			$rows[$i]['wcount'] = str_word_count($row->content);
			$rows[$i]['files'] = $files;
			$rows[$i]['tags'] = count($row->post_tags());
		}

		return \Bootie\App::json($rows);
	}

	public function delete($id){

        $response["status"] = "error";
        $response["message"] = "Something happened";

		if(is_numeric($id))
		{
			$entry = \Model\Post::row([
				'id' => $id
			]);

			if( $entry )
			{

				$tags = \Model\PostTag::row([
					'post_id' => $id
				]);

				if($tags)
				{
					$tags->delete();
				}

				foreach($entry->files() as $file)
				{
					\Bootie\Image::destroy_group($file->name,'posts');

					\Model\File::row([
						'id' => $file->id
					])->delete();
				}

				$title = $entry->title;
				$entry->delete();

		        $response["status"] = "success";
		        $response["message"] = "Entry deleted";

				return \Bootie\App::json($response);
			}
		}

		return \Bootie\App::json($response);
	}
}