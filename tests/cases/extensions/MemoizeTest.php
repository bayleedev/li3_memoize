<?php

namespace li3_memoize\tests\cases\extensions;

use li3_memoize\extensions\Memoize;
use li3_memoize\tests\mocks\Prose;

class MemoizeTest extends \lithium\test\Unit {

	public $class = "li3_memoize\\extensions\\Memoize";

	public function setUp() {
		$result = Memoize::$objectNames = array();
	}
	public function tearDown() {
		$result = Memoize::$objectNames = array();
	}

	public function testBasicAdd() {
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('init')
			)
		));
		$result = Memoize::$objectNames;
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
		$result = Memoize::$objectNames;
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
		$result = Memoize::$objectNames;
		$this->assertEqual(array(
			'li3_memoize\tests\mocks\Prose' => array(
				'init', 'list'
			)
		), $result);
	}

	public function testNonHelper() {
		// Should not be filtered so same class should be returned
		$prose = new Prose();
		$oldClass = get_class($prose);
		$prose = Memoize::catchHelper($prose);
		$this->assertEqual($oldClass, get_class($prose));
	}

	public function testHelper() {
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('slowSpeak')
			)
		));
		$prose = new Prose();
		$oldClass = get_class($prose);
		$prose = Memoize::catchHelper($prose);
		$this->assertNotEqual($oldClass, get_class($prose));
	}

	public function testModelDocument() {
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('slowSpeak')
			)
		));
		$request = array(
			'name' = 'lithium\data\entity\Document',
			'options' => array(
				'model' = 'li3_memoize\tests\mocks\Prose'
			)
		);
		$expected = array(
			'name' = 'li3_memoize\data\entity\Document',
			'options' => array(
				'model' = 'li3_memoize\tests\mocks\Prose'
			)
		);
		$this->assertEqual($expected, Memoize::catchModel($his, $request), $this);
	}

	public function testModelRecord() {
		$request = array(
			'name' = 'lithium\data\entity\Record',
			'options' => array(
				'model' = 'li3_memoize\tests\mocks\Prose'
			)
		);
		$expected = array(
			'name' = 'li3_memoize\data\entity\Record',
			'options' => array(
				'model' = 'li3_memoize\tests\mocks\Prose'
			)
		);
		$this->assertEqual($expected, Memoize::catchModel($his, $request), $this);
	}

	public function testNonModelDocument() {
		$request = array(
			'name' = 'lithium\data\entity\Document',
			'options' => array(
				'model' = 'li3_memoize\tests\mocks\Prose'
			)
		);
		$expected = array(
			'name' = 'lithium\data\entity\Document',
			'options' => array(
				'model' = 'li3_memoize\tests\mocks\Prose'
			)
		);
		$this->assertEqual($expected, Memoize::catchModel($his, $request), $this);
	}

	public function testNonModelRecord() {
		$request = array(
			'name' = 'lithium\data\entity\Record',
			'options' => array(
				'model' = 'li3_memoize\tests\mocks\Prose'
			)
		);
		$expected = array(
			'name' = 'lithium\data\entity\Record',
			'options' => array(
				'model' = 'li3_memoize\tests\mocks\Prose'
			)
		);
		$this->assertEqual($expected, Memoize::catchModel($his, $request), $this);
	}

	public function testSameHashArgs() {
		$args = array(
			'String',
			35,
			M_PI,
			new stdClass
		);
		$expected = Memoize::hashArgs($args);
		$results = Memoize::hashArgs($args);
		$this->assertEqual($expected, $result);
	}

	public function testDifferentHashArgs() {
		$objs = array(
			new stdClass,
			new stdClass
		);
		$first = Memoize::hashArgs(array(
			'String',
			35,
			M_PI,
			$objs[0]
		));
		$second = Memoize::hashArgs(array(
			'String',
			35,
			M_PI,
			$objs[1]
		));
		$this->assertEqual($first, $second);
	}
}