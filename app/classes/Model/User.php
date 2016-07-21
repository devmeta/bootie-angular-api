<?php namespace Model;

class User extends \Bootie\ORM { 
    public static $table = 'users';
	public static $foreign_key = 'user_id';
	
    public static $belongs_to = [
        'role' => '\Model\Role',
        'group' => '\Model\Group'
    ];

    public static $has = [
        'files' => '\Model\File',
        'session' => '\Model\Session'
    ];
}