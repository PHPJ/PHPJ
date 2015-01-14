<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Lang\Exceptions;

class ArrayIndexOutOfBoundsException extends IndexOutOfBoundsException
{

  public function __construct($index = null)
  {
    $message = is_int($index) ? "Array index out of range: " . $index : $index;
    parent::__construct($message);
  }
}