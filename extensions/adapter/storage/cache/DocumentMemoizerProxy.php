<?php

namespace li3_memoize\extensions\adapter\storage\cache;

/**
 * DocumentMemoizerProxy
 *
 * Extends MemoizerProxy but adds Document functionality such as Iteration and ArrayAccess
 */
class DocumentMemoizerProxy extends MemoizerProxy implements \Iterator, \ArrayAccess {

	/**
	 * Iterator::current — Return the current element
	 */
	public function current() {
		return $this->helper->current();
	}

	/**
	 * Iterator::key — Return the key of the current element
	 */
	public function key() {
		return $this->helper->key();
	}

	/**
	 * Iterator::next — Move forward to next element
	 */
	public function next() {
		return $this->helper->next();
	}

	/**
	 * Iterator::rewind — Rewind the Iterator to the first element
	 */
	public function rewind() {
		return $this->helper->rewind();
	}

	/**
	 * Iterator::valid — Checks if current position is valid
	 */
	public function valid() {
		return $this->helper->valid();
	}

	/**
	 * ArrayAccess::offsetExists — Whether a offset exists
	 */
	public function offsetExists($key) {
		return $this->helper->offsetExists($key);
	}

	/**
	 * ArrayAccess::offsetGet — Offset to retrieve
	 */
	public function offsetGet($key) {
		return $this->helper->offsetGet($key);
	}

	/**
	 * ArrayAccess::offsetSet — Offset to set
	 */
	public function offsetSet($key, $value) {
		return $this->helper->offsetSet($key, $value);
	}

	/**
	 * ArrayAccess::offsetUnset — Offset to unset
	 */
	public function offsetUnset($key) {
		return $this->helper->offsetUnset($key);
	}
}