<?php

use lithium\util\collection\Filters;
use li3_memoize\extensions\Memoize;

/**
 * Filters the creation of helpers/models and passes them into Memoize::instance
 */
Filters::apply('lithium\core\Libraries', 'instance', function($self, $params, $chain) {
	$object = $chain->next($self, $params, $chain);

	// Helper
	if($params['type'] == 'helper') {
		$object = Memoize::instance($object);
	}

	// Model
	if(in_array($params['name'], array('lithium\data\entity\Document', 'lithium\data\entity\Record'))) {
		$object = Memoize::instance($object, $params['options']['model']);
	}

	return $object;
});