<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Lang\Exceptions;

class IndexOutOfBoundsException extends \OutOfBoundsException
{

  public function __construct($message)
  {
    parent::__construct($this->getText($message));
  }

  protected function getText($index)
  {
    return is_int($index) ? "{$this->getType()} index out of range: " . $index : $index;
  }

  protected function getType()
  {
    return '';
  }

}