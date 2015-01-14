<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Lang;


class StringBuilder extends AbstractStringBuilder
{

  public function __construct($capacity=16)
  {
    parent::__construct($capacity);
  }
}