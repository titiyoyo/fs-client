<?php

namespace Tertere\Fs;

class FsClientLogger 
{
	private $logger;

	public function __construct($logger = null) 
	{
		$this->logger = $logger;
	}

	public function __call($method, $params) 
	{
		if ($this->logger) {
			return call_user_func_array(
				array($this->logger, $method),
				$params
			);
		}
	}

	public static function __callStatic($method, $params) 
	{
		if ($this->logger) {
			return call_user_func_array(
				$this->get_class($this->logger) . "::" . $name,
				$params
			);
		}
	}
}

?>