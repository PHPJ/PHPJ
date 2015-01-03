<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Tests\Lang;


use PHPJ\Lang\Object;
use PHPJ\Lang\String;
use PHPJ\Tests\Test;

class StringTest extends Test
{
  const STRING_VALUE = 'Test String';

  /**
   * @var \PHPJ\Lang\String
   */
  protected $string;

  protected function getClassName()
  {
    return 'PHPJ\Lang\String';
  }

  protected function setUp()
  {
    $this->string = new String(self::STRING_VALUE);
  }

  public function testLoad()
  {
    $this->assertTrue(class_exists($this->getClassName()));
    $this->assertInstanceOf($this->getClassName(), new String(self::STRING_VALUE));
  }

  public function testLength()
  {
    $this->assertEquals(mb_strlen($this->string->getOriginalValue()), $this->string->length());
    $this->assertEquals(mb_strlen($this->string->getOriginalValue()), $this->string->length());
  }

  public function testIsEmpty()
  {
    $stringEmpty = new String();
    $this->assertTrue($stringEmpty->isEmpty());
    $this->assertFalse($this->string->isEmpty());
  }

  public function testCharAt()
  {
    $str = self::STRING_VALUE;
    for($i = 0; $i < mb_strlen(self::STRING_VALUE); $i++){
      $this->assertEquals($str[$i], $this->string->charAt($i));
    }
  }

  /**
   * @expectedException \PHPJ\Lang\Exceptions\StringIndexOutOfBoundsException
   * @expectedExceptionMessage String index out of range: 100
   */
  public function testCharAtException()
  {
    $this->string->charAt(100);
  }

  public function testTrim()
  {
    $string = new String("   \n \t TrimString \n \t   ");
    $string = $string->trim();
    $this->assertEquals("TrimString", $string->getOriginalValue());
  }

  public function testSubstringOneParam()
  {
    $string = $this->string->substring(0);
    $this->assertEquals(spl_object_hash($string), spl_object_hash($this->string));

    $string = $this->string->substring(1);
    $this->assertEquals("est String", $string->getOriginalValue());

    $string = $this->string->substring(2);
    $this->assertEquals("st String", $string->getOriginalValue());

    $string = $this->string->substring($this->string->length());
    $this->assertEquals("", $string->getOriginalValue());
  }

  public function testSubstringTwoParams()
  {
    $string = $this->string->substring(0, $this->string->length());
    $this->assertEquals(spl_object_hash($string), spl_object_hash($this->string));

    $string = $this->string->substring(1, $this->string->length());
    $this->assertEquals("est String", $string->getOriginalValue());

    $string = $this->string->substring(1, 4);
    $this->assertEquals("est", $string->getOriginalValue());

    $string = $this->string->substring($this->string->length(), $this->string->length());
    $this->assertEquals("", $string->getOriginalValue());
  }

  /**
   * @expectedException \PHPJ\Lang\Exceptions\StringIndexOutOfBoundsException
   * @dataProvider testSubstringExceptionDataProvider
   *
   * @param $beginIndex
   * @param $endIndex
   */
  public function testSubstringException($beginIndex, $endIndex)
  {
    $this->string->substring($beginIndex, $endIndex);
  }

  public function testSubstringExceptionDataProvider()
  {
    return [
      [-1, 1],
      [3, 100],
      [3, 1],
    ];
  }

  public function testHashCode()
  {
    $string = new String();
    $this->assertEquals(0, $string->hashCode());

    $hash = $this->string->hashCode();
    $this->assertEquals(432811871, $hash);

    $string = new String(str_repeat(self::STRING_VALUE, 1000));
    $hash = $string->hashCode();
    $this->assertEquals(3575464064, $hash);
  }

  public function testToString()
  {
    $this->assertEquals(spl_object_hash($this->string), spl_object_hash($this->string->toString()));
  }

  public function testEquals()
  {
    $this->assertTrue($this->string->equals(clone $this->string));
    $this->assertTrue($this->string->equals(new String(self::STRING_VALUE)));
    $this->assertFalse($this->string->equals(new String(self::STRING_VALUE.' ')));
    $this->assertFalse($this->string->equals(new Object()));
    $this->assertFalse($this->string->equals(null));
  }

}