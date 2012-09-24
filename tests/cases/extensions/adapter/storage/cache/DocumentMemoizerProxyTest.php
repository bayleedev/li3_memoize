<?php

namespace li3_memoize\tests\cases\extensions\adapter\storage\cache;

use li3_memoize\extensions\Memoize;
use li3_memoize\extensions\adapter\storage\cache\DocumentMemoizerProxy;
use li3_memoize\tests\mocks\ArrayDocument;

class DocumentMemoizerProxyTest extends \lithium\test\Unit {

	protected $class = "li3_memoize\\extensions\\adapter\\storage\\cache\\DocumentMemoizerProxy";

	public function setUp() {
		$methods = array();
		$this->arr = new DocumentMemoizerProxy(new ArrayDocument, $methods);
	}

	public function testGetSet() {
		$this->arr[0] = 'name';
		$this->arr['name'] = 'BlaineSch';
		$this->assertEqual('name', $this->arr[0]);
		$this->assertEqual('BlaineSch', $this->arr['name']);
	}

	public function testIsset() {
		$this->assertFalse(isset($this->arr[0]));
		$this->arr[0] = 'name';
		$this->assertTrue(isset($this->arr[0]));
	}

	public function testUnset() {
		$this->arr[0] = 'name';
		$this->assertTrue(isset($this->arr[0]));
		unset($this->arr[0]);
		$this->assertFalse(isset($this->arr[0]));
	}

	public function testIteration() {
		$expected = array('name', 'gender', 'location');
		$this->arr[0] = 'name';
		$this->arr[1] = 'gender';
		$this->arr[2] = 'location';
		foreach($this->arr as $key => $value) {
			$this->assertEqual($expected[$key], $value);
		}
	}
}