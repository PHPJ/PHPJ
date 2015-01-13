<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Tests\Util;

use PHPJ\Lang\String;
use PHPJ\Tests\Test;
use PHPJ\Util\Arrays;

class ArraysTest extends Test
{

  protected function getClassName()
  {
    return '\PHPJ\Util\Arrays';
  }

  public function testCopyOfArray()
  {
    $this->performArrayTest(array_fill(0, 10, 'value'));
    $this->performArrayTest(\SplFixedArray::fromArray(array_fill(0, 10, 'value')));
  }

  protected function performArrayTest($array)
  {
    $this->assertEquals(10, count($array));
    $this->assertEquals(5, count(Arrays::copyOf($array, 5)));
    $this->assertEquals(10, count($array));
    foreach (Arrays::copyOf($array, 5) as $key => $value) {
      $this->assertEquals('value', $value);
    }

    $this->assertEquals(0, count(Arrays::copyOf($array, 0)));

    $this->assertEquals(20, count(Arrays::copyOf($array, 20)));
    foreach (Arrays::copyOf($array, 20) as $key => $value) {
      $this->assertEquals(($key < 10) ? 'value' : null, $value);
    }
  }

  public function testCopyOfString()
  {
    $string = 'str12str12';
    $this->performTestString($string);
    $this->performTestString(new String($string));
  }

  protected function performTestString($string)
  {
    $this->assertEquals(10, mb_strlen((string)$string));
    $this->assertEquals(5, mb_strlen(Arrays::copyOf($string, 5)));
    $this->assertEquals(0, mb_strlen(Arrays::copyOf($string, 0)));
    $this->assertEquals(20, mb_strlen(Arrays::copyOf($string, 20)));
  }

  //  public function testPerformance()
  //  {
  //    $array  = 'str12str12';
  //
  //    $t = microtime(true);
  //    for($i = 0; $i <1000; $i++){
  //      Arrays::copyOf($array, 20);
  //    }
  //    var_dump(microtime(true) - $t);
  //
  //    $t = microtime(true);
  //    for($i = 0; $i < 1000; $i++){
  //      Arrays::_copyOf($array, 20);
  //    }
  //    var_dump(microtime(true) - $t);
  //  }
}