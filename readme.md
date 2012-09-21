# Helper caching plugin for [Lithium PHP](http://lithify.me)

Will aid in the caching of expensive helpers.

[![Build Status](https://secure.travis-ci.org/BlaineSch/li3_memoize.png?branch=master)](http://travis-ci.org/BlaineSch/li3_memoize)

## Installation

__Submodule__

From the root of your app run `git submodule add git://github.com/BlaineSch/li3_memoize.git libraries/li3_memoize`

***

__Clone Directly__

From your apps `libraries` directory run `git clone git://github.com/BlaineSch/li3_memoize.git`

## Usage

### Load the plugin

Add the plugin to be loaded with Lithium's autoload magic

In `app/config/bootstrap/libraries.php` add:

~~~ php
<?php
	Libraries::add('li3_memoize');
?>
~~~
