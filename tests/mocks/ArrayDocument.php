<?php

namespace li3_memoize\tests\mocks;

class AraryDocument implements \Iterator, \ArrayAccess {

	protected $items = array();

	/**
	 * Iterator::current — Return the current element
	 */
	public function current() {
		return current($this->items);
	}

	/**
	 * Iterator::key — Return the key of the current element
	 */
	public function key() {
		return key($this->items);
	}

	/**
	 * Iterator::next — Move forward to next element
	 */
	public function next() {
		return next($this->items);
	}

	/**
	 * Iterator::rewind — Rewind the Iterator to the first element
	 */
	public function rewind() {
		return rewind($this->items);
	}

	/**
	 * Iterator::valid — Checks if current position is valid
	 */
	public function valid() {
		return isset($this->items[$this->key()]);
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