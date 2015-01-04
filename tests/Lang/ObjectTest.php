<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Tests\Lang;


use PHPJ\Lang\Object;
use PHPJ\Tests\Test;

class ObjectTest extends Test
{
  protected function getClassName()
  {
    return 'PHPJ\Lang\Object';
  }

  public function testLoad()
  {
    $this->assertTrue(class_exists($this->getClassName()));
    $this->assertInstanceOf($this->getClassName(), new \PHPJ\Lang\Object());
  }

  public function testClone()
  {
    $object = new Object();
    $clone = $object->getClone();
    $this->assertTrue($clone !== $object);
    $this->assertTrue($clone == $object);

    $clone = clone $object;
    $this->assertTrue($clone !== $object);
    $this->assertTrue($clone == $object);
  }

  public function testEquals()
  {
    $object = new Object();
    $objectCopy = $object;
    $clone = clone $object;

    $this->assertTrue($object->equals($object));
    $this->assertTrue($object->equals($objectCopy));
    $this->assertFalse($object->equals($clone));
    $this->assertFalse($object->equals(new Object()));
    $this->assertFalse($object->equals(null));
  }

  public function testGetClass()
  {
    $object = new Object();
    $this->assertEquals($this->getClassName(), $object->getClass());
  }

  public function testGetClassReflection()
  {
    $object = new Object();
    $r = $object->getClassReflection();

    $this->assertEquals($this->getClassName(), $r->getName());
  }

  public function testHashCode()
  {
    $object = new Object();
    $this->assertEquals(spl_object_hash($object), $object->hashCode());
  }

  public function testToString()
  {
    $object = new Object();

    $this->assertInstanceOf('PHPJ\Lang\String', $object->toString());

    $this->assertEquals($object->toString()->getOriginalValue(), $object->toString()->getOriginalValue());
    $this->assertEquals($object->toString()->getOriginalValue(), (string)$object);
    $this->assertNotEquals($object->toString()->getOriginalValue(), $object->getClone()->toString()->getOriginalValue());

    $this->assertStringStartsWith(get_class($object), $object->toString()->getOriginalValue());
    $this->assertStringEndsWith(spl_object_hash($object), $object->toString()->getOriginalValue());
  }
}