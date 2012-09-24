<?php

namespace li3_memoize\extensions\adapter\storage\cache;

/**
 * MemoizerProxy
 *
 * This class was developed to aid in caching expensive method calls.
 * Pass any object into the constructor and use it to directly proxy method calls.
 */
class MemoizerProxy extends \lithium\template\Helper {

	/**
	 * This is where the results are stored.
	 */
	protected $_memoizeResults = array();

	/**
	 * A copy of the helper we are proxying
	 */
	protected $helper;

	/**
	 * A list of methods that need to be filtered
	 */
	protected $methods = array();

	/**
	 * The constructor will accept the helper and cache it.
	 * @param object $helper 
	 */
	public function __construct($helper, &$methods) {
		$this->helper = $helper;
		$this->methods = $methods;
	}

	/**
	 * __call
	 *
	 * The heart of the application. The method and params go in, the result is then cached and servered next time.
	 *
	 * @param string $method
	 * @param array $params
	 * @return mixed
	 */
	public function __call($method, $params) {

		// To filter or not to filter. That is the question
		if(!in_array($method, $this->methods)) {
			return call_user_func_array(array($this->helper, $method), $params);
		}

		// Eval
		$key = $this->_getHash($params);

		// Create array if it doesn't exist
		if(!isset($this->_memoizeResults[$method])) {
			$this->_memoizeResults[$method] = array();
		}

		// Check if method + params have been ran already
		if(isset($this->_memoizeResults[$method][$key])) {
			return $this->_memoizeResults[$method][$key];
		}

		// Get results
		return ($this->_memoizeResults[$method][$key] = call_user_func_array(array($this->helper, $method), $params));
	}

	/**
	 * Will get the variable from the object.
	 * 
	 * @param string $name 
	 * @return mixed
	 */
	public function &__get($name) {
		return $this->helper->$name;
	}

	/**
	 * Will set a variable from the object.
	 * 
	 * @param string $name 
	 * @param mixed $value 
	 * @return $value
	 */
	public function __set($name, $value = null) {
		return $this->helper->$name = $value;
	}

	/**
	 * Will check if $name is set on the object
	 * 
	 * @param string $name 
	 * @return boolean
	 */
	public function __isset($name) {
		return isset($this->helper->$name);
	}

	/**
	 * Will unset the variable $name on the object
	 * 
	 * @param string $name
	 * @return null
	 */
	public function __unset($name) {
		return unset($this->helper->$name);
	}

	/**
	 * _getHash
	 *
	 * The current params go in, a unique hash comes out.
	 *
	 * This was put into it's own method so that it can be overwritten by your class if you'd like to improve/simplify it.
	 *
	 * @param array $params
	 * @return string
	 */
	protected function _getHash($params) {
		$hash = array();
		foreach($params as &$param) {
			if(is_object($param)) {
				$hash[] = spl_object_hash($param);
			} else {
				$hash[] =& $param;
			}
		}
		return md5(serialize($hash));
	}
}