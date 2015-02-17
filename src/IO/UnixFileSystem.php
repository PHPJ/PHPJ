<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\IO;

use PHPJ\Lang\String;

class UnixFileSystem extends FileSystem
{

  private $slash;
  private $colon;

  public function __construct()
  {
    $this->slash = DIRECTORY_SEPARATOR;
    $this->colon = DIRECTORY_SEPARATOR;
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
  public function prefixLength(String $path)
  {
    if (!$path->getOriginalValue()) {
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
      $p = $p->substring(0, $p->length()-1);
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
    // TODO: Implement isAbsolute() method.
  }

  /**
   * Resolve the given pathname into absolute form.  Invoked by the
   * getAbsolutePath and getCanonicalPath methods in the File class.
   * @param File $f
   * @return \PHPJ\Lang\String
   */
  public function resolveFile(File $f)
  {
    // TODO: Implement resolveFile() method.
  }

  /**
   * @param \PHPJ\Lang\String $path
   * @return \PHPJ\Lang\String
   */
  public function canonicalize(String $path)
  {
    // TODO: Implement canonicalize() method.
  }

  /**
   * Return the simple boolean attributes for the file or directory denoted
   * by the given pathname, or zero if it does not exist or some
   * other I/O error occurs.
   * @param File $f
   * @return int
   */
  public function getBooleanAttributes(File $f)
  {
    // TODO: Implement getBooleanAttributes() method.
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
    // TODO: Implement checkAccess() method.
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
  public function setPermission(File $f, $access, $enable, $owneronly)
  {
    // TODO: Implement setPermission() method.
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
    // TODO: Implement getLastModifiedTime() method.
  }

  /**
   * Return the length in bytes of the file denoted by the given abstract
   * pathname, or zero if it does not exist, is a directory, or some other
   * I/O error occurs.
   * @param File $f
   * @return int
   */
  public function  getLength(File $f)
  {
    // TODO: Implement getLength() method.
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
    // TODO: Implement createFileExclusively() method.
  }

  /**
   * Delete the file or directory denoted by the given pathname,
   * returning <code>true</code> if and only if the operation succeeds.
   * @param File $f
   * @return bool
   */
  public function delete(File $f)
  {
    // TODO: Implement delete() method.
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
    // TODO: Implement listFile() method.
  }

  /**
   * Create a new directory denoted by the given pathname,
   * returning <code>true</code> if and only if the operation succeeds.
   * @param File $f
   * @return bool
   */
  public function createDirectory(File $f)
  {
    // TODO: Implement createDirectory() method.
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
    // TODO: Implement rename() method.
  }

  /**
   * Set the last-modified time of the file or directory denoted by the
   * given pathname, returning <code>true</code> if and only if the
   * operation succeeds.
   * @param File $f
   * @param int $time
   * @return bool
   */
  public function setLastModifiedTime(File $f, $time)
  {
    // TODO: Implement setLastModifiedTime() method.
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
    // TODO: Implement setReadOnly() method.
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
    // TODO: Implement getSpace() method.
  }

  /**
   * Compare two pathnames lexicographically.
   * @param File $f1
   * @param File $f2
   * @return int
   */
  public function compare(File $f1, File $f2)
  {
    // TODO: Implement compare() method.
  }

}