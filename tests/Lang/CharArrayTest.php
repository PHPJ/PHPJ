<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Tests\Lang;


use PHPJ\Lang\CharArray;
use PHPJ\Tests\Test;

class CharArrayTest extends Test
{

  protected function getClassName()
  {
    return '\PHPJ\Lang\CharArray';
  }

  public function testNew()
  {
    $ar = new CharArray(16);
    $this->assertEquals(16, $ar->getSize());
    $this->assertEquals(16, $ar->length());
  }

  public function testFrom()
  {
    $array = CharArray::fromString('str');
    $this->assertInstanceOf(CharArray::class, $array);
    $this->assertEquals('str', (string)$array);
  }
}