<?php
/**
 * @author ykmship@yandex-team.ru
 * Date: 30/12/14
 */

namespace PHPJ\Tests\Lang;


use PHPJ\Lang\Object;
use PHPJ\Lang\String;
use PHPJ\Tests\Test;

class StringTest extends Test
{
  const STRING_VALUE = 'Test String';

  protected function getClassName()
  {
    return 'PHPJ\Lang\String';
  }

  public function testLoad()
  {
    $this->assertTrue(class_exists($this->getClassName()));
    $this->assertInstanceOf($this->getClassName(), new String(self::STRING_VALUE));
  }

  public function testLength()
  {
    $string = new String(self::STRING_VALUE);
    $this->assertEquals(mb_strlen($string->getOriginalValue()), $string->length());
    $this->assertEquals(mb_strlen($string->getOriginalValue()), $string->length());
  }

  public function testIsEmpty()
  {
    $string = new String(self::STRING_VALUE);
    $stringEmpty = new String();
    $this->assertTrue($stringEmpty->isEmpty());
    $this->assertFalse($string->isEmpty());
  }

  public function testHashCode()
  {
    $string = new String(self::STRING_VALUE);
    $hash = $string->hashCode();
    $this->assertEquals(432811871, $hash);

    $t = microtime(true);
    $string = new String(str_repeat(self::STRING_VALUE, 1000));
    $hash = $string->hashCode();
    $this->assertEquals(3575464064, $hash);
  }

}