<?php
/**
 * @author Tomáš Blatný
 */

use PhoenixCMS\Utils\HashMap;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


class DummyString
{

	public function __toString()
	{
		return 'dummy';
	}
}


$hashMap = HashMap::from([
	'bool1' => TRUE,
	'bool2' => 'abc',
	'bool3' => FALSE,
	'int1' => 15,
	'int2' => '123',
	'intX' => 'abc',
	'float1' => 1.5,
	'float2' => 1.23,
	'floatX' => 'abc',
	'string1' => 'abc',
	'string2' => new DummyString,
	'stringX' => [],
	'array1' => [],
	'arrayX' => new stdClass,
	'object1' => $object1Val = new stdClass,
	'objectX' => [],
	'boolN' => NULL,
	'intN' => NULL,
	'floatN' => NULL,
	'stringN' => NULL,
	'arrayN' => NULL,
	'objectN' => NULL,
], FALSE);

Assert::equal(TRUE, $hashMap->getBool('bool1'));
Assert::equal(TRUE, $hashMap->getBool('bool2'));
Assert::equal(FALSE, $hashMap->getBool('bool3'));
Assert::equal(15, $hashMap->getInt('int1'));
Assert::equal(123, $hashMap->getInt('int2'));
Assert::equal(1.5, $hashMap->getFloat('float1'));
Assert::equal(1.23, $hashMap->getFloat('float2'));
Assert::equal('abc', $hashMap->getString('string1'));
Assert::equal('dummy', $hashMap->getString('string2'));
Assert::equal([], $hashMap->getArray('array1'));
Assert::equal($object1Val, $hashMap->getObject('object1'));

// invalid types
foreach (['int', 'float', 'string', 'array', 'object'] as $key) {
	Assert::exception(function () use ($hashMap, $key) {
		$hashMap->get($key . 'X', $key, FALSE);
	}, 'PhoenixCMS\Utils\InvalidTypeException');
}

// nullable and not nullable fields
foreach (['bool', 'int', 'float', 'string', 'array', 'object'] as $key) {
	Assert::null($hashMap->get($key . 'N', $key, TRUE));
	Assert::exception(function () use ($hashMap, $key) {
		$hashMap->get($key . 'N', $key, FALSE);
	}, 'PhoenixCMS\Utils\InvalidTypeException');
}

$object = new stdClass;
$object->test = 1;

$hashMap = HashMap::from([
	'array1' => [
		'test' => 1,
	],
	'object1' => $object,
], TRUE);

Assert::type('PhoenixCMS\Utils\HashMap', $arrayHashMap = $hashMap->getArray('array1'));
Assert::type('PhoenixCMS\Utils\HashMap', $objectHashMap = $hashMap->getObject('object1'));

Assert::equal(1, $arrayHashMap->getInt('test'));
Assert::equal(1, $objectHashMap->getInt('test'));
