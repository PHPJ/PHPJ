<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Lang\Exceptions;

class ArrayIndexOutOfBoundsException extends IndexOutOfBoundsException
{

  protected function getType()
  {
    return 'Array';
  }
}