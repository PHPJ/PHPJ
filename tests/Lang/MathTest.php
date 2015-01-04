<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Tests\Lang;


use PHPJ\Lang\Math;
use PHPJ\Tests\Test;

class MathTest extends Test
{

  protected function getClassName()
  {
    return '\PHPJ\Lang\Math';
  }

  public function testMax()
  {
    $this->assertEquals(0, Math::max(0,-10));
    $this->assertEquals(0, Math::max(-10, 0));
  }

  public function testMin()
  {
    $this->assertEquals(10, Math::max(0,10));
    $this->assertEquals(10, Math::max(10, 0));
  }


}