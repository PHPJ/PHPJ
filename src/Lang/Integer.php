<?php

namespace PHPJ\Lang;

class Integer extends Number
{

  const MIN_VALUE = 0x80000000;
  const MAX_VALUE = 0x7fffffff;

  protected static $digits = [
        '0' , '1' , '2' , '3' , '4' , '5' ,
        '6' , '7' , '8' , '9' , 'a' , 'b' ,
        'c' , 'd' , 'e' , 'f' , 'g' , 'h' ,
        'i' , 'j' , 'k' , 'l' , 'm' , 'n' ,
        'o' , 'p' , 'q' , 'r' , 's' , 't' ,
        'u' , 'v' , 'w' , 'x' , 'y' , 'z'
    ];

  /**
   * @var int
   */
  protected $value;

  /**
   * @param $value
   * @param null $radix
   */
  public function __construct($value, $radix = null)
  {
    $this->value = gmp_init($value, (int)$radix);
  }


  /**
   * Returns the value of the specified number as an <code>int</code>.
   * This may involve rounding or truncation.
   *
   * @return int the numeric value represented by this object after conversion
   *          to type <code>int</code>.
   */
  public function intValue()
  {
    return gmp_intval($this->value);
  }

  /**
   * Returns the value of the specified number as a <code>long</code>.
   * This may involve rounding or truncation.
   *
   * @return int the numeric value represented by this object after conversion
   *          to type <code>long</code>.
   */
  public function longValue()
  {
    return gmp_intval($this->value);
  }

  /**
   * Returns the value of the specified number as a <code>float</code>.
   * This may involve rounding.
   *
   * @return  float The numeric value represented by this object after conversion
   *          to type <code>float</code>.
   */
  public function floatValue()
  {
    return (float)$this->value;
  }

  /**
   * Returns the value of the specified number as a <code>double</code>.
   * This may involve rounding.
   *
   * @return  double The numeric value represented by this object after conversion
   *          to type <code>double</code>.
   */
  public function doubleValue()
  {
    return (double)$this->value;
  }
}