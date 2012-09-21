<?php

use lithium\util\collection\Filters;

Filters::apply('lithium\core\Libraries', 'instance', function($self, $params, $chain) {
	$object = $chain->next($self, $params, $chain);
	return $object;
});