<?php

namespace li3_memoize\tests\cases\extensions;

use li3_memoize\extensions\Memoize;
use li3_memoize\tests\mocks\Prose;
use li3_memoize\tests\mocks\Speaker;

class MemoizeTest extends \lithium\test\Unit {

	protected $class = "li3_memoize\\extensions\\Memoize";
	protected $reflectionClass;

	/**
	 * Will return the protected/private variables
	 */
	protected function getVariables($name) {
		if(!isset($this->reflectionClass)) {
			$this->reflectionClass = new ReflectionClass($this->class);
		}
		return $this->reflectionClass->getStaticPropertyValue($name);
	}

	public function testBasicAdd() {
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('init')
			)
		));
		$result = $this->getVariables('objectNames');
		$this->assertEqual(array(
			'name' => 'li3_memoize\tests\mocks\Prose',
			'method' => array('init')
		), $result);
	}

	public function testDuplicates() {
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('init')
			),
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('list')
			)
		));
		$result = $this->getVariables('objectNames');
		$this->assertEqual(array(
			'name' => 'li3_memoize\tests\mocks\Prose',
			'method' => array('init', 'list')
		), $result);
	}

	public function testDuplicateCalls() {
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('init')
			)
		));
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('list')
			)
		));
		$result = $this->getVariables('objectNames');
		$this->assertEqual(array(
			'name' => 'li3_memoize\tests\mocks\Prose',
			'method' => array('init', 'list')
		), $result);
	}

	public function testNonFilteredInstance() {
		// Should not be filtered so same class should be returned
		$prose = new Prose();
		$oldClass = get_class($prose);
		$prose = Memoize::instance($prose);
		$this->assertEqual($oldClass, get_class($prose));
	}

	public function testFilteredInstance() {
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('slowSpeak')
			)
		));
		$prose = new Prose();
		$oldClass = get_class($prose);
		$prose = Memoize::instance($prose);
		$this->assertNotEqual($oldClass, get_class($prose));
	}
}