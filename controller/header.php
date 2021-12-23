<?php

class Header
{
	private $title;

	public function __construct($title)
	{
		$this->title = $title;
	}

	public function output()
	{
		include('view/head.html');
	}
}