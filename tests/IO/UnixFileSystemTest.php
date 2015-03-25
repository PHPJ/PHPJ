<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Tests\IO;


use PHPJ\IO\DefaultFileSystem;
use PHPJ\IO\File;
use PHPJ\IO\UnixFileSystem;
use PHPJ\Lang\String;
use PHPJ\Lang\System;
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

  public function testGet()
  {
    $this->assertEquals(DIRECTORY_SEPARATOR, $this->fs->getSeparator());
    $this->assertEquals(PATH_SEPARATOR, $this->fs->getPathSeparator());
  }

  public function testPrefixPath()
  {
    $this->assertEquals(0, $this->fs->prefixLength());
    $this->assertEquals(1, $this->fs->prefixLength(new String("/")));
    $this->assertEquals(1, $this->fs->prefixLength(new String("/tmp")));
    $this->assertEquals(0, $this->fs->prefixLength(new String("composer.json")));
  }

  public function testGetDefaultParent()
  {
    $this->assertEquals("/", (string)$this->fs->getDefaultParent());
  }

  public function testIsAbsolute()
  {
    $this->assertTrue($this->fs->isAbsolute(new File("/tmp")));
    $this->assertFalse($this->fs->isAbsolute(new File("composer.json")));
  }

  public function testResolveFile()
  {
    $this->assertEquals("/tmp", (string)$this->fs->resolveFile(new File("/tmp")));
    $this->assertEquals(System::getProperty("user.dir")."/composer.json", (string)$this->fs->resolveFile(new File("composer.json")));
    $this->assertEquals(System::getProperty("user.dir")."/nonexists", (string)$this->fs->resolveFile(new File("nonexists")));
  }

  public function testCanonicalize()
  {
    $this->assertEquals(System::getProperty("user.dir")."/composer.json", (string)$this->fs->canonicalize(new String("composer.json")));
  }

  public function testGetBooleanAttrs()
  {
    $this->assertEquals(0, $this->fs->getBooleanAttributes(new File("nonexists")));
    $this->assertEquals(
      octdec(substr(sprintf('%o', fileperms("composer.json")), -4)),
      $this->fs->getBooleanAttributes(new File("composer.json"))
    );
  }
}