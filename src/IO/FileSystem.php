<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\IO;

use PHPJ\IO\Exceptions\IOException;
use PHPJ\Lang\ObjectClass;
use PHPJ\Lang\String;
use PHPJ\Lang\System;

abstract class FileSystem extends ObjectClass
{

  /* -- Normalization and construction -- */

  /**
   * Return char
   * the local filesystem's name-separator character.
   */
  abstract public function getSeparator();

  /**
   * Return char
   * the local filesystem's path-separator character.
   */
  abstract public function getPathSeparator();

  /**
   * @param \PHPJ\Lang\String $path
   * @return \PHPJ\Lang\String Convert the given pathname string to normal form.  If the string is
   * Convert the given pathname string to normal form.  If the string is
   * already in normal form then it is simply returned.
   */
  abstract public function normalize(String $path);

  /**
   * Compute the length of this pathname string's prefix.  The pathname
   * string must be in normal form.
   * @param \PHPJ\Lang\String $path
   * @return int
   */
  abstract public function prefixLength(String $path);

  /**
   * Resolve the child pathname string against the parent.
   * Both strings must be in normal form, and the result
   * will be in normal form.
   * @param \PHPJ\Lang\String $parent
   * @param \PHPJ\Lang\String $child
   * @return \PHPJ\Lang\String
   */
  abstract public function resolve(String $parent, String $child);

  /**
   * Return the parent pathname string to be used when the parent-directory
   * argument in one of the two-argument File constructors is the empty
   * pathname.
   * @return \PHPJ\Lang\String
   */
  abstract public function getDefaultParent();

  /**
   * Post-process the given URI path string if necessary.  This is used on
   * win32, e.g., to transform "/c:/foo" into "c:/foo".  The path string
   * still has slash separators; code in the File class will translate them
   * after this method returns.
   *
   * @param \PHPJ\Lang\String $path
   * @return \PHPJ\Lang\String
   */
  abstract public function fromURIPath(String $path);


  /* -- Path operations -- */

  /**
   * Tell whether or not the given abstract pathname is absolute.
   * @param File $f
   * @return bool
   */
  abstract public function isAbsolute(File $f);

  /**
   * Resolve the given abstract pathname into absolute form.  Invoked by the
   * getAbsolutePath and getCanonicalPath methods in the File class.
   * @param File $f
   * @return \PHPJ\Lang\String
   */
  abstract public function resolveFile(File $f);

  /**
   * @param \PHPJ\Lang\String $path
   * @return \PHPJ\Lang\String
   */
  abstract public function canonicalize(String $path);


  /* -- Attribute accessors -- */

  /* Constants for simple boolean attributes */
  const BA_EXISTS = 0x01;
  const BA_REGULAR = 0x02;
  const BA_DIRECTORY = 0x04;
  const BA_HIDDEN = 0x08;

  /**
   * Return the simple boolean attributes for the file or directory denoted
   * by the given abstract pathname, or zero if it does not exist or some
   * other I/O error occurs.
   * @param File $f
   * @return int
   */
  abstract public function getBooleanAttributes(File $f);

  const ACCESS_READ = 0x04;
  const ACCESS_WRITE = 0x02;
  const ACCESS_EXECUTE = 0x01;

  /**
   * Check whether the file or directory denoted by the given abstract
   * pathname may be accessed by this process.  The second argument specifies
   * which access, ACCESS_READ, ACCESS_WRITE or ACCESS_EXECUTE, to check.
   * Return false if access is denied or an I/O error occurs
   * @param File $f
   * @param bool $access
   * @return bool
   */
  abstract public function checkAccess(File $f, $access);

  /**
   * Set on or off the access permission (to owner only or to all) to the file
   * or directory denoted by the given abstract pathname, based on the parameters
   * enable, access and owneronly.
   * @param File $f
   * @param int $access
   * @param bool $enable
   * @param bool $owneronly
   * @return bool
   */
  abstract public function setPermission(File $f, $access, $enable, $owneronly);

