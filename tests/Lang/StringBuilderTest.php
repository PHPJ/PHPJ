<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Tests\Lang;


use PHPJ\Lang\StringBuilder;
use PHPJ\Tests\Test;

class StringBuilderTest extends Test{

  protected function getClassName()
  {
    return '\PHPJ\Lang\StringBuilder';
  }

  public function testAppend()
  {
    $sb = new StringBuilder();
    $sb->append('My');
    $sb->append(' ');
    $sb->append('Value');
    $this->assertEquals("My Value", $sb->toString()->getOriginalValue());
  }
}