<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Lang;


final class StringBuilder extends AbstractStringBuilder
{

  /**
   * @param string|int $string
   */
  public function __construct($string = null)
  {
    if(is_int($string)){
      parent::__construct($string);
      return;
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