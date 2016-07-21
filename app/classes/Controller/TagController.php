<?php namespace Controller;

class TagController extends \Controller\BaseController {
	
	static $layout = "admin";

	public function tags($type,$id){

		$tags_ids = $included = $excluded = [];

		$tags_ids = \Model\TagRelation::select('fetch','tag_id',null,[
			$type . '_id' => $id
		]);

		if( ! count($tags_ids)){
			$tags_ids = [0];
		}
		
		$included = \Model\Tag::select('fetch','tag',null,[
			'id IN(' . implode(',',$tags_ids) . ')',
			'type' => $type
		]);

		$excluded = \Model\Tag::select('fetch','tag',null,[
			'id NOT IN(' . implode(',',$tags_ids) . ')',
			'type' => $type
		]);	
			
		return \Bootie\App::json([
			'included' => $included,
			'excluded' => $excluded
		]);
	}

	public function add($type,$id){

		$request_body = file_get_contents('php://input');
		$request = json_decode($request_body);
		$data = $request->customer;
		extract((array) $data);

		$included = [];

		if(isset($tags)){

			foreach( $tags as $tag ){

				$tag_id = \Model\Tag::select('column','id',null,[
					'tag' => $tag
				]);

				if( ! $tag_id ){
					$tag2 = new \Model\Tag();
					$tag2->tag = $tag;
					$tag2->type = $type;
					$tag2->save();

					$tag_id = $tag2->id;
				}

				$type_id = $type . '_id';
				$post_tag = new \Model\TagRelation();
				$post_tag->tag_id = $tag_id;
				$post_tag->{$type_id} = $id;
				$post_tag->save();

				$included[] = $tag;
			}
		}

		return \Bootie\App::json(['result' => "success"]);
	}

	public function remove($id){

		$request_body = file_get_contents('php://input');
		$request = json_decode($request_body);
		$data = $request->customer;
		extract((array) $data);

		if(isset($tags)){
			
			foreach( $tags as $tag ){

				$tag_id = \Model\Tag::select('column','id',null,[
					'tag' => $tag
				]);

				$entry = \Model\TagRelation::row([
					'tag_id' => $tag_id,
					'post_id' => $id
				]);

				if($entry) $entry->delete();

			}
		}

		return \Bootie\App::json(['result' => "success"]);
	}	
}