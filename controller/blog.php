<?php

class Blog
{
	private Model $model;
	protected string $h1;
	protected string $body;
	protected string $views;
	protected string $time;


	public function __construct($data = false)
	{
		$this->model = new Model();
		if ($data) {
			$this->model->updateBlogViews($data['href']);
			$this->h1 = strip_tags(html_entity_decode($data['h1']));
			$this->body = html_entity_decode($data['body']);
			$this->views = (int)$data['views'] + 1;
			$this->time = date('d.m.Y', $data['time_create']);
		}
	}


	public function getBlogData($href)
	{
		$data =  $this->model->getBlogData($href);
		$data['title'] = html_entity_decode($data['title']);
		$data['body'] = html_entity_decode($data['body']);
		$data['description'] = html_entity_decode($data['description']);
		$data['product'] = html_entity_decode($data['product']);
		return $data;
	}


	public function output()
	{
		$h1 = $this->h1;
		$body = $this->body;
		$views = $this->views;
		$time = $this->time;
		include('view/blog.php');
	}

}