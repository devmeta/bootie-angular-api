<?php 

require('../bootstrap/bootstrap.php');
require('../config/common.php');
require('../config/routes.php');

/*
 * If cache option is enabled AND this url is grabbed
 * then execution stops on the next line
 */

if( config()->cache_enabled ) return \Bootie\Cache::init(config()->cache,PATH);

$app = new \Bootie\App;

try 
{
	$app->run(PATH);
} 
catch (\Exception $e) 
{
	if($e->getMessage() == 'Missing Route.') {
		die("Invalid request");
	}

	\Bootie\Error::exception($e);
}

if( config()->cache_enabled ) \Bootie\Cache::store();