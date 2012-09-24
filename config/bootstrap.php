<?php

use lithium\util\collection\Filters;
use li3_memoize\extensions\Memoize;

/**
 * Filters the creation of helpers/models and passes them into Memoize::instance
 */
echo 'hello world';
Filters::apply('lithium\core\Libraries', 'instance', function($self, $params, $chain) {
	$object = $chain->next($self, $params, $chain);

	// Helper
	if($params['type'] == 'helper') {
		$object = Memoize::instance($object);
	}

	// Model
	if($params['name'] == 'lithium\data\collection\RecordSet') {
		$object = Memoize::instance($object, $params['options']['model']);
	}

	return $object;
});