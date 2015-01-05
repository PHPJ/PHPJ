<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Tests\Lang;


use PHPJ\Lang\ObjectClass;
use PHPJ\Tests\Test;

class ObjectTest extends Test
{
  protected function getClassName()
  {
    return 'PHPJ\Lang\ObjectClass';
  }

  public function testClone()
  {
    $object = new ObjectClass();
    $clone = $object->getClone();
    $this->assertTrue($clone !== $object);
    $this->assertTrue($clone == $object);

    $clone = clone $object;
    $this->assertTrue($clone !== $object);
    $this->assertTrue($clone == $object);
  }

  public function testEquals()
  {
    $object = new ObjectClass();
    $objectCopy = $object;
    $clone = clone $object;

    $this->assertTrue($object->equals($object));
    $this->assertTrue($object->equals($objectCopy));
    $this->assertFalse($object->equals($clone));
    $this->assertFalse($object->equals(new ObjectClass()));
    $this->assertFalse($object->equals(null));
  }

  public function testGetClass()
  {
    $object = new ObjectClass();
    $this->assertEquals($this->getClassName(), $object->getClass());
  }

  public function testGetClassReflection()
  {
    $object = new ObjectClass();
    $r = $object->getClassReflection();

    $this->assertEquals($this->getClassName(), $r->getName());
  }

  public function testHashCode()
  {
    $object = new ObjectClass();
    $this->assertEquals(spl_object_hash($object), $object->hashCode());
  }

  public function testToString()
  {
    $object = new ObjectClass();

    $this->assertInstanceOf('PHPJ\Lang\String', $object->toString());

    $this->assertEquals($object->toString()->getOriginalValue(), $object->toString()->getOriginalValue());
    $this->assertEquals($object->toString()->getOriginalValue(), (string)$object);
    $this->assertNotEquals($object->toString()->getOriginalValue(), $object->getClone()->toString()->getOriginalValue());

    $this->assertStringStartsWith(get_class($object), $object->toString()->getOriginalValue());
    $this->assertStringEndsWith(spl_object_hash($object), $object->toString()->getOriginalValue());
  }
}