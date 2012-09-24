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
		return $this->_object->current();
	}

	/**
	 * Iterator::key — Return the key of the current element
	 */
	public function key() {
		return $this->_object->key();
	}

	/**
	 * Iterator::next — Move forward to next element
	 */
	public function next() {
		return $this->_object->next();
	}

	/**
	 * Iterator::rewind — Rewind the Iterator to the first element
	 */
	public function rewind() {
		return $this->_object->rewind();
	}

	/**
	 * Iterator::valid — Checks if current position is valid
	 */
	public function valid() {
		return $this->_object->valid();
	}

	/**
	 * ArrayAccess::offsetExists — Whether a offset exists
	 */
	public function offsetExists($key) {
		return $this->_object->offsetExists($key);
	}

	/**
	 * ArrayAccess::offsetGet — Offset to retrieve
	 */
	public function offsetGet($key) {
		return $this->_object->offsetGet($key);
	}

	/**
	 * ArrayAccess::offsetSet — Offset to set
	 */
	public function offsetSet($key, $value) {
		return $this->_object->offsetSet($key, $value);
	}

	/**
	 * ArrayAccess::offsetUnset — Offset to unset
	 */
	public function offsetUnset($key) {
		return $this->_object->offsetUnset($key);
	}
}