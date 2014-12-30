<?php
/**
 * @author Yury Kozyrev <ykmship@yandex-team.ru>
 */
namespace PHPJ\Tests;

use PHPUnit_Framework_TestCase;

class AddressParserTest extends PHPUnit_Framework_TestCase
{
  public function testLoad()
  {
    $this->assertTrue(class_exists('PHPJ\Lang\Object'));
  }
}
