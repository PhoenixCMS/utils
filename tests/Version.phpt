<?php

require __DIR__ . "/bootstrap.php";

use PhoenixCMS\Utils\Version;
use Tester\Assert;

Assert::equal([1, 2, 3], Version::parse('v1.2.3'));
Assert::equal([4, 5, 6], Version::parse('4.5.6'));
Assert::equal([7, 8, 9], Version::parse('v 7.8.9'));
Assert::truthy(Version::validate('1.2.3.4'));
Assert::truthy(Version::validate('1.2.3a'));
Assert::truthy(Version::validate('1.2'));
