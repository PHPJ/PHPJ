<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Lang;

use ReflectionClass;

/**
 * Class Object
 * @package PHPJ\Lang
 */
class Object
{

  public function __construct()
  {

  }

  /**
   * PHP legacy hack method. Retrieves original value.
   * @return null
   */
  public function getOriginalValue()
  {
    return null;
  }

  /**
   * Creates and returns a copy of this object. The precise meaning
   * of "copy" may depend on the class of the object.
   *
   * Use native PHP {@code __clone) to add cloning functionality
   * @return static
   */
  public final function getClone()
  {
    return clone $this;
  }


  /**
   * Indicates whether some other object is "equal to" this one.
   *
   * Note that it is generally necessary to override the {@code hashCode}
   * method whenever this method is overridden, so as to maintain the
   * general contract for the {@code hashCode} method, which states
   * that equal objects must have equal hash codes.
   *
   * @param   Object $object the reference object with which to compare.
   * @return  bool {@code true} if this object is the same as the obj
   *          argument; {@code false} otherwise.
   * @see \PHPJ\Lang\Object::hashCode
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
   * Returns a hash code value for the object.
   *
   * @return  string a hash code value for this object.
   * @see     \PHPJ\Lang\Object::equals
   */
  public function hashCode()
  {
    return spl_object_hash($this);
  }

  /**
   * Returns a string representation of the object. In general, the
   * {@code toString} method returns a string that
   * "textually represents" this object. The result should
   * be a concise but informative representation that is easy for a
   * person to read.
   * It is recommended that all subclasses override this method.
   *
   * @return string a string representation of the object.
   * @todo return String
   */
  public function toString()
  {
    return sprintf("%s@%s", $this->getClass(), $this->hashCode());
  }

  /**
   * Final method that calls {@code toString}
   * Override {@code toString} method to change the way of
   * string representation of the object
   *
   * @return string
   * @see    \PHPJ\Lang\Object::toString
   */
  public final function __toString()
  {
    return $this->toString();
  }

}
