<?php 

define('LOCALE','es');
header('Access-Control-Allow-Origin: *');  
header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE,OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Cache-Control, X-Requested-With');

$config = [
	/* 
	 * Available translations (Array of Locales)
	 */

    'key' => '665b13ebc7811c41fef2e7feb5fbbeja3',

    'jwtKey' => 'bfe13ebb665be36b5c7811c41fef2w0p',

	'debug' => true,
	
	/* 
	 * Cookie settings
	 */

    'cookie' => [
            'key' => '665b13ebc7811c41fef2e7feb5fbbe36',
            'timeout' => time()+(60*60*4), // Ignore submitted cookies older than 4 hours
            'expires' => 0, // Expire on browser close
            'path' => '/',
            'domain' => null,
            'secure' => null,
            'httponly' => 0
    ],

	/* 
	 * Cache settings
	 */
	'cache_enabled' => false, 

	'cache' => [
		'cache_ext' => '.cache', //file extension
		'cache_time' => 3600,  //Cache file expires afere these seconds (1 hour = 3600 sec)
		'cache_json' => true,  //Cache file expires afere these seconds (1 hour = 3600 sec)
		'cache_folder' => SP . 'storage/cache/', //folder to store Cache files
		'ignore_pages' => [
			'/login',
			'/register',
			'/logout',
			'/account/(.*)'
		]
	],

	/* 
	 * Email settings
	 */

	'mailer' => [
		'from' => "noreply@devmeta.net",
		'title' => "devmeta.net"
	],

	/* 
	 * Image manipulation (In Pixels)
	 */

	'img_sizes' => [
	    'ty'  => [75,75],
	    'th' => [225,225],
	    'sd'   => [600,400]
	],

	'img_save_orig' => true,

	/* 
	 * Database Access Credentials
	 */

	'database' => [
		'connections' => [
			'default' => [
				'dns' => "mysql:host=127.0.0.1;port=3306;dbname=bootieng;charset=utf8",
				'username' => 'root',
				'password' => '',
				'params' => array()
			]
		]
	]
];
