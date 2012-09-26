<?php

namespace li3_memoize\data\entity;

use li3_memoize\extensions\Memoize;

class Document extends lithium\data\entity\Document {

	/**
	 * This is where the results are stored.
	 */
	protected $_memoizeResults = array();

	/**
	 * A list of methods that need to be filtered
	 */
	protected $_methods = array();

	/**
	 * Will construct a list of methods to filter
	 */
	protected function _init() {
		parent::_init();
		$this->_methods =& Memoize::$objectNames[$this->_model];
	}

	/**
	 * __call
	 *
	 * The heart of the DocumentProxy. The method and params go in, the result is then cached and servered next time.
	 *
	 * @param string $method
	 * @param array $params
	 * @return mixed
	 */
	public function __call($method, $params) {
		// Is filterable?
		if(!isset($this->_methods[$method])) {
			return parent::__call($method, $params);
		}

		// Variables
		$hash = Memoize::hashArgs($params);

		// Create array if it doesn't exist
		if(!isset($this->_memoizeResults[$method])) {
			$this->_memoizeResults[$method] = array();
		}

		// Check if method + params have been ran already
		if(isset($this->_memoizeResults[$method][$key])) {
			return $this->_memoizeResults[$method][$key];
		}

		// Set and return
		return ($this->_memoizeResults[$method][$key] = parent::__call($method, $params));
	}
}