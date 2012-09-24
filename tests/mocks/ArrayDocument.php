<?php

namespace li3_memoize\tests\mocks;

class ArrayDocument implements \Iterator, \ArrayAccess {

	public $items = array();
	public $position;

	/**
	 * Iterator::current — Return the current element
	 */
	public function current() {
		return $this->items[$this->position];
	}

	/**
	 * Iterator::key — Return the key of the current element
	 */
	public function key() {
		return $this->position;
	}

	/**
	 * Iterator::next — Move forward to next element
	 */
	public function next() {
		++$this->position;
		return null;
	}

	/**
	 * Iterator::rewind — Rewind the Iterator to the first element
	 */
	public function rewind() {
		$this->position = 0;
		return null;
	}

	/**
	 * Iterator::valid — Checks if current position is valid
	 */
	public function valid() {
		return isset($this->items[$this->position]);
	}

	/**
	 * ArrayAccess::offsetExists — Whether a offset exists
	 */
	public function offsetExists($key) {
		return isset($this->items[$key]);
	}

	/**
	 * ArrayAccess::offsetGet — Offset to retrieve
	 */
	public function offsetGet($key) {
		return $this->items[$key];
	}

	/**
	 * ArrayAccess::offsetSet — Offset to set
	 */
	public function offsetSet($key, $value) {
		return $this->items[$key] = $value;
	}

	/**
	 * ArrayAccess::offsetUnset — Offset to unset
	 */
	public function offsetUnset($key) {
		unset($this->items[$key]);
		return null;
	}
}