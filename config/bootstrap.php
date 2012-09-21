<?php

use lithium\util\collection\Filters;
use li3_memoize\extensions\Memoize;

/**
 * Filters the creation of helpers and passes them into Memoize::instance
 */
Filters::apply('lithium\core\Libraries', 'instance', function($self, $params, $chain) {
	$object = $chain->next($self, $params, $chain);
	if($params['type'] == 'helper') {
		$object = Memoize::instance($object);
	}
	return $object;
});