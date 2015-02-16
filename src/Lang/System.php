<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Lang;

use PHPJ\Lang\Exceptions\ArrayIndexOutOfBoundsException;

final class System extends ObjectClass
{

  /**
   * @param NativeArray $src
   * @param int $srcPos
   * @param NativeArray $dest
   * @param int $destPos
   * @param int $length
   * @return NativeArray
   */
  public static function arraycopy(NativeArray $src, $srcPos, NativeArray $dest, $destPos, $length)
  {
    self::checkArrayCopy($src, $srcPos, $dest, $destPos, $length);
    return self::arraycopyNoCheck(clone $src, $srcPos, $dest, $destPos, $length);
  }

  /**
   * @param NativeArray $src
   * @param int $srcPos
   * @param NativeArray $dest
   * @param int $destPos
   * @param int $length
   * @return NativeArray
   */
  public static function arraycopyNoCheck(NativeArray $src, $srcPos, NativeArray $dest, $destPos, $length)
  {
    for ($i = 0; $i < $length; $i++) {
      $dest[$destPos + $i] = $src[$srcPos + $i];
    }
    return $dest;
  }

  /**
   * @param $property
   * @return \PHPJ\Lang\String
   */
  public static function getProperty($property){
    throw new \Exception("Todo ".__FUNCTION__." ".__CLASS__);
    return new String();
  }

  /**
   * @param NativeArray $src
   * @param int $srcPos
   * @param NativeArray $dest
   * @param int $destPos
   * @param int $length
   */
  protected static function checkArrayCopy(NativeArray $src, &$srcPos, NativeArray $dest, &$destPos, &$length)
  {
    self::checkIsPositive($srcPos);
    self::checkIsPositive($destPos);
    self::checkIsPositive($length);
    if ($srcPos + $length > $src->length()) {
      throw new ArrayIndexOutOfBoundsException();
    }
    if ($destPos + $length > $dest->length()) {
      throw new ArrayIndexOutOfBoundsException();
    }
  }

  /**
   * @param int $index
   */
  protected static function checkIsPositive(&$index)
  {
    $index = (int)$index;
    if ($index < 0) {
      throw new ArrayIndexOutOfBoundsException($index);
    }
  }
}