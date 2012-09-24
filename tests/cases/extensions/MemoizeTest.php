<?php

namespace li3_memoize\tests\cases\extensions;

use li3_memoize\extensions\Memoize;
use li3_memoize\tests\mocks\Prose;
use li3_memoize\tests\mocks\Speaker;
use lithium\data\entity\Document;
use lithium\data\entity\Record;

class MemoizeTest extends \lithium\test\Unit {

	protected $class = "li3_memoize\\extensions\\Memoize";
	protected $reflectionClass;

	/**
	 * Will return the protected/private variables
	 */
	protected function getVariable($name) {
		if(!isset($this->reflectionClass)) {
			$this->reflectionClass = new \ReflectionClass($this->class);
		}
		$prop = $this->reflectionClass->getProperty($name);
		$prop->setAccessible(true);
		return $prop->getValue($this->reflectionClass);
	}

	/**
	 * Will update the protected/private variables
	 */
	protected function setVariable($name, $value) {
		if(!isset($this->reflectionClass)) {
			$this->reflectionClass = new \ReflectionClass($this->class);
		}
		$prop = $this->reflectionClass->getProperty($name);
		$prop->setAccessible(true);
		return $prop->setValue($this->reflectionClass, $value);
	}

	/**
	 * Will setup/tear down the current object
	 */
	public function setUp() {
		$this->tearDown();
	}
	public function tearDown() {
		$this->setVariable('objectNames', array());
	}

	public function testDocumentInstance() {
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('init')
			)
		));
		$doc = new Document();
		$doc = Memoize::instance($doc, 'li3_memoize\tests\mocks\Prose');
		$this->assertEqual('li3_memoize\extensions\adapter\storage\cache\DocumentMemoizerProxy', get_class($doc));
	}

	public function testRecordInstance() {
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('init')
			)
		));
		$doc = new Record();
		$doc = Memoize::instance($doc, 'li3_memoize\tests\mocks\Prose');
		$this->assertEqual('li3_memoize\extensions\adapter\storage\cache\DocumentMemoizerProxy', get_class($doc));
	}

	public function testBasicAdd() {
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('init')
			)
		));
		$result = $this->getVariable('objectNames');
		$this->assertEqual(array(
			'li3_memoize\tests\mocks\Prose' => array(
				'init'
			)
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
		$result = $this->getVariable('objectNames');
		$this->assertEqual(array(
			'li3_memoize\tests\mocks\Prose' => array(
				'init', 'list'
			)
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
		$result = $this->getVariable('objectNames');
		$this->assertEqual(array(
			'li3_memoize\tests\mocks\Prose' => array(
				'init', 'list'
			)
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