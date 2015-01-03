<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */
namespace PHPJ\Tests;

use PHPUnit_Framework_TestCase;

abstract class Test extends PHPUnit_Framework_TestCase
{

  abstract protected function getClassName();
}
