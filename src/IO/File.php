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
}