<?php namespace Model;
 
class TagRelation extends \Bootie\ORM
{
    public static $table = 'tags_relation';
    public static $foreign_key = 'id';
	public static $cascade_delete = false;
	
    public static $has = array(
        'post' => '\Model\Post',
        'tag' => '\Model\Tag',
    );
}