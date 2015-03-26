<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\IO;

use Kozz\Components\Cache\StaticCache;
use PHPJ\Lang\String;
use PHPJ\Lang\System;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Filesystem\Filesystem as SFilesystem;

class UnixFileSystem extends FileSystem
{

  private $slash;
  private $colon;

  /** @var SFilesystem */
  protected $fs;

  public function __construct()
  {
    $this->slash = DIRECTORY_SEPARATOR;
    $this->colon = PATH_SEPARATOR;
    $this->fs    = StaticCache::loadInjection('symfony_fs', new Definition(SFilesystem::class));
  }

  /**
   * Return char
   * the local filesystem's name-separator character.
   */
  public function getSeparator()
  {
    return $this->slash;
  }

  /**
   * Return char
   * the local filesystem's path-separator character.
   */
  public function getPathSeparator()
  {
    return $this->colon;
  }

  /**
   * @param \PHPJ\Lang\String $path
   * @return \PHPJ\Lang\String Convert the given pathname string to normal form.  If the string is
   * Convert the given pathname string to normal form.  If the string is
   * already in normal form then it is simply returned.
   */
  public function normalize(String $path)
  {
    return new String(preg_replace("/\/{2,}/", "/", (string)$path));
  }

  /**
   * Compute the length of this pathname string's prefix.  The pathname
   * string must be in normal form.
   * @param \PHPJ\Lang\String $path
   * @return int
   */
  public function prefixLength(String $path = null)
  {
    if (null === $path || !$path->getOriginalValue()) {
      return 0;
    }
    return "/" === $path->charAt(0) ? 1 : 0;
  }

  /**
   * Resolve the child pathname string against the parent.
   * Both strings must be in normal form, and the result
   * will be in normal form.
   * @param \PHPJ\Lang\String $parent
   * @param \PHPJ\Lang\String $child
   * @return \PHPJ\Lang\String
   */
  public function resolve(String $parent, String $child)
  {
    if ($child->equals(new String(""))) {
      return $parent;
    }
    if ($child->charAt(0) === '/') {
      if ($parent->equals(new String("/"))) {
        return $child;
      }
      return $parent->concat($child);
    }
    if ($parent->equals(new String("/"))) {
      return $parent->concat($child);
    }
    return new String($parent->getOriginalValue() . '/' . $child->getOriginalValue());
  }

  /**
   * Return the parent pathname string to be used when the parent-directory
   * argument in one of the two-argument File constructors is the empty
   * pathname.
   * @return \PHPJ\Lang\String
   */
  public function getDefaultParent()
  {
    return new String("/");
  }

  /**
   * Post-process the given URI path string if necessary.  This is used on
   * win32, e.g., to transform "/c:/foo" into "c:/foo".  The path string
   * still has slash separators; code in the File class will translate them
   * after this method returns.
   *
   * @param \PHPJ\Lang\String $path
   * @return \PHPJ\Lang\String
   */
  public function fromURIPath(String $path)
  {
    $p = $path;
    if ($p->endsWith(new String("/")) && ($p->getOriginalValue() && ($p->charAt(0) !== $p->getOriginalValue()))) {
      // "/foo/" --> "/foo", but "/" --> "/"
      $p = $p->substring(0, $p->length() - 1);
    }
    return $p;
  }

  /**
   * Tell whether or not the given pathname is absolute.
   * @param File $f
   * @return bool
   */
  public function isAbsolute(File $f)
  {
    return $this->fs->isAbsolutePath($f->getPath());
    //return $f->getPrefixLength() != 0;
  }

  /**
   * Resolve the given pathname into absolute form.  Invoked by the
   * getAbsolutePath and getCanonicalPath methods in the File class.
   * @param File $f
   * @return \PHPJ\Lang\String
   */
  public function resolveFile(File $f)
  {
    if ($this->isAbsolute($f)) {
      return $f->getPath();
    }
    return $this->resolve(System::getProperty("user.dir"), $f->getPath());
  }

  /**
   * @param \PHPJ\Lang\String $path
   * @return \PHPJ\Lang\String
   */
  public function canonicalize(String $path)
  {
    return new String(realpath($path->getOriginalValue()));
  }

  /**
   * Return the simple boolean attributes for the file or directory denoted
   * by the given pathname, or zero if it does not exist or some
   * other I/O error occurs.
   *
   * @param File $f
   * @return int
   */
  public function getBooleanAttributes(File $f)
  {
    if (!$f->exists()) {
      return 0;
    }

    $mode = substr(sprintf('%o', $f->getPerms()), -4);
    $mode = intval($mode, 8);
    return $mode;
  }

  /**
   * Check whether the file or directory denoted by the given abstract
   * pathname may be accessed by this process.  The second argument specifies
   * which access, ACCESS_READ, ACCESS_WRITE or ACCESS_EXECUTE, to check.
   * Return false if access is denied or an I/O error occurs
   * @param File $f
   * @param bool $access
   * @return bool
   */
  public function checkAccess(File $f, $access)
  {

    switch ($access) {
      case self::ACCESS_READ:
        return $f->isReadable();
      case self::ACCESS_WRITE:
        return $f->isWritable();
      case self::ACCESS_EXECUTE:
        return $f->isExecutable();
    }
    return false;
  }

