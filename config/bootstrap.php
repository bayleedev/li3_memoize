<?php

use lithium\util\collection\Filters;
use li3_memoize\extensions\Memoize;

/**
 * Filters the creation of helpers/models
 */
Filters::apply('lithium\core\Libraries', 'instance', function($self, $params, $chain) {

	// Prescan Model
	if(isset($params['options']['model'])) {
		Memoize::catchModel($self, $params, $chain);
	}

	$object = $chain->next($self, $params, $chain);

	// Postscan Helper
	if($params['type'] == 'helper') {
		Memoize::catchHelper($object);
	}

	return $object;
});