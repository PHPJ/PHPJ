<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\IO;

use PHPJ\Lang\Exceptions\IllegalArgumentException;
use PHPJ\Lang\NativeArray;
use PHPJ\Lang\Object;
use PHPJ\Lang\ObjectTrait;
use PHPJ\Lang\String;
use PHPJ\Lang\System;

class File extends \SplFileInfo implements Object
{
  use ObjectTrait;

  /** @var int */
  protected $prefixLength;

  /** @var FileSystem */
  protected $fs;

  /** @var \PHPJ\Lang\String */
  protected $path;

  public function __construct($file_name)
  {
    $this->fs           = DefaultFileSystem::getFileSystem();
    $path               = $this->fs->fromURIPath(new String($file_name));
    $this->path         = $this->fs->normalize($path);
    $this->prefixLength = $this->fs->prefixLength($this->path);
    parent::__construct($this->path);
  }

  public function getName()
  {
    return new String($this->getBasename());
  }

  public function getPath()
  {
    return $this->path;
  }

  public function getAbsolutePath()
  {
    return new String($this->getRealPath());
  }

  public function getCanonicalPath()
  {
    return $this->getAbsolutePath();
  }

  public function getParent()
  {
    $path = explode(System::getProperty('file.separator'), $this->path);
    array_pop($path);
    if (empty($path)) {
      return null;
    }
    $path = implode(System::getProperty('file.separator'), $path);

    return new String($path);
  }

  public function getParentFile()
  {
    if ($path = $this->getParent()) {
      return new static($path);
    }
    return null;
  }

  public function getPrefixLength()
  {
    return $this->prefixLength;
  }

  public function getFreeSpace()
  {
    return $this->fs->getSpace($this, FileSystem::SPACE_FREE);
  }

  public function getTotalSpace()
  {
    return $this->fs->getSpace($this, FileSystem::SPACE_TOTAL);
  }

  public function getUsableSpace()
  {
    return $this->fs->getSpace($this, FileSystem::SPACE_USABLE);
  }


  public function canRead()
  {
    return $this->fs->checkAccess($this, FileSystem::ACCESS_READ);
  }

  public function canWrite()
  {
    return $this->fs->checkAccess($this, FileSystem::ACCESS_WRITE);
  }

  public function canExecute()
  {
    return $this->fs->checkAccess($this, FileSystem::ACCESS_EXECUTE);
  }

  public function exists()
  {
    return $this->isReadable();
    //return ((fs.getBooleanAttributes(this) & FileSystem.BA_EXISTS) != 0);
  }

  public function isDirectory()
  {
    return $this->isDir();
    //return ((fs.getBooleanAttributes(this) & FileSystem.BA_DIRECTORY) != 0);
  }

  public function isFile()
  {
    return parent::isFile();
    //return ((fs.getBooleanAttributes(this) & FileSystem.BA_REGULAR) != 0);
  }

  public function isHidden()
  {
    return 0 === strpos($this->getBasename(), ".");
    //return ((fs.getBooleanAttributes(this) & FileSystem.BA_HIDDEN) != 0);
  }

  public function getLastModified()
  {
    return $this->fs->getLastModifiedTime($this);
  }

  public function length()
  {
    return $this->fs->getLength($this);
  }

  public function createNewFile()
  {
    $this->fs->createFileExclusively($this->path);
  }

  public function delete()
  {
    $this->fs->delete($this);
  }

  public function deleteOnExit()
  {
    //todo
  }

  public function hashCode()
  {
    return $this->fs->hashCodeFile($this);
  }

  public function setExecutable($executable, $ownerOnly = true)
  {
    $this->fs->setPermission($this, FileSystem::ACCESS_EXECUTE, $executable, $ownerOnly);
  }

  public function setWritable($writable, $ownerOnly = true)
  {
    $this->fs->setPermission($this, FileSystem::ACCESS_WRITE, $writable, $ownerOnly);
  }

  public function setReadable($readable, $ownerOnly = true)
  {
    $this->fs->setPermission($this, FileSystem::ACCESS_READ, $readable, $ownerOnly);
  }

  /**
   * Desc
   *
   * @param null $filter
   * @todo filter
   *
   * @return array
   */
  public function listPaths($filter = null)
  {
    $array = new NativeArray();
    foreach ($this->fs->getListIterator($this) as $path) {
      $array[] = new String($path->getBasename());
    }

    return $array;
  }

  public function listFiles($filter = null)
  {
    $array = new NativeArray();
    foreach ($this->fs->getListIterator($this) as $path) {
      $array[] = new self($this->fs->resolve($this->getPath(), new String($path->getPath())));
    }

    return $array;
  }

  public function mkdir()
  {
    $this->fs->createDirectory($this);
  }

  public function renameTo(File $file)
  {
    return $this->fs->rename($this, $file);
  }

  public function setLastModified($time)
  {
    $time = (int)$time;
    if ($time < 0) {
      throw new IllegalArgumentException("Negative time");
    }
    return $this->fs->setLastModifiedTime($this, $time);
  }

  public function setReadOnly()
  {
    return $this->fs->setReadOnly($this);
  }

  public static function listRoots()
  {
    return DefaultFileSystem::getFileSystem()->listRoots();
  }

  public static function createFmpFile(String $prefix = null, String $suffix = null, File $directory = null)
  {
    $directory = $directory ?: new File(sys_get_temp_dir());
    $directory = $directory->getPath();
    $suffix    = (string)($suffix ?: 'tmp');
    $prefix    = (string)($prefix ?: uniqid('tmp_file'));
    $name      = tempnam($directory, $prefix);
    $name      = sprintf("%s.%s", $name, $suffix);
    $file      = new File($name);
    $file->createNewFile();
    return $file;
  }
}