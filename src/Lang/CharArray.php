<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Lang;


class CharArray extends NativeArray
{
  protected $escapeValidation = false;

  public static function fromString($string)
  {
    $array = preg_split('//u', (string)$string, -1, PREG_SPLIT_NO_EMPTY);
    $size = count($array);
    $self = new self($size);
    $self->escapeValidation = true;
    for($i = 0; $i <=$size-1; $i++){
      $self[$i] = $array[$i];
    }
    $self->escapeValidation = false;
    return $self;
  }

  public function offsetSet($offset, $value)
  {
    if($this->escapeValidation || (is_string($value) && $value === mb_substr($value, 0, 1))){
      return parent::offsetSet($offset, $value);
    }
    throw new \InvalidArgumentException(
      sprintf("Inserting value should be a single character. Trying to insert %s, offset %d", $value, $offset)
    );
  }

  public function toString()
  {
    return new String(implode('', iterator_to_array($this)));
  }
}