<?php namespace Model;

class Group extends \Bootie\ORM
{
    public static $table = 'groups';
	public static $foreign_key = 'group_id';
	public static $has = [
		'users' => '\Model\User',
		'vehicles' => '\Model\Vehicle'
	];
}