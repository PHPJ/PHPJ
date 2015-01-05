<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Tests\Lang;


use PHPJ\Lang\ObjectClass;
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

  public function testSettingsCharset()
  {
    $this->assertEquals(strtolower('UTF-8'), strtolower(ini_get('default_charset')));
  }


  public function testLength()
  {
    $this->assertEquals(strlen($this->string->getOriginalValue()), $this->string->length());
    $this->assertEquals(strlen($this->string->getOriginalValue()), $this->string->length());
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
   * @dataProvider dataSubstringException
   *
   * @param $beginIndex
   * @param $endIndex
   */
  public function testSubstringException($beginIndex, $endIndex)
  {
    $this->string->substring($beginIndex, $endIndex);
  }

  public function dataSubstringException()
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
    $this->assertTrue($this->string->equals($this->string));
    $this->assertTrue($this->string->equals(clone $this->string));
    $this->assertTrue($this->string->equals(new String(self::STRING_VALUE)));
    $this->assertFalse($this->string->equals(new String(self::STRING_VALUE.' ')));
    $this->assertFalse($this->string->equals(new ObjectClass()));
    $this->assertFalse($this->string->equals(null));
  }

  public function testEqualsIgnoreCase()
  {
    $this->assertTrue($this->string->equalsIgnoreCase($this->string));
    $this->assertTrue($this->string->equalsIgnoreCase(clone $this->string));
    $this->assertFalse($this->string->equalsIgnoreCase(new String(self::STRING_VALUE. ' ')));
    $this->assertFalse($this->string->equalsIgnoreCase(new String('test strini')));
  }

  /**
   * @dataProvider dataCompareTo
   * @param int $diff
   * @param string $string
   */
  public function testCompareTo($diff, $string)
  {
    $this->assertEquals($diff, $this->string->compareTo(new String($string)));
  }

  /**
   * @dataProvider dataCompareTo
   * @param int $diff
   * @param string $string
   */
  public function testCompareToOriginal($diff, $string)
  {
    $r = new \ReflectionClass($this->getClassName());
    $m = $r->getMethod('_compareTo');
    $m->setAccessible(true);
    $this->assertEquals($diff, $m->invoke($this->string, new String($string)));
  }

  /**
   * @dataProvider dataCompareTo
   * @param int $diff
   * @param string $string
   */
  public function testCompareToIgnoreCase($diff, $string)
  {
    if($string === " String"){
      $diff = 84;
    }

    $this->assertEquals($diff, $this->string->compareToIgnoreCase(new String(strtolower($string))));
  }


  /**
   * @dataProvider dataCompareTo
   * @param int $diff
   * @param string $string
   */
  public function testCompareToIgnoreCaseOriginal($diff, $string)
  {
    if($string === " String"){
      $diff = 84;
    }
    $r = new \ReflectionClass($this->getClassName());
    $m = $r->getMethod('_compareToIgnoreCase');
    $m->setAccessible(true);
    $this->assertEquals($diff, $m->invoke($this->string, new String(strtolower($string))));
  }

  public function dataCompareTo()
  {
    return [
      [19, "AString"],
      [1 , "String"],
      [52, " String"],
      [11, ""],
      [-2, "Test Strini"],
      [0, "Test String"],
    ];
  }

  /**
   * @dataProvider dataPreValidateRegionMatches
   */
  public function testPreValidateRegionMatches($boolean, $toffset, $compareWith, $oofset,  $len)
  {
    $string = $compareWith ? new String($compareWith) : null;
    $r = new \ReflectionClass($this->getClassName());
    $m = $r->getMethod('preValidateRegionMatches');
    $m->setAccessible(true);
    $this->assertEquals($boolean, $m->invoke($this->string, $toffset, $string, $oofset, $len));
  }

  public function dataPreValidateRegionMatches()
  {
    return [
      [true,  0 , self::STRING_VALUE, 0, 1],
      [true,  0, self::STRING_VALUE, 0, strlen(self::STRING_VALUE)],
      [false,  0 , null, 0, 1],
      [false, -1, self::STRING_VALUE, 0, 1],
      [false, 0 , self::STRING_VALUE, -1, 1],
      [false, strlen(self::STRING_VALUE), self::STRING_VALUE, 0, 1],
      [false, 0, self::STRING_VALUE, strlen(self::STRING_VALUE), 1],
      [false, 0, self::STRING_VALUE, 0, strlen(self::STRING_VALUE)+1],
    ];
  }

  /**
   * @param $boolean boolean
   * @param $compareWith string
   * @dataProvider dataRegionMatches
   */
  public function testRegionMatches($boolean, $toffset, $compareWith, $oofset,  $len)
  {
    $this->assertEquals($boolean, $this->string->regionMatches($toffset, new String($compareWith), $oofset, $len));
  }

  public function dataRegionMatches()
  {
    return array_merge([
      [true,  1, "est", 0, 1],
      [true,  1, "eST", 0, 1],
      [true,  1, "esT", 0, 2],
      [true,  1, "est", 0, 2],
      [true,  1, "est", 0, 3],
      [false, 1, "es ", 0, 3],
      [false, 1, "esT", 0, 3],
    ], $this->dataPreValidateRegionMatches());
  }

  /**
   * @param $boolean boolean
   * @param $compareWith \PHPJ\Lang\String
   * @dataProvider dataRegionMatchesIgnoreCase
   */
  public function testRegionMatchesIgnoreCase($boolean, $toffset, $compareWith, $oofset,  $len)
  {
    $this->assertEquals($boolean, $this->string->regionMatchesIgnoreCase($toffset, new String($compareWith), $oofset, $len));
  }

  public function dataRegionMatchesIgnoreCase()
  {
    return array_merge([
      [true,  1, "est", 0, 1],
      [true,  1, "EST", 0, 1],
      [true,  1, "EST", 0, 2],
      [true,  1, "est", 0, 2],
      [true,  1, "est", 0, 3],
      [true,  1, "esT", 0, 3],
      [false, 1, "es ", 0, 3],
    ], $this->dataPreValidateRegionMatches());
  }




//  public function testSmpCasePerformance()
//  {
//    $t = microtime(true);
//    for($i = 0;$i<100000;$i++){
//      $this->string->regionMatchesIgnoreCase(1, new String("est"), 1, 3);
//      //$c1 = strcasecmp(self::STRING_VALUE, "Test Strini");
//    }
//    $t1 = microtime(true) - $t;
//    $t = microtime(true);
//    for($i = 0;$i<100000;$i++){
//      $this->string->_regionMatchesIgnoreCase(1, new String("est"), 1, 3);
//      //$c2 = strcasecmp(self::STRING_VALUE, "test Strini");
//    }
//    $t2 = microtime(true) - $t;
//    var_dump($t1, $t2);
//  }

}