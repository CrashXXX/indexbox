<?php

class Blog
{
	private Model $model;
	protected string $h1;
	protected string $body;
	protected string $views;
	protected string $time;


	public function __construct($data)
	{
		$this->model = new Model();
		$this->h1 = strip_tags(html_entity_decode($data['h1']));
		$this->body = html_entity_decode($data['body']);
		$this->views = $data['views'];
		$this->time = date('d.m.Y', $data['time_create']);
		$this->model->updateBlogViews($data['href']);
	}


	public function output()
	{
		include('view/blog.php');
	}

}