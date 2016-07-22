<?php namespace Controller;

class AuthController extends \Controller\BaseController {

	static private function authenticate($user,$remember = false){

		$ip = get_client_ip();
		$iat = time();
		$timeout = 60 * 60 * 24 * 7; // 1 week
		$nbf = $iat + 1;
		$exp = $nbf + $timeout;

        $jwt = [
        	'iat' => $iat,
        	'nbf' => $nbf,
        	'exp' => $exp,
        	'data' => [
		        'uid' => $user->id,
		        'uip' => $ip,
		        'name' => $user->name,
		        'login' => $user->login,
		        'email' => $user->email,
		        'role' => $user->role->slug?:'default',
		        'group_id' => $user->group->id,
		        'group' => $user->group->title?:'default',
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
	    	"email='$email' or login='$email' or phone='$email'"
	    ]);

	    if ($user != NULL) {
	        if(\Bootie\Hasher::check_password($user->pass,$password)){
		        $response['status'] = "success";
		        $response['message'] = 'Session successfully created';
		        $response['auth'] = self::authenticate($user,$remember);

				return \Bootie\App::json($response);

	        } else {
	            $response['status'] = "error";
	            $response['message'] = 'Signin failed: Invalid credential.';
	        }
	    } else {
	        $response['status'] = "error";
	        $response['message'] = 'Signin failed: No such a user.';
	    }
	    return  \Bootie\App::json($response);
	}

	public static function register(){
		$request_body = file_get_contents('php://input');
		$customer = json_decode($request_body);
		$data = $customer->customer;

	    $isUserExists = \Model\User::row([
	    	"login='$data->login' or phone='$data->phone' or email='$data->email'"
	    ]);

	    if(!$isUserExists){

	        $data->pass = \Bootie\Hasher::hash($data->pass);
	        $column_names = array('login', 'email', 'pass', 'phone', 'name', 'city', 'address');

	        $user = new \model\User;
	        $user->role_id = 2;
	        $user->group_id = 2;
	        $user->created = TIME; 

	        foreach($column_names as $col){
	        	if(isset($data->{$col})){
	        		$user->{$col} = $data->{$col};
	        	}
	        }

	        if ($user->save(1)) {

	            $response["status"] = "success";
	            $response["message"] = "Account successfully created";
		        $response['auth'] = self::authenticate($user);

				return \Bootie\App::json($response);
	        } else {

	            $response["status"] = "error";
	            $response["message"] = "Signup failed: Please try again.";

	            return \Bootie\App::json($response);

	        }            
	    } else {
	        $response["status"] = "error";
	        $response["message"] = "Signup failed: A user already exists with the same login, phone or email!";
	        return \Bootie\App::json($response);
	    }
	}
	
	public static function logout() {
	    $response["status"] = "info";
	    $response["message"] = "Your session's over.";
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