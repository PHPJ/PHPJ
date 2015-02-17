<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Tests\IO;


use PHPJ\IO\DefaultFileSystem;
use PHPJ\IO\UnixFileSystem;
use PHPJ\Tests\Test;

class DefaultFileSystemTest extends Test
{

  protected function getClassName()
  {
    return DefaultFileSystem::class;
  }

  public function testDI()
  {
    $fs = DefaultFileSystem::getFileSystem();
    $this->assertInstanceOf(UnixFileSystem::class, $fs);

    $fs2 = DefaultFileSystem::getFileSystem();
    $this->assertEquals($fs, $fs2);

  }
}