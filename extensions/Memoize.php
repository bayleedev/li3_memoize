<?php

namespace li3_memoize\extensions;

use li3_memoize\extensions\adapter\storage\cache\MemoizerProxy;
use li3_memoize\extensions\adapter\storage\cache\DocumentMemoizerProxy;

/**
 * Memoize
 * 
 * Main Memoize class helps add the filters to the specific libraries.
 * 
 * This is the interface that bootstrap will use to add specific helpers/methods to our list.
 * We then filter the creation of helpers (li3_memoize/config/bootstrap.php) and pass them through Memoize::instance
 * Once in it'll either return the same object or a new instance of MemoizerProxy.
 */
class Memoize {

	/**
	 * Holds the names of objects and methods we want to memoize
	 */
	public static $objectNames = array();

	/**
	 * Will add the filter for the specific $methods.
	 * 
	 * ~~~ php
	 * use li3_memoize\extensions\Memoize;
	 * Memoize::add(array(
	 * 	array(
	 * 		'name' => 'app\extensions\helper\Prose',
	 * 		'method' => array('init')
	 * 	),
	 * 	array(
	 * 		'name' => 'app\models\Users',
	 * 		'method' => array('name')
	 * 	),
	 * ));
	 * ~~~
	 * 
	 * @param array $methods
	 */
	public static function add(array $methods) {
		foreach($methods as $method) {
			// Init array
			if(!isset(self::$objectNames[$method['name']])) {
				self::$objectNames[$method['name']] = array();
			}
			// append to array
			self::$objectNames[$method['name']] = array_merge(self::$objectNames[$method['name']], (array)$method['method']);
		}
		return;
	}

	/**
	 * Will proxy the given helper if in the static $objectNames variable.
	 * 
	 * @param object $object 
	 * @param string $class The optional param of providing the class name for us
	 * @return object A new MemoizerProxy or DocumentMemoizerProxy object or the original object
	 */
	public static function catchHelper($object) {
		$class = get_class($object);
		if(isset(self::$objectNames[$class])) {
			return new MemoizerProxy($object, self::$objectNames[$class]);
		}
		return $object;
	}
}