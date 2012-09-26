<?php

namespace li3_memoize\tests\mocks;

class Users extends \lithium\data\Model {
	public function name($entity) {
		sleep(2);
		return $entity->name;
	}
}