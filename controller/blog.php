<?php

class Blog
{
	public $data;
	private Model $model;

	public function __construct($href)
	{
		$this->model = new Model();
		$this->model->updateBlogViews($href);
		$blog = $this->model->getBlogData($href);

		$this->data['title'] = html_entity_decode($blog['title']);
		$this->data['body'] = html_entity_decode($blog['body']);
		$this->data['description'] = html_entity_decode($blog['description']);
		$this->data['product'] = html_entity_decode($blog['product']);

		$this->data['h1'] = strip_tags(html_entity_decode($blog['h1']));
		$this->data['views'] = $blog['views'];
		$this->data['time'] = date('d.m.Y', $blog['time_create']);
	}


	public function output()
	{
		$data = $this->data;
		include('view/blog.php');
	}

}