  /**
   * Return the time at which the file or directory denoted by the given
   * abstract pathname was last modified, or zero if it does not exist or
   * some other I/O error occurs.
   * @param File $f
   * @return int
   */
  abstract public function getLastModifiedTime(File $f);

  /**
   * Return the length in bytes of the file denoted by the given abstract
   * pathname, or zero if it does not exist, is a directory, or some other
   * I/O error occurs.
   * @param File $f
   * @return int
   */
  abstract public function  getLength(File $f);


  /* -- File operations -- */

  /**
   * Create a new empty file with the given pathname.  Return
   * <code>true</code> if the file was created and <code>false</code> if a
   * file or directory with the given pathname already exists.  Throw an
   * IOException if an I/O error occurs.
   * @param \PHPJ\Lang\String $pathname
   * @return bool
   */
  abstract public function createFileExclusively(String $pathname);

  /**
   * Delete the file or directory denoted by the given abstract pathname,
   * returning <code>true</code> if and only if the operation succeeds.
   * @param File $f
   * @return bool
   */
  abstract public function delete(File $f);

  /**
   * List the elements of the directory denoted by the given abstract
   * pathname.  Return an array of strings naming the elements of the
   * directory if successful; otherwise, return <code>null</code>.
   * @param File $f
   * @return \String[]
   */
  abstract public function listFile(File $f);

  /**
   * Desc
   *
   * @param File $f
   * @return \DirectoryIterator
   */
  abstract public function getListIterator(File $f);

  /**
   * Create a new directory denoted by the given abstract pathname,
   * returning <code>true</code> if and only if the operation succeeds.
   * @param File $f
   * @return bool
   */
  abstract public function createDirectory(File $f);

  /**
   * Rename the file or directory denoted by the first abstract pathname to
   * the second abstract pathname, returning <code>true</code> if and only if
   * the operation succeeds.
   * @param File $f1
   * @param File $f2
   * @return bool
   */
  abstract public function rename(File $f1, File $f2);

  /**
   * Set the last-modified time of the file or directory denoted by the
   * given abstract pathname, returning <code>true</code> if and only if the
   * operation succeeds.
   * @param File $f
   * @param int $time
   * @return bool
   */
  abstract public function setLastModifiedTime(File $f, $time);

  /**
   * Mark the file or directory denoted by the given abstract pathname as
   * read-only, returning <code>true</code> if and only if the operation
   * succeeds.
   * @param File $f
   * @return bool
   */
  abstract public function setReadOnly(File $f);


  /* -- Filesystem interface -- */

  /**
   * List the available filesystem roots.
   * @return File[]
   */
  abstract public function listRoots();

  /* -- Disk usage -- */
  const SPACE_TOTAL = 0;
  const SPACE_FREE = 1;
  const SPACE_USABLE = 2;

  /**
   * @param File $f
   * @param int $t
   * @return int
   */
  abstract public function getSpace(File $f, $t);

  /* -- Basic infrastructure -- */

  /**
   * Compare two abstract pathnames lexicographically.
   * @param File $f1
   * @param File $f2
   * @return int
   */
  abstract public function compare(File $f1, File $f2);

  /**
   * Compute the hash code of an abstract pathname.
   * @param File $f
   * @return int
   */
  public function hashCodeFile(File $f){
    return (new String($f->getPath()))->hashCode() ^ 1234321;
  }

  // Flags for enabling/disabling performance optimizations for file
  // name canonicalization
  public static $useCanonCaches = true;
  public static $useCanonPrefixCache = true;

  /**
   * @param \PHPJ\Lang\String $prop
   * @param boolean $defaultVal
   * @return bool
   */
  private static function getBooleanProperty(String $prop, $defaultVal = null)
  {
    $val = System::getProperty($prop);
    if ($val === null) {
      return $defaultVal;
    }
    if ($val->equalsIgnoreCase(new String("true"))) {
      return true;
    } else {
      return false;
    }
  }

  //static {
  //  useCanonCaches      = getBooleanProperty("sun.io.useCanonCaches",
  //    useCanonCaches);
  //  useCanonPrefixCache = getBooleanProperty("sun.io.useCanonPrefixCache",
  //    useCanonPrefixCache);
  //}
}