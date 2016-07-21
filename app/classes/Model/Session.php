<?php namespace Model;

class Session extends \Bootie\ORM
{
    public static $table = 'session';
	public static $foreign_key = 'session_id';

    public static $belongs_to = [
        'user' => '\Model\User',
    ];
}