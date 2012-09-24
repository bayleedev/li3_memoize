<?php

namespace li3_memoize\tests\cases\extensions\adapter\storage\cache;

use li3_memoize\extensions\Memoize;
use li3_memoize\extensions\adapter\storage\cache\DocumentMemoizerProxy;
use li3_memoize\tests\mocks\ArrayDocument;

class DocumentMemoizerProxyTest extends \lithium\test\Unit {

	protected $class = "li3_memoize\\extensions\\adapter\\storage\\cache\\DocumentMemoizerProxy";

	public function testGetProxy() {

	}

	public function testGetDocument() {
		
	}

	public function testCurrent() {
		$arr = new DocumentMemoizerProxy(new ArrayDocument);
		$arr[0] = 'name';
		$arr[1] = 'gender';
		$arr['name']  = 'Blaine';
		$this->assertEqual(0, current($arr));
	}
}