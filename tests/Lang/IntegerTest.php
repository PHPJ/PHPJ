<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Tests\Lang;


use PHPJ\Lang\Integer;
use PHPJ\Tests\Test;

class IntegerTest extends Test
{


  protected function getClassName()
  {
    return '\PHPJ\Lang\Integer';
  }

  public function testValues()
  {
    $int = new Integer(77);
    $this->assertEquals(77, $int->intValue());
    $int = new Integer("77", 8);
    $this->assertEquals(63, $int->intValue());

    $int = new Integer(77);
    $int2 = new Integer(77);
    //$this->assertEquals(154, $int+$int2);

  }
}