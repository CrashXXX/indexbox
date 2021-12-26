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
		$footer = $this->footer;
		include('view/footer.php');
	}
}