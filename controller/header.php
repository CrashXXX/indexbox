<?php

class Header
{
	protected string $title;
	protected string $description;


	public function __construct($title, $description)
	{
		$this->setTitle(strip_tags(html_entity_decode($title)));
		$this->setDescription(strip_tags(html_entity_decode($description)));
	}


	public function setTitle($title)
	{
		$this->title = $title;
	}


	public function setDescription($description)
	{
		$this->description = $description;
	}


	public function output()
	{
		include('view/head.html');
	}
}