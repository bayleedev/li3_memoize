<?php

namespace li3_memoize\extensions\adapter;

/**
 * Memoize
 *
 * This class was developed to aid in caching expensive method calls.
 */
class MemoizerProxy extends \lithium\template\Helper {

	/**
	 * This is where the results are stored.
	 */
	protected $_memoizeResults = [];

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
			$this->_memoizeResults[$method] = [];
		}

		// Check if method + params have been ran already
		if(isset($this->_memoizeResults[$method][$key])) {
			return $this->_memoizeResults[$method][$key];
		}

		// Get results
		return ($this->_memoizeResults[$method][$key] = call_user_func_array(array($this->helper, $method), $params));
	}

	/**
	 * _getHash
	 *
	 * The current params go in, a unique hash comes out.
	 *
	 * This was put into it's own method so that it can be overwritten by your class if you'd like to improve/simplify it.
	 *
	 * @param mixed $data
	 * @return string
	 */
	protected function _getHash($data) {
		return md5(serialize(func_get_args()));
	}
}