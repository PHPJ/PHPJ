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
    if($r->isInstantiable() && !$r->isInternal()){
      try {
        $class = $r->newInstanceWithoutConstructor();
        $this->assertInstanceOf($name, $class);
        $this->assertInstanceOf('PHPJ\Lang\Object', $class);
      } catch (\ReflectionException $e) {
        $this->markTestSkipped($e->getMessage());
      }
    }
  }

  abstract protected function getClassName();
}
