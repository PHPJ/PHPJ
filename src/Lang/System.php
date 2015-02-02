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
    $srcPos = (int)$srcPos;
    $destPos = (int)$destPos;
    $length = (int)$length;
    if ($srcPos < 0) {
      throw new ArrayIndexOutOfBoundsException($srcPos);
    }
    if ($destPos < 0) {
      throw new ArrayIndexOutOfBoundsException($destPos);
    }
    if ($length < 0) {
      throw new ArrayIndexOutOfBoundsException();
    }
    if ($srcPos + $length > $src->length()) {
      throw new ArrayIndexOutOfBoundsException();
    }
    if ($destPos + $length > $dest->length()) {
      throw new ArrayIndexOutOfBoundsException();
    }
    for ($i = 0; $i < $length; $i++) {
      $dest[$destPos + $i] = $src[$srcPos + $i];
    }
    return $dest;
  }
}