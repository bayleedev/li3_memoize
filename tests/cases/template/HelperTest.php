<?php

namespace li3_memoize\tests\cases\template;

use li3_memoize\extensions\Memoize;
use li3_memoize\tests\mocks\Prose;

class HelperTest extends \lithium\test\Unit {

	public $class = "li3_memoize\\template\\Helper";

	public function testGetSet() {
		$name = 'lithium';
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('init')
			)
		));
		$prose = Memoize::catchHelper(new Prose);
		$prose->name = $name;
		$this->assertEqual($name, $prose->name);
	}

	public function testIsset() {
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('init')
			)
		));
		$prose = Memoize::catchHelper(new Prose);
		$this->assertFalse(isset($prose->name));
		$prose->name = 'lithium';
		$this->assertTrue(isset($prose->name));
	}

	public function testUnset() {
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('init')
			)
		));
		$prose = Memoize::catchHelper(new Prose);
		$prose->name = 'lithium';
		$this->assertTrue(isset($prose->name));
		unset($prose->name);
		$this->assertFalse(isset($prose->name));
	}

	public function testSpeedDescrease() {
		// Filtering
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('speak')
			)
		));

		// Helper
		$prose = new Prose;
		Memoize::catchHelper($prose);

		// Timing
		$times = array();

		$times[0] = microtime(true);

		$results[0] = $prose->speak('lithium');

		$times[1] = microtime(true);

		$results[0] = $prose->speak('lithium');

		$times[2] = microtime(true);

		// The first iteration needs to be MORE than a second
		$this->assertTrue(($times[1] - $times[0]) > 1);

		// The second needs to be LESS than a second
		$this->assertTrue(($times[2] - $times[1]) < 1);

		// Results should equal
		$this->assertEqual($results[0], $results[1]);
	}

	public function testSpeedSteady() {
		// Filtering
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Prose',
				'method' => array('doesNotExist')
			)
		));

		// Helper
		$prose = new Prose;
		Memoize::catchHelper($prose);

		// Timing
		$times = array();

		$times[0] = microtime(true);

		$results[0] = $prose->speak('lithium');

		$times[1] = microtime(true);

		$results[0] = $prose->speak('lithium');

		$times[2] = microtime(true);

		// The first iteration needs to be MORE than a second
		$this->assertTrue(($times[1] - $times[0]) > 1);

		// The second needs to be LESS than a second
		$this->assertTrue(($times[2] - $times[1]) < 1);

		// Results should equal
		$this->assertEqual($results[0], $results[1]);
	}

}