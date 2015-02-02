<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */
namespace PHPJ\Tests;

use PHPUnit_Framework_TestCase;

abstract class Test extends PHPUnit_Framework_TestCase
{
  public function testLoad()
  {
    $name = $this->getClassName();
    $this->assertTrue(class_exists($name));
    $r = new \ReflectionClass($name);
    if(PHP_VERSION_ID >= 50600 || !$r->isInternal()){
      $class = $r->newInstanceWithoutConstructor();
      $this->assertInstanceOf($name, $class);
      $this->assertInstanceOf('PHPJ\Lang\Object', $class);
    }
  }

  abstract protected function getClassName();
}
