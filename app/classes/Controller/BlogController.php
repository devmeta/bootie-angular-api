<?php namespace Controller;

class BlogController extends \Controller\BaseController  {

	static $layout = "";
	
	public function index(){
		return \Bootie\App::json(\Model\Post::select('fetch','id,title,slug,caption,updated',null,null,5,0,[
			'id' => 'DESC'
		]));
	}

	public function tag($tag){
		return \Bootie\App::view('blog.tags',[
			'posts'	=> \Controller\BlogController::find_by_tag($tag),
			'tags'	=> \Controller\BlogController::find_all_tags(),
			'about'	=> \Controller\BlogController::find_by_tag('about'),
			'tag'	=> $tag
		]);
	}

	public function show($slug){

		$tags_ids = [];

		$entry = \Model\Post::row([
			'slug' => urldecode($slug)
		]);

		if($entry) {

			// attach social network metadata
			$meta = new \stdClass();
			$meta->og_title = $entry->title;
			$meta->og_description = $entry->caption;
			$posts_id = $related = [];

			foreach($entry->tags() as $tag){
				if( isset($tag->id)){
					$tags_ids[] = $tag->id;
				}
			}

			/*
			if(count($entry->files())){
				$meta->og_image = site_url('upload/posts/std/' . $entry->files()[0]->name);
			}
			*/

			if(count($tags_ids)){
				$tag_obs = \Model\TagRelation::fetch([
					'tag_id IN(' . implode(',',array_unique($tags_ids)) . ')'
				]);

				foreach($tag_obs as $tag){
					if($tag->post_id == $entry->id) continue;
					$posts_id[] = $tag->post_id;
				}

				if(count($posts_id)){
					$related = \Model\Post::fetch([
						'id IN(' . implode(',',array_unique($posts_id)) . ')',
						"content <> '<p><br></p>'"
					]);
				}
			}

			return \Bootie\App::json([
				'entry'	=> $entry,
				'meta'	=> $meta,
				'related' => $related
			]);
		} 

		header("HTTP/1.0 404 Not Found");
		die();
		
		return \Bootie\App::json([]);
	}


	public static function find_by_tag($tags,$where = [], $limit = 0){

		$tags = "'" . str_replace(",","','",$tags) . "'";
		$posts = [];

		$tags_id = \Model\Tag::select('fetch','id',null,[
			'tag IN(' . $tags . ')'
		]);

		if( ! empty($tags_id)){

			$posts_id = \Model\TagRelation::select('fetch','post_id',null,[
				'tag_id IN(' . implode(',',$tags_id) . ')'
			]);

			$wheres = [
				'id IN(' . implode(',',array_unique($posts_id)) . ')'
			];

			if(count($where)) $wheres[] = implode(' AND ', $where);

			if(count($posts_id)){
				$posts = \Model\Post::fetch($wheres,($limit?:10),0,[
					'position' => "ASC"
				]);
			}

			return $posts;
		}

		return FALSE;
	}

	public function find_all_tags(){

		$posts_id = \Model\Post::select('fetch','id');

		if(count($posts_id)){

			$tags_ids = \Model\TagRelation::select('fetch','tag_id',null,[
				'post_id IN(' . implode(',',$posts_id) . ')'
			]);

			if(count($tags_ids)){
				return \Model\Tag::fetch([
					'id IN(' . implode(',',array_unique($tags_ids)) . ')'
				]);
			}
		}

		return FALSE;
	}

	public function files($type, $id){
		$files = [];

		if($id){
			$files = \Model\File::select('fetch','*',null,[
				$type . '_id' => $id
			]);
		}

		return $files;
	}	
}