<?php

use lithium\util\collection\Filters;
use li3_memoize\extensions\Memoize;

// Filter helpers
Filters::apply('lithium\core\Libraries', 'instance', function($self, $params, $chain) {
	$object = $chain->next($self, $params, $chain);
	if($params['type'] == 'helper') {
		$object = Memoize::instance('helper', $object);
	}
	return $object;
});