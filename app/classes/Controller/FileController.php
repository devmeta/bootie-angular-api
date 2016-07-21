<?php namespace Controller;

class FileController extends \Controller\BaseController {

	public function index($namespace,$id){

		$files = [];

		if($id AND $namespace){
			$files = \Model\File::select('fetch','*',null,[
				$namespace . '_id' => $id
			]);
		}

		return \Bootie\App::json($files);
	}

	static function upload($folder = false,$id = 'file'){
		if ($_FILES[$id]['name']) {
            if (!$_FILES[$id]['error']) {
            	$fsy = SP . 'public/upload/' . date('Y');
            	if( ! $folder) $folder = date('Y').'/'.date('m');
            	$fsx = SP . 'public/upload/' . $folder;
            			
            	if( ! is_dir($fsy)){
            		mkdir($fsy);
            		chmod($fsy,0777);
            	}

            	if( ! is_dir($fsx)){
            		mkdir($fsx);
            		chmod($fsx,0777);
            	}

                $name = md5(rand(100, 200));
                $ext = explode('.', $_FILES[$id]['name']);
                $filename = $name . '.' . $ext[1];
                $destination = $fsx . '/' . $filename; //change this directory
                $location = $_FILES[$id]["tmp_name"];

                move_uploaded_file($location, $destination);
                $message =  '/upload/' . $folder . '/' . $filename;//change this URL
            }
            else
            {
              $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES[$id]['error'];
            }
        }

        if(AJAX_REQUEST) die($message);

        return $filename;

        //return ['path' => $message];
	}

	public function destroy(){

		$request_body = file_get_contents('php://input');
		$request = json_decode($request_body);
		$data = $request->customer;

		if( is_numeric($data->id) ){

			$file = \Model\File::row([
				'id' => $data->id
			]);

			if( $file )
			{
				\Bootie\Image::destroy_group($file->name,'posts');
				$file->delete();
			}

			return ['ok'];
		}

		return [
			'error' => "No File ID provided"
		];
	}

	public function order(){
		$request_body = file_get_contents('php://input');
		$request = json_decode($request_body);
		$data = $request->customer;

		if($data->sorted){
			foreach($data->sorted as $i => $id) {
				$entry = new \Model\File();
				$entry->id = $id;
				$entry->position = ($i+1);
				$entry->save();
			}
		}

		return ['ok'];
	}

	static public function resize(){

		extract($_POST);

		if ( ! empty($_FILES) ) {

			$mainFolder = SP . 'public/upload/';
		
			directory_is_writable($mainFolder,0777);

			$storeFolder =  $mainFolder . $domain;
			$filename = filename_unique($storeFolder. '/' . key(config()->img_sizes) . '/',$_FILES['file']['name']);
			
			directory_is_writable($storeFolder,0777);

			$orig_filename = $storeFolder . $filename;

			if( copy($_FILES['file']['tmp_name'], $orig_filename)){

				$stat = stat($orig_filename);

				$entry = new \Model\File();
				$entry->name = $filename;
				$entry->{ $namespace . '_id'} = $id;
				$entry->file_size = $stat[7];
				$entry->created = TIME;
				$entry->updated = TIME;

				$entry->save();

				\Bootie\Image::resize_group($orig_filename,$storeFolder,$filename, config()->img_sizes);

				if( config()->img_save_orig)
				{
					directory_is_writable($storeFolder. '/cp/',0777);
					copy($orig_filename, $storeFolder . '/cp/' . $filename);
					unlink($orig_filename);
				}

				return [
					'success' => true, 
					'id' => $entry->id, 
					$namespace . '_id' => $id
				];
			}

			throw new \Exception('Could not write to directory:' . $storeFolder );
		}
	}
}