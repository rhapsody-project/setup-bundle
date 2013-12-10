<?php
/* Copyright (c) 2013 Rhapsody Project
 *
 * Licensed under the MIT License (http://opensource.org/licenses/MIT)
 *
 * Permission is hereby granted, free of charge, to any
 * person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the
 * Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice
 * shall be included in all copies or substantial portions of
 * the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY
 * KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
 * OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Rhapsody\SetupBundle\Converter;

class ConverterFactory
{

	/**
	 * Instance of the <tt>ConverterFactory</tt>
	 * @property ConverterFactory
	 * @access private
	 */
	private static $_instance = null;

    /**
     * Array of converters, mapped to their converter class.
     * @static
     * @property array
     * @access private
     */
    private static $convertersMap = array(
    	'bool' => 'Rhapsody\SetupBundle\Type\Boolean',
    	'boolean' => 'Rhapsody\SetupBundle\Type\Boolean',
    	'date' => 'Rhapsody\SetupBundle\Type\Date',
    	'double' => 'Rhapsody\SetupBundle\Type\Numeric',
    	'float' => 'Rhapsody\SetupBundle\Type\Numeric',
    	'int' => 'Rhapsody\SetupBundle\Type\Numeric',
    	'integer' => 'Rhapsody\SetupBundle\Type\Numeric',
    	'null' => 'Rhapsody\SetupBundle\Type\NullValue',
    	'string' => 'Rhapsody\SetupBundle\Type\String',
    	'text' => 'Rhapsody\SetupBundle\Type\String',
    );

    /**
     * An associative array that tracks an array of constructor arguments mapped
     * to the converter's name.
     * @static
     * @property array
     * @access private
     */
    private static $converterConstructorArgs = array();

    /**
     * Array of instantiated converter classes.
     * @static
     * @property array
     * @access private
     */
    private static $converters = array();

	private function __construct()
	{
		//
	}

	/**
	 *
	 * @param unknown $value
	 * @return Ambigous <boolean, string>|boolean
	 * @see Rhapsody\SetupBundle\Type\IType
	 */
	public function convert($value, $type = null, array $attributes = array())
	{
		if (empty($type)) {
			$type = $this->getType($value, $attributes);
		}
		$converter = self::getConverter($type);
		return $converter->convert($value, $attributes);
	}

	/**
	 * Get a Converter instance.
	 *
	 * @param string $converter The type name.
	 * @return Rhapsody\SetupBundle\Type\IType $converter
	 * @throws InvalidArgumentException
	 */
	public static function getConverter($converter)
	{
		if (!isset(self::$convertersMap[$converter])) {
			throw new \InvalidArgumentException(sprintf('Invalid converter specified "%s".', $converter));
		}
		if ( ! isset(self::$converters[$converter])) {
			$className = self::$convertersMap[$converter];
			if (!empty(self::$converterConstructorArgs[$converter])) {
				$class = new \ReflectionClass($className);
				$args = self::$converterConstructorArgs[$converter];
				self::$converters[$converter] = $class->newInstanceArgs($args);
			}
			else {
				self::$converters[$converter] = new $className;
			}
		}
		return self::$converters[$converter];
	}

	/**
	 * Get the converters array map which holds all registered converters and
	 * the corresponding converter class
	 *
	 * @return array $convertersMap
	 */
	public static function getConvertersMap()
	{
		return self::$convertersMap;
	}

	/**
	 *
	 * @param unknown $input
	 * @return string
	 */
	public function getType($input, array $attributes = array())
	{
		$booleans = array('true', 'yes', 'false', 'no');
		if (!empty($input)) {
			// ** If the $value is numeric, we can determine whether it is an integer, or a float...
			if (is_numeric($input)) {
				return is_int($input + 0) ? 'int' : 'float';
			}

			// ** A value is boolean if it satisfies the is_bool test, or the value is `true' or `false'
			if (is_bool($input) || in_array($input, $booleans)) {
				return 'bool';
			}
			return 'string';
		}
		return 'null';
	}

	public static function getInstance()
	{
		if (empty(self::$_instance)) {
			self::$_instance = new ConverterFactory();
		}
		return self::$_instance;
	}

	/**
	 * Checks to see if support exists for a given converter, by name.
	 *
	 * @static
	 * @param string $name Name of the converter
	 * @return boolean <tt>true</tt> if converter is supported; <tt>false</tt>
	 * 		otherwise.
	 */
	public static function hasConverter($name)
	{
		return isset(self::$convertersMap[$name]);
	}

	/**
	 * Register a new converter in the map of converters.
	 *
	 * @param string $name The name of the converter.
	 * @param string $class The class name.
	 * @param array $args constructor arguments.
	 */
	public static function registerConverter($name, $class, array $args = array())
	{
		self::$convertersMap[$name] = $class;
		if (!empty($args)) {
			self::$converterConstructorArgs[$name] = $args;
		}
	}
}