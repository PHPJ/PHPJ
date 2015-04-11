<?php
/**
 * PHP Version 5
 *
 * @category  H24
 * @package   
 * @author    "Yury Kozyrev" <yury.kozyrev@home24.de>
 * @copyright 2015 Home24 GmbH
 * @license   Proprietary license.
 * @link      http://www.home24.de
 */

namespace PHPJ\Tests\IO;


use PHPJ\IO\File;
use PHPJ\Lang\System;
use PHPJ\Tests\Test;

class FileTest  extends Test
{

  /**
   * @var File
   */
  protected $dir;

  /**
   * @var File
   */
  protected $dirAbs;

  /**
   * @var File
   */
  protected $file;

  /**
   * @var File
   */
  protected $fileAbs;

  /**
   * @var File
   */
  protected $fileRoot;

  protected function getClassName()
  {
    return File::class;
  }

  public function setUp()
  {
    $this->dir = new File("tests/IO");
    $this->dirAbs = new File(__DIR__);
    $this->file = new File("tests/IO/FileTest.php");
    $this->fileAbs = new File(__FILE__);
    $this->fileRoot = new File("composer.json");
  }

  public function testGetPrefixLen()
  {
    $this->assertEquals(0, $this->file->getPrefixLength());
  }

  public function testGetName()
  {
    $this->assertEquals("IO", (string)$this->dir->getName());
    $this->assertEquals("IO", (string)$this->dirAbs->getName());
    $this->assertEquals("FileTest.php", (string)$this->file->getName());
    $this->assertEquals("FileTest.php", (string)$this->fileAbs->getName());
    $this->assertEquals("composer.json", (string)$this->fileRoot->getName());
  }

  public function testGetPath()
  {
    $this->assertEquals("tests/IO", (string)$this->dir->getPath());
    $this->assertEquals(__DIR__, (string)$this->dirAbs->getPath());
    $this->assertEquals("tests/IO/FileTest.php", (string)$this->file->getPath());
    $this->assertEquals(__FILE__, (string)$this->fileAbs->getPath());
    $this->assertEquals("composer.json", (string)$this->fileRoot->getPath());
  }

  public function testExists()
  {
    $this->assertTrue($this->file->exists());
    $this->assertTrue($this->fileAbs->exists());
    $this->assertTrue($this->dir->exists());
    $this->assertTrue($this->dirAbs->exists());
    $this->assertTrue($this->fileRoot->exists());
  }

  public function testGetParent()
  {
    $this->assertEquals("tests", (string)$this->dir->getParent());
    $this->assertEquals(str_replace("/IO", "", __DIR__), (string)$this->dirAbs->getParent());
    $this->assertEquals("tests/IO", (string)$this->file->getParent());
    $this->assertEquals(__DIR__, (string)$this->fileAbs->getParent());
    $this->assertNull($this->fileRoot->getParent());
  }

  public function testGetParentFile()
  {
    $this->assertInstanceOf(File::class, $this->dir->getParentFile());
    $this->assertEquals("tests", (string)$this->dir->getParentFile()->getPath());

    $this->assertInstanceOf(File::class, $this->dirAbs->getParentFile());
    $this->assertEquals(str_replace("/IO", "", __DIR__), (string)$this->dirAbs->getParentFile()->getPath());

    $this->assertInstanceOf(File::class, $this->file->getParentFile());
    $this->assertEquals("tests/IO", (string)$this->file->getParentFile()->getPath());

    $this->assertInstanceOf(File::class, $this->fileAbs->getParentFile());
    $this->assertEquals(__DIR__, (string)$this->fileAbs->getParentFile()->getPath());

    $this->assertNull($this->fileRoot->getParentFile());
  }

  public function testGetAbsolutePath()
  {
    $this->assertContains("/", (string)$this->file->getAbsolutePath());
    $this->assertContains("/", (string)$this->file->getCanonicalPath());
    $this->assertContains("/", (string)$this->fileAbs->getAbsolutePath());
    $this->assertContains("/", (string)$this->dir->getAbsolutePath());
    $this->assertContains("/", (string)$this->dirAbs->getAbsolutePath());
    $this->assertContains("/", (string)$this->fileRoot->getAbsolutePath());

    $this->assertContains((string)System::getProperty('user.home'), (string)$this->file->getAbsolutePath());
    $this->assertContains((string)System::getProperty('user.home'), (string)$this->fileAbs->getAbsolutePath());
    $this->assertContains((string)System::getProperty('user.home'), (string)$this->dir->getAbsolutePath());
    $this->assertContains((string)System::getProperty('user.home'), (string)$this->dirAbs->getAbsolutePath());
    $this->assertContains((string)System::getProperty('user.home'), (string)$this->fileRoot->getAbsolutePath());

    $this->assertContains((string)$this->file->getPath(), (string)$this->file->getAbsolutePath());
    $this->assertContains((string)$this->fileAbs->getPath(), (string)$this->fileAbs->getAbsolutePath());
    $this->assertContains((string)$this->dir->getPath(), (string)$this->dir->getAbsolutePath());
    $this->assertContains((string)$this->dirAbs->getPath(), (string)$this->dirAbs->getAbsolutePath());
    $this->assertContains((string)$this->fileRoot->getPath(), (string)$this->fileRoot->getAbsolutePath());
  }

