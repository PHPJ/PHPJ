<?php

namespace PHPJ\Lang;

// TODO determine java.io.Serializable interface
abstract class Number extends \GMP implements Object
{
  use ObjectTrait;

  /**
   * Returns the value of the specified number as an <code>int</code>.
   * This may involve rounding or truncation.
   *
   * @return int the numeric value represented by this object after conversion
   *          to type <code>int</code>.
   */
  public abstract function intValue();

  /**
   * Returns the value of the specified number as a <code>long</code>.
   * This may involve rounding or truncation.
   *
   * @return int the numeric value represented by this object after conversion
   *          to type <code>long</code>.
   */
  public abstract function longValue();

  /**
   * Returns the value of the specified number as a <code>float</code>.
   * This may involve rounding.
   *
   * @return  float The numeric value represented by this object after conversion
   *          to type <code>float</code>.
   */
  public abstract function floatValue();

  /**
   * Returns the value of the specified number as a <code>double</code>.
   * This may involve rounding.
   *
   * @return  double The numeric value represented by this object after conversion
   *          to type <code>double</code>.
   */
  public abstract function doubleValue();

  /**
   * Returns the value of the specified number as a <code>byte</code>.
   * This may involve rounding or truncation.
   *
   * @return  string The numeric value represented by this object after conversion
   *          to type <code>byte</code>.
   * @since   JDK1.1
   */
  public function byteValue()
  {
    return decbin(self::intValue());
  }

  /**
   * Returns the value of the specified number as a <code>short</code>.
   * This may involve rounding or truncation.
   *
   * @return  string The numeric value represented by this object after conversion
   *          to type <code>short</code>.
   * @since   JDK1.1
   */
  public function shortValue()
  {
    return pack('s', self::intValue());
  }

}