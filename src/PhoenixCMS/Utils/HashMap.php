<?php
/**
 * @author Tomáš Blatný
 */

namespace PhoenixCMS\Utils;

use Traversable;


class HashMap
{

	const TYPE_BOOL = 'bool';
	const TYPE_INT = 'int';
	const TYPE_FLOAT = 'float';
	const TYPE_STRING = 'string';
	const TYPE_ARRAY = 'array';
	const TYPE_OBJECT = 'object';


	/** @var array */
	private $data;

	/** @var bool */
	private $recursive;


	public function __construct(array $data, $recursive = TRUE)
	{
		$this->data = $data;
		$this->recursive = $recursive;
	}


	/**
	 * @param array $data
	 * @param bool $recursive
	 * @return HashMap
	 */
	public static function from(array $data, $recursive = TRUE)
	{
		return new self($data, $recursive);
	}


	/**
	 * @param string $key
	 * @return bool
	 */
	public function getBool($key)
	{
		return $this->get($key, self::TYPE_BOOL, FALSE);
	}


	/**
	 * @param string $key
	 * @return bool|NULL
	 */
	public function getBoolOrNull($key)
	{
		return $this->get($key, self::TYPE_BOOL, TRUE);
	}


	/**
	 * @param string $key
	 * @return int
	 */
	public function getInt($key)
	{
		return $this->get($key, self::TYPE_INT, FALSE);
	}


	/**
	 * @param string $key
	 * @return int|NULL
	 */
	public function getIntOrNull($key)
	{
		return $this->get($key, self::TYPE_INT, TRUE);
	}


	/**
	 * @param string $key
	 * @return float
	 */
	public function getFloat($key)
	{
		return $this->get($key, self::TYPE_FLOAT, FALSE);
	}


	/**
	 * @param string $key
	 * @return float|NULL
	 */
	public function getFloatOrNull($key)
	{
		return $this->get($key, self::TYPE_FLOAT, TRUE);
	}


	/**
	 * @param string $key
	 * @return string
	 */
	public function getString($key)
	{
		return $this->get($key, self::TYPE_STRING, FALSE);
	}


	/**
	 * @param string $key
	 * @return string|NULL
	 */
	public function getStringOrNull($key)
	{
		return $this->get($key, self::TYPE_STRING, TRUE);
	}


	/**
	 * @param string $key
	 * @return array|HashMap
	 */
	public function getArray($key)
	{
		return $this->get($key, self::TYPE_ARRAY, FALSE);
	}


	/**
	 * @param string $key
	 * @return array|HashMap|NULL
	 */
	public function getArrayOrNull($key)
	{
		return $this->get($key, self::TYPE_ARRAY, TRUE);
	}


	/**
	 * @param string $key
	 * @return object|HashMap
	 */
	public function getObject($key)
	{
		return $this->get($key, self::TYPE_OBJECT, FALSE);
	}


	/**
	 * @param string $key
	 * @return object|HashMap
	 */
	public function getObjectOrNull($key)
	{
		return $this->get($key, self::TYPE_OBJECT, TRUE);
	}


	/**
	 * @param string $key
	 * @param string $type
	 * @param bool $nullable
	 * @return bool|int|float|string|array|object|HashMap|NULL
	 */
	public function get($key, $type, $nullable)
	{
		if (!array_key_exists($key, $this->data)) {
			throw new InvalidTypeException("Missing '$key' key in HashMap.");
		}

		$val = $this->data[$key];

		if ($val === NULL) {
			if ($nullable) {
				return NULL;
			} else {
				throw new InvalidTypeException("Key '$key' is NULL, but required as not nullable.");
			}
		}

		switch ($type) {
			case self::TYPE_BOOL:
				return (bool) $val;
			case self::TYPE_INT:
				if (is_numeric($val)) {
					return (int) $val;
				} else {
					throw new InvalidTypeException("Key '$key': expected int, got " . gettype($val) . '.');
				}
			case self::TYPE_FLOAT:
				if (is_numeric($val)) {
					return (float) $val;
				} else {
					throw new InvalidTypeException("Key '$key': expected float, got " . gettype($val) . '.');
				}
			case self::TYPE_STRING:
				if (is_string($val) || (is_object($val) && method_exists($val, '__toString'))) {
					return (string) $val;
				} else {
					throw new InvalidTypeException("Key '$key': expected string, got " . gettype($val) . '.');
				}
			case self::TYPE_ARRAY:
				if (is_array($val) || $val instanceof Traversable) {
					return $this->recursive ? self::from((array) $val) : (array) $val;
				} else {
					throw new InvalidTypeException("Key '$key': expected array, got " . gettype($val) . '.');
				}
			case self::TYPE_OBJECT:
				if (is_object($val)) {
					return $this->recursive ? self::from((array) $val) : (object) $val;
				} else {
					throw new InvalidTypeException("Key '$key': expected object, got " . gettype($val) . '.');
				}
			default:
				throw new InvalidTypeException("Invalid type '$type'.");
		}
	}
}
