<?php

/* Example Column Options:
$column = [
	'type' => 'primary|string|integer|boolean|decimal|datetime',
	'length' => NULL,
	'index' => FALSE,
	'null' => TRUE,
	'default' => NULL,
	'unique' => FALSE,
	'precision' => 0, // (optional, default 0) The precision for a decimal (exact numeric) column. (Applies only if a decimal column is used.)
	'scale' => 0, // (optional, default 0) The scale for a decimal (exact numeric) column. (Applies only if a decimal column is used.)
);
*/

$config = [
	'files' => [
		'id' => ['type' => 'primary'],
		'post_id' => ['type' => 'integer'],
		'user_id' => ['type' => 'integer'],
		'title' => ['type' => 'string', 'length' => 255],
		'content' => ['type' => 'string'],
		'name' => ['type' => 'string', 'length' => 255],
		'status' => ['type' => 'tinyint', 'length' => 1],
		'file_size' => ['type' => 'integer'],
		'position' => ['type' => 'smallint', 'length' => 6],
		'created' => ['type' => 'integer'],
		'updated' => ['type' => 'integer'],
	],
	'groups' => [
		'id' => ['type' => 'primary'],
		'title' => ['type' => 'string', 'length' => 50],
		'caption' => ['type' => 'string'],
		'slug' => ['type' => 'string', 'length' => 50],
		'created' => ['type' => 'integer'],
		'updated' => ['type' => 'integer'],
	],	
	'packs' => [
		'id' => ['type' => 'primary'],
		'slug' => ['type' => 'string', 'length' => 50],
		'title' => ['type' => 'string', 'length' => 250],
		'caption' => ['type' => 'string'],
		'content' => ['type' => 'string'],
		'fulltext' => ['type' => 'string'],
		'typcn' => ['type' => 'string', 'length' => 50],
		'price' => ['type' => 'integer', 'length' => 10],
		'color' => ['type' => 'string', 'length' => 20],
		'created' => ['type' => 'integer'],
		'updated' => ['type' => 'integer'],
	],	
	'posts' => [
		'id' => ['type' => 'primary'],
		'user_id' => ['type' => 'integer'],
		'title' => ['type' => 'string', 'length' => 255],
		'slug' => ['type' => 'string', 'length' => 255],
		'position' => ['type' => 'integer', 'length' => 1],
		'caption' => ['type' => 'string', 'length' => 510],
		'content' => ['type' => 'string'],
		'created' => ['type' => 'integer'],
		'updated' => ['type' => 'integer'],		
	],	
	'roles' => [
		'id' => ['type' => 'primary'],
		'title' => ['type' => 'string', 'length' => 255],
		'slug' => ['type' => 'string', 'length' => 255],
		'caption' => ['type' => 'string', 'length' => 510],
		'created' => ['type' => 'integer'],
		'updated' => ['type' => 'integer'],		
	],	
	'session' => [
		'id' => ['type' => 'primary'],	
		'token' => ['type' => 'string'],
		'user_id' => ['type' => 'integer', 'length' => 11],
		'ip' => ['type' => 'string', 'length' => 50],
		'created' => ['type' => 'integer'],
	],
	'tags' => [
		'id' => ['type' => 'primary'],
		'user_id' => ['type' => 'integer'],
		'tag' => ['type' => 'string', 'length' => 255],
		'type' => ['type' => 'string', 'length' => 10],
		'created' => ['type' => 'integer'],
		'updated' => ['type' => 'integer'],		
	],	
	'tags_relation' => [
		'id' => ['type' => 'primary'],
		'post_id' => ['type' => 'integer'],
		'user_id' => ['type' => 'integer'],
		'tag_id' => ['type' => 'integer'],
		'created' => ['type' => 'integer'],
		'updated' => ['type' => 'integer'],		
	],	
	'users' => [
		'id' => ['type' => 'primary'],
		'role_id' => ['type' => 'integer'],
		'group_id' => ['type' => 'integer'],
		'lang' => ['type' => 'string', 'length' => 2],
		'login' => ['type' => 'string', 'length' => 50],
		'pass' => ['type' => 'string', 'length' => 255],
		'name' => ['type' => 'string', 'length' => 50],
		'phone' => ['type' => 'string', 'length' => 50],
		'email' => ['type' => 'string', 'length' => 50],
		'address' => ['type' => 'string', 'length' => 50],
		'city' => ['type' => 'string', 'length' => 50],
		'caption' => ['type' => 'text'],
		'status' => ['type' => 'integer', 'length' => 6],
		'created' => ['type' => 'integer'],
		'updated' => ['type' => 'integer']
	]
];