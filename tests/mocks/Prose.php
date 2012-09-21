<?php

namespace li3_memoize\tests\mocks;

/**
 * Mock prose object
 */
class Prose {

	/**
	 * Fast running method
	 * 
	 * @param mixed $words
	 * @return mixed The input $words
	 */
	public function speak($words) {
		return $words;
	}

	/**
	 * Slow running method
	 * 
	 * @param mixed $words
	 * @return mixed The input $words
	 */
	public function slowSpeak($words) {
		sleep(2);
		return $words;
	}
}