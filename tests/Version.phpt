<?php

require __DIR__ . "/bootstrap.php";

use PhoenixCMS\Utils\Version;
use Tester\Assert;

Assert::equal([1,2,3], Version::parse('v1.2.3'));
