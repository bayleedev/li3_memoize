<?php

namespace li3_analytics\extensions\adapter;

/**
 * Memoize
 *
 * This class was developed to aid in caching expensive method calls.
 */
class Memoizer extends \lithium\template\Helper {

    /**
     * This is where the results are stored.
     */
    protected $_memoizeResults = [];

    /**
     * __call
     *
     * The heart of the application. The method and params go in, the result is then cached and servered next time.
     *
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function __call($method, $params) {
        $key = $this->_getHash($params);
        $method = $this->_getMethod($method);
        if(!isset($this->_memoizeResults[$method])) {
            $this->_memoizeResults[$method] = [];
        }
        if(isset($this->_memoizeResults[$method][$key])) {
            return $this->_memoizeResults[$method][$key];
        }
        if($method === false) {
            throw new BadMethodCallException();
            return;
        }
        return ($this->_memoizeResults[$method][$key] = call_user_func_array(array($this, $method), $params));
    }

    /**
     * _getHash
     *
     * The current params go in, a unique hash comes out.
     *
     * This was put into it's own method so that it can be overwritten by your class if you'd like to improve/simplify it.
     *
     * @param mixed $data
     * @return string
     */
    protected function _getHash($data) {
        return md5(serialize(func_get_args()));
    }

    /**
     * _getMethod
     *
     * You input the "cahced" version of the method and it'll return the real method name, or false if it doesn't exist.
     *
     * This was put into it's own method so that it can be overwritten by your class if you'd like to improve/simplify it.
     *
     * @param string $method
     * @return string
     */
    protected function _getMethod($method) {
        $hasTop = (substr($method, 0, 1) == "_");
        $method = substr($method, 1);
        $methodExists = (method_exists($this, $method));
        if($hasTop && $methodExists) {
            return $method;
        }
        return false;
    }
}