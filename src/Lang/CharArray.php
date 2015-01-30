<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Lang;


class CharArray extends NativeArray
{

  public static function fromString($string)
  {
    $array = preg_split('//u', (string)$string, -1, PREG_SPLIT_NO_EMPTY);
    $size = count($array);
    $self = new self($size);
    for($i = 0; $i <=$size-1; $i++){
      $self[$i] = $array[$i];
    }
    return $self;
  }

  public function toString()
  {
    return new String(implode('', iterator_to_array($this)));
  }
}