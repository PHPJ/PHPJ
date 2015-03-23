<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Util;


use PHPJ\Lang\String;

class Properties extends \ArrayObject
{

  public function __construct($input = [], $flags = 0, $iterator_class = "ArrayIterator")
  {
    parent::__construct([]);
//    $this['os.name'] = new String($_SERVER['_system_name']);
//    $this['os.arch'] = new String($_SERVER['_system_arch']);
//    $this['os.version'] = new String($_SERVER['_system_version']);
    $this['file.separator'] = new String(DIRECTORY_SEPARATOR);
    $this['path.separator'] = new String(PATH_SEPARATOR);
    $this['line.separator'] = new String("\n");
    $this['user.name'] = new String($_SERVER['USER']);
    $this['user.home'] = new String($_SERVER['HOME']);
    $this['user.dir'] = new String($_SERVER['DOCUMENT_ROOT'] ?: $_SERVER['PWD']);
  }

  /**
   * @param $property
   * @return \PHPJ\Lang\String
   */
  public function getProperty($property)
  {
    return $this[$property];
  }

}