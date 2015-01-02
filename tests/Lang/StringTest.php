<?php
/**
 * @author ykmship@yandex-team.ru
 * Date: 30/12/14
 */

namespace PHPJ\Tests\Lang;


use PHPJ\Lang\Object;
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
    $this->assertInstanceOf($this->getClassName(), new \PHPJ\Lang\String(self::STRING_VALUE));
  }

}