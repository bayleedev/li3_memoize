<?php

namespace li3_analytics\extensions;

/**
 * Main Memoize class helps add the filters to the specific libraries
 */
class Memoize extends \lithium\core\Adaptable {

	/**
	 * Holds an instance of every item we are already filtering.
	 */
	protected static $instances = array();

	/**
	 * Will add the filter for the specific $methods.
	 * 
	 * The namespace key is optional and will be written as "app/extensions/helper" if not provided.
	 * 
	 * ```php
	 * Memoize::add(array(
	 * 	array(
	 * 		'namespace' => 'app\extensions\helper\',
	 * 		'name' => 'BreadCrumbs',
	 * 		'method' => 'render'
	 * 	)
	 * ));
	 * ```
	 */
	public static function add(array $methods) {
		foreach($methods as $method) {

			//  Update namespace
			if(isset($method['namespace'])) {
				// Default namespace
				$method['namespace'] = 'app\extensions\helper\\';
			} elseif(substr($method['namespace'], -1) !== '\\') {
				// Append backslash
				$method['namespace'] .= '\\';
			}

			// Generate hash
			$hash = forward_static_call_array(self, '_generateMethodHash'), $method);
			
			// Search and add
			if(!in_array($hash, self::$instances)) {
				self::$instances[] = $hash;
				$helperName = $method['namespace'] . $method['name'];
				$helperName::applyFilter($method['method'], $params, function($self, $params, $chain) {
					return $chain->next($self, $params, $chain);
				})
			}
		}
	}

	/**
	 * Will generate a unique hash for the helper/method combination
	 */
	protected static function _generateMethodHash() {
		return md5(serialize(func_get_args()));
	}
}