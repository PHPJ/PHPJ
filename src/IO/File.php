<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\IO;

use PHPJ\Lang\Object;
use PHPJ\Lang\ObjectTrait;
use PHPJ\Lang\String;

class File extends \SplFileInfo implements Object
{
  use ObjectTrait;

  /** @var int */
  protected $prefixLength;

  /** @var FileSystem */
  protected $fs;

  /** @var String */
  protected $path;

  public function __construct($file_name)
  {
    $this->fs = DefaultFileSystem::getFileSystem();
    $path = $this->fs->fromURIPath(new String($file_name));
    $this->path = $this->fs->normalize($path);
    $this->prefixLength = $this->fs->prefixLength($this->path);
    parent::__construct($file_name);
  }

  public function getPath()
  {
    return new String($this->getBasename());
  }

  public function getAbsolutePath()
  {
    return new String($this->getPathname());
  }

  public function getCanonicalPath()
  {
    return $this->getAbsolutePath();
  }

  public function getPrefixLength()
  {
    return $this->prefixLength;
  }
}