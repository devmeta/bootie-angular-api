<?php namespace Controller;

class BaseController {
	
	public static function get_definition(){
		$reflection = new \ReflectionClass(get_called_class());
		$class = $reflection->getShortName();
		$definition = str_replace('Controller','',$class);
		
		return (object) [
			'namespace' => '\\Model\\' . $definition,
			'class'	=> $definition
		];
	}

	public function edit($id){
		if(is_numeric($id))
		{
			$def = self::get_definition();
			
			$entry = call_user_func_array(array($def->namespace,'find'),array($id));

			if(!empty($entry))
			{
				$entry->load();
				return \Bootie\App::json($entry->data);
			}
		}
		
		throw new \Exception('ID was not provided');
	}
 
	public function update(){

		global $app;

		$request_body = file_get_contents('php://input');
		$request = json_decode($request_body);
		$data = $request->customer;
		$def = self::get_definition();
		$db = new \Bootie\Database;
		$class = new \ReflectionClass($def->namespace);
		$table = $class->getStaticPropertyValue('table');
		$fields = $db->fetch('SHOW COLUMNS FROM ' . $table,null,'Field');
		$entry = new $def->namespace();

		if(!empty($data->id)) {
			$entry->id = $data->id;
		}

		if(!empty($app->jwt->data->uid) AND in_array('user_id',$fields)) {
			$entry->user_id = $app->jwt->data->uid;
		}

		foreach($fields as $field){
			if(!empty($data->{$field})){
				$entry->{$field} = $data->{$field};
			}
		}

		if(!empty($fields->updated)){
			$data->updated = TIME;
		}

		if($entry->save()) {
			$response["status"] = "success";
			$response["message"] = $def->class . " Actualizado";
		} else {
			$response["status"] = "error";
			$response["message"] = "Error al guardar " . $def->class;
		}

		if(!empty($entry->id)){
			$response['id'] = $entry->id;
		}

		return \Bootie\App::json($response);
	}

	public function delete($id){

		$response["status"] = "warning";
		$response["message"] = "No ID was provided";

		if(is_numeric($id)){

			$def = self::get_definition();
			$entry = $def->namespace::find($id);

			if($entry->delete()) {
				$response["status"] = "success";
				$response["message"] = $def->class . " deleted";
			} else {
				$response["status"] = "error";
				$response["message"] = "Error when deleting " . $def->class;
			}
		}

		return \Bootie\App::json($response);
	}
}