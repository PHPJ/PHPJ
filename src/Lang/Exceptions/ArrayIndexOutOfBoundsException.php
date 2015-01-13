<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Lang\Exceptions;


class ArrayIndexOutOfBoundsException extends IndexOutOfBoundsException
{

  public function __construct($index)
  {
    parent::__construct("Array index out of range: " . $index);
  }
}