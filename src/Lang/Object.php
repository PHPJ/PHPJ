<?php

namespace PHPJ\Lang;

use ReflectionClass;

/**
 * Class Object
 *
 * @package PHPJ\Lang
 */
class Object
{

  public function __construct()
  {

  }

  /**
   * Use __clone to override
   *
   * @return static
   */
  public final function getClone()
  {
    return clone $this;
  }


  /**
   * @param self $object
   *
   * @return bool
   */
  public function equals(Object $object = null)
  {
    return $object && $object->hashCode() === $this->hashCode();
  }

  /**
   * @return string
   * @todo return Class maybe
   */
  public final function getClass()
  {
    return get_called_class();
  }

  /**
   * @return ReflectionClass
   */
  public final function getClassReflection()
  {
    return new ReflectionClass($this);
  }

  /**
   * @return string
   * @todo return String
   */
  public function hashCode()
  {
    return spl_object_hash($this);
  }

  /**
   * @return string
   * @todo return String
   */
  public function toString()
  {
    return sprintf("%s@%s", $this->getClass(), $this->hashCode());
  }

  /**
   * Final. Use toString() to override
   *
   * @return string
   */
  public final function __toString()
  {
    return $this->toString();
  }
}
