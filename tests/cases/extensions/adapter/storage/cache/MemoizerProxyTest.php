<?php

namespace li3_memoize\tests\cases\extensions\adapter\storage\cache;

use li3_memoize\extensions\Memoize;
use li3_memoize\tests\mocks\Prose;
use li3_memoize\tests\mocks\Speaker;

class MemoizerProxyTest extends \lithium\test\Unit {

	protected $class = "li3_memoize\\extensions\\adapter\\storage\\cache\\MemoizerProxy";

	/**
	 * Will return the protected/private variables
	 */
	protected function getVariables($obj, $name) {
		$reflectionClass = new \ReflectionClass($obj);
		$prop = $reflectionClass->getProperty($name);
		$prop->setAccessible(true);
		return $prop->getValue($obj);
	}

	public function testFilteredInstanceMethods() {
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('slowSpeak')
			)
		));
		$prose = new Prose();
		$prose = Memoize::instance($prose);

		$methods = $this->getVariables($prose, 'methods');

		$this->assertEqual(array('slowSpeak'), $methods);
	}

	public function testFilteredInstanceMethodsNonFiltered() {
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('slowSpeak')
			)
		));
		$prose = new Prose();
		$prose = Memoize::instance($prose);

		$expected = 'Hello, World';

		$result = $prose->speak($expected);

		$this->assertEqual($expected, $result);
	}

	public function testFilteredInstanceMethodsFiltered() {
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('slowSpeak')
			)
		));
		$prose = new Prose();
		$prose = Memoize::instance($prose);

		$expected = 'Hello, World';

		$result = $prose->slowSpeak($expected);

		$this->assertEqual($expected, $result);
	}

	public function testFilteredInstanceMethodsFilteredCache() {
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('slowSpeak')
			)
		));
		$prose = new Prose();
		$prose = Memoize::instance($prose);

		$times = array();
		$results = array();

		$words = 'Hello, World';

		$times[0] = microtime(true);

		$results[0] = $prose->slowSpeak($words);

		$times[1] = microtime(true);

		$results[1] = $prose->slowSpeak($words);

		$times[2] = microtime(true);

		// The first iteration needs to be MORE than a second
		$this->assertTrue(($times[1] - $times[0]) > 1);

		// The second needs to be LESS than a second
		$this->assertTrue(($times[2] - $times[1]) < 1);

		// Results should equal
		$this->assertEqual($results[0], $results[1]);
	}

}