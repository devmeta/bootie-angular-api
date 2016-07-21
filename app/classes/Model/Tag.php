<?php namespace Model;
 
class Tag extends \Bootie\ORM
{
    public static $table = 'tags';
    public static $foreign_key = 'tag_id';
    public static $where = ["tag!=''"];

    public static $belongs_to = array(
        'tag_id' => '\Model\TagRelation'
    );

    public static $has_many_through = array(
        'tags' => array(
            'post_id' => '\Model\TagRelation',
            'post_id' => '\Model\Post',
        ),
    );    
}