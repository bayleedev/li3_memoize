<?php

namespace li3_memoize\data\entity;

use li3_memoize\extensions\Memoize;

class Record extends \lithium\data\entity\Record {

	/**
	 * This is where the results are stored.
	 */
	protected $_memoizeResults = array();

	/**
	 * __call
	 *
	 * The heart of the RecordProxy. The method and params go in, the result is then cached and servered next time.
	 *
	 * @param string $method
	 * @param array $params
	 * @return mixed
	 */
	public function __call($method, $params) {
		// Is filterable?
		if(!in_array($method, Memoize::$objectNames[$this->_model])) {
			return parent::__call($method, $params);
		}

		// Variables
		$hash = Memoize::hashArgs($params);

		// Create array if it doesn't exist
		if(!isset($this->_memoizeResults[$method])) {
			$this->_memoizeResults[$method] = array();
		}

		// Check if method + params have been ran already
		if(isset($this->_memoizeResults[$method][$hash])) {
			return $this->_memoizeResults[$method][$hash];
		}

		// Set and return
		return ($this->_memoizeResults[$method][$hash] = parent::__call($method, $params));
	}
}