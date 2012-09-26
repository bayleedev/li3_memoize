<?php

namespace li3_memoize\tests\cases\data\entity;

use li3_memoize\extensions\Memoize;
use li3_memoize\tests\mocks\Users;
use li3_memoize\data\entity\Record;
use lithium\data\Connections;

class RecordTest extends \lithium\test\Unit {

	public $class = "li3_memoize\\data\\entity\\Record";

	public function setUp() {
		$result = Memoize::$objectNames = array();
		Connections::add('default', array(
			'type' => 'database',
			'adapter' => 'Sqlite3',
			'database' => LITHIUM_APP_PATH . '/app/libraries/li3_memoize/tests/mocks/sqlite.db'
		));
	}
	public function tearDown() {
		$result = Memoize::$objectNames = array();
	}

	public function testGetRecord() {
		$name = 'lithium';
		$user = new Record(array(
			'model' => 'li3_memoize\tests\mocks\Users',
			'data' => array(
				'name' => $name
			)
		));
		$this->assertEqual($name, $user->name);		
	}
	public function testSpeedDescrease() {
		// Filtering
		Memoize::add(array(
			array(
				'name' => 'li3_memoize\tests\mocks\Users',
				'method' => array('name')
			)
		));

		// Model
		$name = 'lithium';
		$user = new Record(array(
			'model' => 'li3_memoize\tests\mocks\Users',
			'data' => array(
				'name' => $name
			)
		));

		// Timing
		$times = array();

		$times[0] = microtime(true);

		$results[0] = $user->name();

		$times[1] = microtime(true);

		$results[1] = $user->name();

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
				'name' => 'li3_memoize\tests\mocks\Users',
				'method' => array('doesNotExist')
			)
		));

		// Model
		$name = 'lithium';
		$user = new Record(array(
			'model' => 'li3_memoize\tests\mocks\Users',
			'data' => array(
				'name' => $name
			)
		));

		// Timing
		$times = array();

		$times[0] = microtime(true);

		$results[0] = $user->name();

		$times[1] = microtime(true);

		$results[1] = $user->name();

		$times[2] = microtime(true);

		// The first iteration needs to be MORE than a second
		$this->assertTrue(($times[1] - $times[0]) > 1);

		// The second needs to be MORE than a second
		$this->assertFalse(($times[2] - $times[1]) < 1);

		// Results should equal
		$this->assertEqual($results[0], $results[1]);
	}
}