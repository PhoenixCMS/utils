<?php
/**
 * @author Tomáš Blatný
 */

namespace PhoenixCMS\Utils;


class Version
{

	public function __construct()
	{
		throw new StaticClassException;
	}


	/**
	 * @param string $version1
	 * @param string $version2
	 * @return int
	 */
	public static function compareMajor($version1, $version2)
	{
		$compare = self::compare($version1, $version2);
		if (abs($compare) === 3) {
			return $compare / 3;
		} else {
			return 0;
		}
	}


	/**
	 * @param string $version1
	 * @param string $version2
	 * @return int|NULL
	 */
	public static function compareMinor($version1, $version2)
	{
		$compare = self::compare($version1, $version2);
		if (abs($compare) === 3) {
			return NULL;
		} else if (abs($compare) === 2) {
			return $compare / 1;
		} else {
			return 0;
		}
	}


	/**
	 * @param string $version1
	 * @param string $version2
	 * @return int
	 * @throws InvalidVersionException
	 */
	public static function compare($version1, $version2)
	{
		list($major1, $minor1, $patch1) = self::parse($version1);
		list($major2, $minor2, $patch2) = self::parse($version2);

		if ($major1 === '*' || $major2 === '*') {
			return 0;
		} else if ($major1 > $major2) {
			return 3;
		} else if ($major2 > $major1) {
			return -3;
		} else {
			if ($minor1 === '*' || $minor2 === '*') {
				return 0;
			} else if ($minor1 > $minor2) {
				return 2;
			} else if ($minor2 > $minor1) {
				return -2;
			} else {
				if ($patch1 === '*' || $patch2 === '*') {
					return 0;
				} else if ($patch1 > $patch2) {
					return 1;
				} else if ($patch2 > $patch1) {
					return -1;
				} else {
					return 0;
				}
			}
		}
	}


	/**
	 * @param string $version
	 * @return int[]
	 * @throws InvalidVersionException
	 */
	public static function parse($version)
	{
		$version = ltrim($version, 'v');
		$parts = explode('.', $version);
		if (count($parts) !== 3) {
			throw new InvalidVersionException('Version must contain three chunks.');
		}
		return array_map(function ($part) {
			return $part === '*' ? '*' : (int) $part;
		}, $parts);
	}


	/**
	 * @param string $version
	 * @return NULL|InvalidVersionException
	 */
	public static function validate($version)
	{
		try {
			self::parse($version);
			return NULL;
		} catch(InvalidVersionException $e) {
			return $e;
		}
	}
}
