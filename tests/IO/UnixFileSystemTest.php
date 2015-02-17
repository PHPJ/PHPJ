<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Tests\IO;


use PHPJ\IO\DefaultFileSystem;
use PHPJ\IO\File;
use PHPJ\IO\UnixFileSystem;
use PHPJ\Lang\String;
use PHPJ\Tests\Test;

class UnixFileSystemTest extends Test
{
  /**
   * @var UnixFileSystem
   */
  protected $fs;

  protected function setUp()
  {
    $this->fs = DefaultFileSystem::getFileSystem();
  }

  protected function getClassName()
  {
    return UnixFileSystem::class;
  }

  public function testNormalize()
  {
    $file = new File(__FILE__);
    $path = $this->fs->normalize(new String("////path////to"));
    $this->assertInstanceOf(String::class, $path);
    $this->assertEquals('/path/to', (string)$path);

    //var_dump($file->getAbsolutePath());
  }

  /**
   * @dataProvider dataResolve
   */
  public function testResolve($parent, $child, $expected)
  {
    $this->assertEquals($expected, $this->fs->resolve(new String($parent), new String($child))->getOriginalValue());
  }

  public function dataResolve()
  {
    return [
      ["","",""],
      ["/","/","/"],
      ["","path/to","/path/to"],
      ["","path/to/","/path/to/"],
      ["","/path/to/","/path/to/"],
      ["/","/path/to/","/path/to/"],
      ["/","path/to/","/path/to/"],
      ["/path","","/path"],
      ["/path","/","/path/"],
      ["first/","/path/to/","first//path/to/"],
      ["/first/","/path/to/","/first//path/to/"],
    ];
  }

  public function testFromURI()
  {
    $res = $this->fs->fromURIPath(new String("/"));
    $this->assertEquals("/", (string)$res);
    $res = $this->fs->fromURIPath(new String("/uri"));
    $this->assertEquals("/uri", (string)$res);
    $res = $this->fs->fromURIPath(new String("/uri/"));
    $this->assertEquals("/uri", (string)$res);
  }
}