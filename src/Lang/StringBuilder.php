<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Lang;


class StringBuilder extends AbstractStringBuilder
{

  public function __construct($string = null)
  {
    if(is_int($string)){
      return parent::__construct($string);
    }
    $string = new String($string);
    parent::__construct($string->length() + 16);
    $this->append($string);
  }

  public function toString()
  {
    return parent::toString();
  }
}