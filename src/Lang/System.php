<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Lang;

use PHPJ\Lang\Exceptions\ArrayIndexOutOfBoundsException;

final class System extends ObjectClass
{

  public static function arraycopy(NativeArray $src, $srcPos, NativeArray $dest, $destPos, $length)
  {
    self::checkArrayCopy($src, $srcPos, $dest, $destPos, $length);
    for ($i = 0; $i < $length; $i++) {
      $dest[$destPos + $i] = $src[$srcPos + $i];
    }
    return $dest;
  }

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

  protected static function checkIsPositive(&$index)
  {
    $index = (int)$index;
    if ($index < 0) {
      throw new ArrayIndexOutOfBoundsException($index);
    }
  }
}