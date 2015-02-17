<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\IO;

use PHPJ\Lang\Object;
use PHPJ\Lang\ObjectTrait;
use PHPJ\Lang\String;

class File extends \SplFileObject implements Object
{
  use ObjectTrait;

  /** @var int */
  protected $prefixLength;

  /** @var FileSystem */
  protected $fs;

  /** @var String */
  protected $path;

  public function __construct ($file_name, $open_mode, $use_include_path, $context)
  {
    $this->fs = DefaultFileSystem::getFileSystem();
    $path = $this->fs->fromURIPath(new String($file_name));
    $this->path = $this->fs->normalize($path);
    $this->prefixLength = $this->fs->prefixLength($this->path);
    parent::__construct($this->path, $open_mode, $use_include_path, $context);
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