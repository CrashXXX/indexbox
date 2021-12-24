<?php

class Footer
{
	private $footer;


	public function __construct($footer)
	{
		$this->footer = $footer;
	}


	public function output()
	{
		include('view/footer.php');
	}
}