  /**
   * Set on or off the access permission (to owner only or to all) to the file
   * or directory denoted by the given pathname, based on the parameters
   * enable, access and owneronly.
   * @param File $f
   * @param int $access
   * @param bool $enable
   * @param bool $owneronly
   * @return bool
   */
  public function setPermission(File $f, $access, $enable = null, $owneronly = null)
  {
    $mode = $this->generateAccessMode($f, $enable, $owneronly);
    $this->fs->chmod($f->getAbsolutePath(), $mode);
  }

  /**
   * Desc
   *
   * @param File $f
   * @param $access
   * @param null $enable
   * @param null $owneronly
   * @todo owneronly
   *
   * @return int
   */
  protected function generateAccessMode(File $f, $access, $enable = null, $owneronly = null)
  {
    $mode = $this->getBooleanAttributes($f);
    $newMode = $enable
      ? $mode | $access // 0660 | 0x04 = 0664
      : $mode & ~ $access; // 0664 &~ 0x04 = 0660
    return $newMode;
  }

  /**
   * Return the time at which the file or directory denoted by the given
   * pathname was last modified, or zero if it does not exist or
   * some other I/O error occurs.
   * @param File $f
   * @return int
   */
  public function getLastModifiedTime(File $f)
  {
    return $f->getMTime();
  }

  /**
   * Return the length in bytes of the file denoted by the given abstract
   * pathname, or zero if it does not exist, is a directory, or some other
   * I/O error occurs.
   * @param File $f
   * @return int
   */
  public function getLength(File $f)
  {
    return $f->getSize();
  }

  /**
   * Create a new empty file with the given pathname.  Return
   * <code>true</code> if the file was created and <code>false</code> if a
   * file or directory with the given pathname already exists.  Throw an
   * IOException if an I/O error occurs.
   * @param \PHPJ\Lang\String $pathname
   * @return bool
   */
  public function createFileExclusively(String $pathname)
  {
    $this->fs->touch($pathname->getOriginalValue());
  }

  /**
   * Delete the file or directory denoted by the given pathname,
   * returning <code>true</code> if and only if the operation succeeds.
   * @param File $f
   * @return bool
   */
  public function delete(File $f)
  {
    $this->fs->remove($f->getAbsolutePath());
  }

  /**
   * List the elements of the directory denoted by the given abstract
   * pathname.  Return an array of strings naming the elements of the
   * directory if successful; otherwise, return <code>null</code>.
   * @param File $f
   * @return \String[]
   */
  public function listFile(File $f)
  {
    return $this->getListIterator($f);
  }

  /**
   * Desc
   *
   * @param File $f
   * @return \DirectoryIterator
   */
  public function getListIterator(File $f)
  {
    return new \DirectoryIterator($f->getPath());
  }

  /**
   * Create a new directory denoted by the given pathname,
   * returning <code>true</code> if and only if the operation succeeds.
   * @param File $f
   * @return bool
   */
  public function createDirectory(File $f)
  {
    $this->fs->mkdir($f->getPath());
  }

  /**
   * Rename the file or directory denoted by the first pathname to
   * the second pathname, returning <code>true</code> if and only if
   * the operation succeeds.
   * @param File $f1
   * @param File $f2
   * @return bool
   */
  public function rename(File $f1, File $f2)
  {
    return $this->fs->rename($f1->getPath(), $f2->getPath());
  }

  /**
   * Set the last-modified time of the file or directory denoted by the
   * given pathname, returning <code>true</code> if and only if the
   * operation succeeds.
   * @param File $f
   * @param int $time
   * @return bool
   */
  public function setLastModifiedTime(File $f, $time = null)
  {
    return $this->fs->touch($f->getPath(), $time);
  }

  /**
   * Mark the file or directory denoted by the given pathname as
   * read-only, returning <code>true</code> if and only if the operation
   * succeeds.
   * @param File $f
   * @return bool
   */
  public function setReadOnly(File $f)
  {
    $string = sprintf('%o', $this->getBooleanAttributes($f));
    $string[3] = "4";
    return $this->fs->chmod($f->getAbsolutePath(), octdec($string));
  }

  /**
   * List the available filesystem roots.
   * @return File[]
   */
  public function listRoots()
  {
    // TODO: Implement listRoots() method.
  }

  /**
   * @param File $f
   * @param int $t
   * @return int
   */
  public function getSpace(File $f, $t)
  {
    switch ($t) {
      case self::SPACE_FREE:
        return disk_free_space($f->getAbsolutePath()->getOriginalValue());
      case self::SPACE_TOTAL:
        return disk_total_space("/");
      case self::SPACE_USABLE:
        return $this->getBytesFromIni(ini_get("memory_limit"));
      default:
        return null;
    }
  }

  protected function getBytesFromIni($val)
  {
    $val  = trim($val);
    $last = strtolower($val[strlen($val) - 1]);
    switch ($last) {
      case 'g':
        $val *= 1024;
        break;
      case 'm':
        $val *= 1024;
        break;
      case 'k':
        $val *= 1024;
        break;
      default:
        $val = (int)$val;
    }
    return $val;
  }

  /**
   * Compare two pathnames lexicographically.
   * @param File $f1
   * @param File $f2
   * @return int
   */
  public function compare(File $f1, File $f2)
  {
    return $f1->getAbsolutePath()->compareTo($f2->getAbsolutePath());
  }

}