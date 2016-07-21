<?php namespace Controller;

class AuthController extends \Controller\BaseController {

	static private function authenticate($user,$remember = false){


		$ip = get_client_ip();


		$iat = time();
		$timeout = 60 * 60 * 24 * 7; // 1 week
		$nbf = $iat + 1;
		$exp = $nbf + $timeout;
		$role = $user->role->slug?:'default';
		$group = $user->group->title?:'default';

        $jwt = [
        	'iat' => $iat,
        	'nbf' => $nbf,
        	'exp' => $exp,
        	'data' => [
		        'name' => $user->title,
		        'login' => $user->login,
		        'uid' => $user->id,
		        'uip' => $ip,
		        'role' => $role,
		        'group_id' => $user->group->id,
		        'group' => $group,
		        'email' => $user->email,
		        'created' => $user->created,
		        'session_count' => $user->session->count(),
		        'session_exp' => date('Y M j H:i',$exp),
		        'remember' => $remember
	        ]
	    ];

        $token = \Firebase\JWT\JWT::encode($jwt, config()->jwtKey);
        $jwt['token'] = $token;


        // update db session 
        $session = new \Model\Session;
        $session->user_id = $user->id;
        $session->ip = $ip;
        $session->token = $token;
        $session->created = TIME;
        $session->save();
        
		return [
			'jwt' => $jwt
		];
	}

	public static function login() {
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body);
		$email = $data->customer->email;
		$password = $data->customer->password;
		$remember = !empty($data->customer->remember)?$data->customer->remember:false;

	    $user = \Model\User::row([
	    	"login='$email' or email='$email'"
	    ]);

	    if ($user != NULL) {
	        if(\Bootie\Hasher::check_password($user->pass,$password)){
		        $response['status'] = "success";
		        $response['message'] = 'Has iniciado sesión de forma exitosa';
		        $response['auth'] = self::authenticate($user,$remember);

				return \Bootie\App::json($response);

	        } else {
	            $response['status'] = "error";
	            $response['message'] = 'El ingreso ha fallado: La credencial es incorrecta.';
	        }
	    } else {
	        $response['status'] = "error";
	        $response['message'] = 'El ingreso ha fallado: No existe tal usuario';
	    }
	    return  \Bootie\App::json($response);
	}

	public static function signup(){
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body);

	    $isUserExists = \Model\User::row(
	    	"phone='$data->customer->phone' or email='$data->customer->email'"
	    );

	    if(!$isUserExists){

	        $password = \Bootie\Hasher::hash($password);
	        $column_names = array('phone', 'name', 'email', 'password', 'city', 'address');

	        $row = new \model\User;

	        foreach($column_names as $col){
	        	$row->{$col} = $data->customer->{$col};
	        }

	        $result = $row->save(1);

	        if ($result != NULL) {
	            $response["status"] = "success";
	            $response["message"] = "La cuenta ha sido creada exitosamente";

		        $response['auth'] = self::authenticate($user);

				return \Bootie\App::json($response);
	        } else {
	            $response["status"] = "error";
	            $response["message"] = "El registro ha fallado: Por favor vuelva a intentar.";
	            return \Bootie\App::json($response);
	        }            
	    } else {
	        $response["status"] = "error";
	        $response["message"] = "El registro ha fallado: Ya existe un usuario con ese telefono o email!";
	        return \Bootie\App::json($response);
	    }
	}
	
	public static function logout() {
	    $response["status"] = "info";
	    $response["message"] = "Has finalizado sesión exitosamente.";
		return \Bootie\App::json($response);	
	}

	/* aux */
	public static function parseAuthRequest($roles){

		global $app;

	    $headers = parse_request_headers();
	    if(isset($headers['X-Auth-Token'])){
	        list($token) = sscanf( $headers['X-Auth-Token'], 'Bearer %s');
	        if(strlen($token) && $token!='undefined'){
	            try {
	                $jwt = \Firebase\JWT\JWT::decode($token,config()->jwtKey,array('HS256'));
	                $app->jwt = $jwt;
	                if(!in_array($jwt->data->role,$roles)){
	                    header('HTTP/1.0 401 Unauthorized'); 
	                    die('HTTP/1.0 401 Unauthorized');                    
	                }
	            } 
	            catch(\Exception $e) {
	                $message = $e->getMessage();

	                \log_message("jwt excpetion: " . $message);

	                if($message == 'Expired token'){
	                	die("expired");
	                }
	                
	                header('HTTP/1.0 401 Unauthorized'); 
	                die($e->getMessage());
	            }
	        }
	    } else {
	        header('HTTP/1.0 401 Unauthorized'); 
	        die('HTTP/1.0 401 Unauthorized');
	    }
	}
}