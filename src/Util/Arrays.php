<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Util;

use PHPJ\Lang\Exceptions\ArrayIndexOutOfBoundsException;
use PHPJ\Lang\Exceptions\IllegalArgumentException;
use PHPJ\Lang\ObjectClass;
use PHPJ\Lang\String;

class Arrays extends ObjectClass
{


  public static function copyOf($array, $newLength)
  {
    if (is_array($array)) {
      return self::copyOfFixedArray(\SplFixedArray::fromArray($array), $newLength)->toArray();
    }
    if ($array instanceof \SplFixedArray) {
      return self::copyOfFixedArray($array, $newLength);
    }
    if ($array instanceof String) {
      return self::copyOfString($array, $newLength);
    }
    if (is_string($array)) {
      return (string)self::copyOfString(new String($array), $newLength);
    }
    return null;
  }

  /**
   * @param \SplFixedArray $array
   * @param int $newLength
   * @return \SplFixedArray
   */
  public static function copyOfFixedArray(\SplFixedArray $array, $newLength)
  {
    $array = clone $array;
    $array->setSize($newLength);
    return $array;
  }

  /**
   * @param \PHPJ\Lang\String $string
   * @param int $newLength
   * @return \PHPJ\Lang\String
   */
  public static function copyOfString(String $string, $newLength)
  {
    return $newLength > $string->length()
      ? $string->concat(new String(str_repeat("\0", $newLength - $string->length())))
      : $string->substring(0, $newLength);

  }

  public static function fill(&$array, $newValue)
  {
    if (4 === func_num_args()) {
      list($array, $fromIndex, $toIndex, $newValue) = func_get_args();
      return self::fillFromTo($array, $fromIndex, $toIndex, $newValue);
    }
    if (is_array($array)) {
      $array = array_fill(0, count($array), $newValue);
    }
    if (is_string($array)) {
      $array = str_repeat($newValue, mb_strlen($array));
    }
    return $array;
  }

  /**
   * Fill with 4 params
   *
   * @param $array
   * @param $fromIndex
   * @param $toIndex
   * @param $val
   * @return mixed
   *
   * @diff
   */
  public static function fillFromTo(&$array, $fromIndex, $toIndex, $val)
  {
    //$this->rangeCheck(count($array), $fromIndex, $toIndex);
    for($i = $fromIndex; $i < $toIndex; $i++){
      $array[$i] = $val;
    }
    return $array;
  }

  /**
   * Checks that {@code fromIndex} and {@code toIndex} are in
   * the range and throws an exception if they aren't.
   * @param $arrayLength int
   * @param $fromIndex int
   * @param $toIndex int
   */
  private static function rangeCheck($arrayLength, $fromIndex, $toIndex)
  {
    if ($fromIndex > $toIndex) {
      throw new IllegalArgumentException(
        "fromIndex(" . $fromIndex . ") > toIndex(" . $toIndex . ")");
    }
    if ($fromIndex < 0) {
      throw new ArrayIndexOutOfBoundsException($fromIndex);
    }
    if ($toIndex > $arrayLength) {
      throw new ArrayIndexOutOfBoundsException($toIndex);
    }
  }
}