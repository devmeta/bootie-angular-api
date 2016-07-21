<?php namespace Model;

class Role extends \Bootie\ORM
{
    public static $table = 'roles';
	public static $foreign_key = 'role_id';
	public static $has = [
		'users' => '\Model\User'
	];
}