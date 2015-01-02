<?php
/**
 * @author ykmship@yandex-team.ru
 * Date: 30/12/14
 */

namespace PHPJ\Tests\Lang;


use PHPJ\Lang\Object;
use PHPJ\Tests\Test;

class ObjectTest extends Test
{
  public function testLoad()
  {
    $this->assertTrue(class_exists('PHPJ\Lang\Object'));
    $this->assertInstanceOf('PHPJ\Lang\Object', new \PHPJ\Lang\Object());
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
    $this->assertEquals('PHPJ\Lang\Object', $object->getClass());
  }

  public function testGetClassReflection()
  {
    $object = new Object();
    $r = $object->getClassReflection();

    $this->assertEquals('PHPJ\Lang\Object', $r->getName());
  }

  public function testHashCode()
  {
    $object = new Object();
    $this->assertEquals(spl_object_hash($object), $object->hashCode());
  }

  public function testToString()
  {
    $object = new Object();

    $this->assertEquals($object->toString(), $object->toString());
    $this->assertEquals($object->toString(), (string)$object);
    $this->assertNotEquals($object->toString(), $object->getClone()->toString());

    $this->assertStringStartsWith(get_class($object), $object->toString());
    $this->assertStringEndsWith(spl_object_hash($object), $object->toString());
  }
}