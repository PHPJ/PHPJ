<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Tests\Lang;


use PHPJ\Lang\NativeArray;
use PHPJ\Lang\System;
use PHPJ\Tests\Test;

class SystemTest extends Test{

  protected function getClassName()
  {
    return 'PHPJ\Lang\System';
  }

  /**
   * @dataProvider dataCopy
   */
  public function testCopy($srcPos, $destBegin, $length, $expected)
  {
    $ints = new NativeArray(4);
    $ints[0] = '1';
    $ints[1] = '2';
    $ints[2] = '3';
    $ints[3] = '4';
    $copyTo = new NativeArray(2);
    $copyTo[0] = 'a';
    $copyTo[1] = 'b';
    $res = System::arraycopy($ints, $srcPos, $copyTo, $destBegin, $length);
    $this->assertEquals($res, $copyTo);
    $this->assertEquals($expected, implode('', $res->toArray()));

  }

  public function dataCopy()
  {
    return [
      [0,0,2,'12'],
      [1,0,2,'23'],
      [2,0,2,'34'],
      [0,0,1,'1b'],
      [1,0,1,'2b'],
      [2,0,1,'3b'],
      [0,1,1,'a1'],
      [1,1,1,'a2'],
      [2,1,1,'a3'],
      [3,1,1,'a4'],
    ];
  }
}