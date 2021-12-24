<?php

class Blog
{
	protected $h1;
	protected $body;
	protected $views;
	protected $time;


	public function __construct($data)
	{
		$this->h1 = strip_tags(html_entity_decode($data['h1']));
		$this->body = html_entity_decode($data['body']);
		$this->views = $data['views'];
		$this->time = date('d.m.Y', $data['time_create']);
	}


	public function output()
	{
		include('view/blog.html');
	}

}