  public function testSpace()
  {
    $this->assertGreaterThan(0, $this->file->getFreeSpace());
    $this->assertGreaterThan(0, $this->file->getTotalSpace());
    $this->assertGreaterThan(0, $this->file->getUsableSpace());
  }

  public function testCan()
  {
    $this->assertTrue($this->file->canRead());
    $this->assertTrue($this->file->canWrite());
    $this->assertFalse($this->file->canExecute());
  }

  public function testIs()
  {
    $this->assertTrue($this->file->isFile());
    $this->assertFalse($this->file->isDirectory());
    $this->assertTrue($this->dir->isDirectory());
    $this->assertFalse($this->dir->isFile());

    $hidden = new File(".gitignore");
    $this->assertTrue($hidden->isHidden());
  }

  public function testGetLastModified()
  {
    $this->assertEquals(filemtime($this->file->getAbsolutePath()), $this->file->getLastModified());
  }

  public function testLength()
  {
    $this->assertEquals(strlen(file_get_contents($this->file->getAbsolutePath())), $this->file->length());
  }

  public function testCreateDelete()
  {
    $fileName = ".tmp.txt";
    $file = new File($fileName);
    $this->assertFalse($file->exists());
    $file->createNewFile();
    $this->assertTrue($file->exists());
    $file->delete();
    $this->assertFalse($file->exists());
  }

  public function testHashCodeFile()
  {
    $this->assertInternalType('integer', $this->file->hashCode());
  }

  /**
   * Desc
   *
   * @dataProvider dataBitwiseEnable
   *
   * @return void
   */
  public function testBitwiseEnable($from, $append, $expected)
  {
    $this->assertEquals($expected, sprintf("%04o", intval($from, 8) | $append));
    $strOct = sprintf("%04o", intval($from, 8) | $append);
    $oct = octdec($strOct);

    $this->assertEquals($oct, intval($from, 8) | $append);
  }

  /**
   * Desc
   *
   * @dataProvider dataBitwiseDisable
   *
   * @return void
   */
  public function testBitwiseDisable($from, $append, $expected)
  {
    $this->assertEquals($expected, sprintf("%04o", intval($from, 8) & ~$append));
  }

  public function dataBitwiseEnable()
  {
    return [
      ["0660", 0x04, "0664"],
      ["0660", 0x02, "0662"],
      ["0660", 0x01, "0661"],

      ["0661", 0x04, "0665"],
      ["0661", 0x02, "0663"],
      ["0661", 0x01, "0661"],

      ["0662", 0x04, "0666"],
      ["0662", 0x02, "0662"],
      ["0662", 0x01, "0663"],

      ["0664", 0x04, "0664"],
      ["0664", 0x02, "0666"],
      ["0664", 0x01, "0665"],

      ["0663", 0x04, "0667"],
      ["0663", 0x02, "0663"],
      ["0663", 0x01, "0663"],

      ["0665", 0x04, "0665"],
      ["0665", 0x02, "0667"],
      ["0665", 0x01, "0665"],

      ["0666", 0x04, "0666"],
      ["0666", 0x02, "0666"],
      ["0666", 0x01, "0667"],

      ["0667", 0x04, "0667"],
      ["0667", 0x02, "0667"],
      ["0667", 0x01, "0667"],
    ];
  }

  public function dataBitwiseDisable()
  {
    return [
      ["0660", 0x04, "0660"],
      ["0660", 0x02, "0660"],
      ["0660", 0x01, "0660"],

      ["0661", 0x04, "0661"],
      ["0661", 0x02, "0661"],
      ["0661", 0x01, "0660"],

      ["0662", 0x04, "0662"],
      ["0662", 0x02, "0660"],
      ["0662", 0x01, "0662"],

      ["0664", 0x04, "0660"],
      ["0664", 0x02, "0664"],
      ["0664", 0x01, "0664"],

      ["0663", 0x04, "0663"],
      ["0663", 0x02, "0661"],
      ["0663", 0x01, "0662"],

      ["0665", 0x04, "0661"],
      ["0665", 0x02, "0665"],
      ["0665", 0x01, "0664"],

      ["0666", 0x04, "0662"],
      ["0666", 0x02, "0664"],
      ["0666", 0x01, "0666"],

      ["0667", 0x04, "0663"],
      ["0667", 0x02, "0665"],
      ["0667", 0x01, "0666"],
    ];
  }

  public function testSet()
  {
    $file = new File(uniqid('.test_'));
    $this->assertFalse($file->exists());
    $file->createNewFile();
    $this->assertTrue($file->exists());

    $this->assertTrue($file->isReadable());
    $this->assertTrue($file->isWritable());
    $this->assertFalse($file->isExecutable());
    $file->setReadable(true);
    $file->setWritable(true);
    $file->setExecutable(true);

//    var_dump($file->isReadable());
//    var_dump($file->isWritable());
//    var_dump($file->isExecutable());

  }
}