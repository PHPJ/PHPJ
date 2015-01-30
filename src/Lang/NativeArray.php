<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Lang;

class NativeArray extends \SplFixedArray implements Object
{
  use ObjectTrait;

  public function length()
  {
    return $this->getSize();
  }

}