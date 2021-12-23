<?php

class Header
{
	private string $title;
	private string $description;
	private string $h1;


	public function __construct()
	{
		//$this->title = $title;
	}


	public function setTitle($title)
	{
		$this->title = $title;
	}


	public function setDescription($description)
	{
		$this->description = $description;
	}


	public function setH1($h1)
	{
		$this->h1 = $h1;
	}


	public function getTitle(): string
	{
		return $this->title;
	}


	public function getDescription(): string
	{
		return $this->description;
	}


	public function getH1(): string
	{
		return $this->h1;
	}


	public function output()
	{
		include('view/head.html');
	}
}