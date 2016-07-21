<?php namespace Model;

class Post extends \Bootie\ORM
{
    public static $table = 'posts';
	public static $foreign_key = 'post_id';

    public static $belongs_to = [
        'user' => '\Model\User'
    ];

	public static $has = array(
		'files' => '\Model\File',
		'post_tags'	=> '\Model\TagRelation'
	);

	public static $has_many_through = array(
		'tags' => array(
			'post_id' => '\Model\TagRelation',
			'tag_id' => '\Model\Tag',
		)
	);
}