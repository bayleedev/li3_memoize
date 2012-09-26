<?php

namespace li3_memoize\template;

use li3_memoize\extensions\Memoize;

/**
 * MemoizerProxy
 *
 * This class was developed to aid in caching expensive method calls.
 * Pass any object into the constructor and use it to directly proxy method calls.
 */
class Helper extends \lithium\template\Helper {

	/**
	 * This is where the results are stored.
	 */
	protected $_memoizeResults = array();

	/**
	 * A copy of the object we are proxying
	 */
	protected $_object;

	/**
	 * An instance of the full namespaced helper name
	 */
	protected $_objectName;

	/**
	 * The constructor will accept the object and cache it.
	 * @param object $object 
	 */
	public function __construct($object) {
		$this->_object = $object;
		$this->_objectName = get_class($object);
	}

	/**
	 * __call
	 *
	 * The heart of the HelperProxy. The method and params go in, the result is then cached and servered next time.
	 *
	 * @param string $method
	 * @param array $params
	 * @return mixed
	 */
	public function __call($method, $params) {

		// To filter or not to filter. That is the question
		if(!in_array($method, Memoize::$objectNames[$this->_objectName])) {
			return call_user_func_array(array($this->_object, $method), $params);
		}

		// Eval
		$key = Memoize::hashArgs($params);

		// Create array if it doesn't exist
		if(!isset($this->_memoizeResults[$method])) {
			$this->_memoizeResults[$method] = array();
		}

		// Check if method + params have been ran already
		if(isset($this->_memoizeResults[$method][$key])) {
			return $this->_memoizeResults[$method][$key];
		}

		// Get results
		switch (count($params)) {
			case 0:
				$this->_memoizeResults[$method][$key] = $this->_object->{$method}();
				break;
			case 1:
				$this->_memoizeResults[$method][$key] = $this->_object->{$method}($params[0]);
				break;
			case 2:
				$this->_memoizeResults[$method][$key] = $this->_object->{$method}($params[0], $params[1]);
				break;
			case 3:
				$this->_memoizeResults[$method][$key] = $this->_object->{$method}($params[0], $params[1], $params[2]);
				break;
			case 4:
				$this->_memoizeResults[$method][$key] = $this->_object->{$method}($params[0], $params[1], $params[2], $params[3]);
				break;
			case 5:
				$this->_memoizeResults[$method][$key] = $this->_object->{$method}($params[0], $params[1], $params[2], $params[3], $params[4]);
				break;
			default:
				$this->_memoizeResults[$method][$key] = call_user_func_array(array(&$this->_object, $method), $params);
		}
		return $this->_memoizeResults[$method][$key];
	}

	/**
	 * Will get the variable from the object.
	 * 
	 * @param string $name 
	 * @return mixed_getHash
	 */
	public function __get($name) {
		return $this->_object->$name;
	}

	/**
	 * Will set a variable from the object.
	 * 
	 * @param string $name 
	 * @param mixed $value 
	 * @return $value
	 */
	public function __set($name, $value = null) {
		return $this->_object->$name = $value;
	}

	/**
	 * Will check if $name is set on the object
	 * 
	 * @param string $name 
	 * @return boolean
	 */
	public function __isset($name) {
		return isset($this->_object->$name);
	}

	/**
	 * Will unset the variable $name on the object
	 * 
	 * @param string $name
	 * @return null
	 */
	public function __unset($name) {
		unset($this->_object->$name);
		return null;
	}

}