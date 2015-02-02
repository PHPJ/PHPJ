<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Util;


use PHPJ\Lang\Exceptions\NullPointerException;
use PHPJ\Lang\ObjectClass;

class Objects extends ObjectClass
{

  public static function requireNonNull($obj, $message = null)
  {
    if(null === $obj){
      throw new NullPointerException($message);
    }
    return $obj;
  }
}