<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Tests\Lang;


use PHPJ\Lang\ObjectClass;
use PHPJ\Lang\String;
use PHPJ\Lang\StringBuilder;
use PHPJ\Tests\Test;
use PHPJ\Util\Regex\Exceptions\PatternSyntaxException;

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
    $this->assertEquals(strtolower('UTF-8'), strtolower(mb_internal_encoding()));
  }


  /**
   * @dataProvider dataLength
   */
  public function testLength($length, $value)
  {
    $this->assertEquals($length, (new String($value))->length());
  }

  public function dataLength()
  {
    return [
      [11, self::STRING_VALUE ],
      [6, 'йцукен' ],
      [5, 'giriş' ],
      [6, 'Straße' ],
    ];
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
    $string = new String("Straße");
    $this->assertEquals("ß", $string->charAt(4));
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

    $this->assertEquals("est String", (string)$this->string->substring(1));

    $string = $this->string->substring(2);
    $this->assertEquals("st String", $string->getOriginalValue());

    $string = $this->string->substring($this->string->length());
    $this->assertEquals("", $string->getOriginalValue());

    $string = new String("Straße");
    $string = $string->substring(4, 5);
    $this->assertEquals("ß", $string->getOriginalValue());
  }

  public function testSubstringTwoParams()
  {
    $string = $this->string->substring(0, $this->string->length());
    $this->assertEquals(spl_object_hash($string), spl_object_hash($this->string));

    $string = $this->string->substring(1, $this->string->length());
    $this->assertEquals("est String", $string->getOriginalValue());

    $string = $this->string->substring(1, 4);
    $this->assertEquals("est", $string->getOriginalValue());
    $string = $this->string->subSequence(1, 4);
    $this->assertEquals("est", $string->getOriginalValue());

    $string = $this->string->substring($this->string->length(), $this->string->length());
    $this->assertEquals("", $string->getOriginalValue());

    $string = $this->string->substring(0, 0);
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
      //[-8629, "Ś"],
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

  public function testRegionMatchesMB()
  {
    $this->assertEquals(true, (new String("unicode_ç_c"))->regionMatches(8, new String('ßç_'), 1, 2));
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

  public function testRegionMatchesIgnoreCaseMB()
  {
    $this->assertEquals(true, (new String("unicode_ç_c"))->regionMatchesIgnoreCase(8, new String('ßÇ_'), 1, 2));
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

  /**
   * @param $boolean
   * @param $string
   * @param $offset
   * @dataProvider dataStartsWith
   */
  public function testStartsWith($boolean, $string, $offset)
  {
    $this->assertEquals($boolean, $this->string->startsWith(new String($string), $offset));
  }

  public function dataStartsWith()
  {
    return [
      [true, "Test", 0],
      [true, "String", 5],
      [false, "String", 4],
    ];
  }

  /**
   * @param $boolean
   * @param $string
   * @dataProvider dataEndsWith
   */
  public function testEndsWith($boolean, $string)
  {
    $this->assertEquals($boolean, $this->string->endsWith(new String($string)));
  }

  public function dataEndsWith()
  {
    return [
      [false, "Test"],
      [true, "String"],
      [true, " String"],
      [false, "String "],
    ];
  }

  /**
   * @dataProvider dataCase
   */
  public function testToUpperCase($stringSource, $stringResult)
  {
    $this->assertEquals($stringResult, (new String($stringSource))->toUpperCase()->getOriginalValue());
  }
  /**
   * @dataProvider dataCase
   */
  public function testToLowerCase($stringResult, $stringSource)
  {
    $this->assertEquals($stringResult, (new String($stringSource))->toLowerCase()->getOriginalValue());
  }

  public function dataCase()
  {
    return [
      ['str str', "STR STR"],
      ['çśį', "ÇŚĮ"],
    ];
  }

  public function testToCharArray()
  {
    $this->assertEquals(self::STRING_VALUE, implode('',$this->string->toCharArray()->toArray()));
  }

  public function testJoin()
  {
    $this->assertEquals("", String::join(' ', new \ArrayIterator())->getOriginalValue());
    $this->assertEquals("T Te Test", String::join(' ', new \ArrayIterator(['T', 'Te', 'Test']))->getOriginalValue());
    $this->assertEquals("T Te Test", String::join(' ', new \ArrayIterator(['T', new String('Te'), 'Test']))->getOriginalValue());
  }
  public function testJoinArgs()
  {
    $this->assertEquals("", String::join(' ')->getOriginalValue());
    $this->assertEquals("T Te Test", String::join(' ', 'T', 'Te', 'Test')->getOriginalValue());
    $this->assertEquals("T Te Test", String::join(' ', 'T', new String('Te'), 'Test')->getOriginalValue());
  }

  /**
   * @param $string
   * @param $char
   * @param $position
   *
   * @dataProvider dataLastIndexOf
   */
  public function testLastIndexOf($string, $char, $position, $from)
  {
    $this->assertEquals($position, (new String($string))->lastIndexOf($char, $from));
  }

  public function dataLastIndexOf()
  {
    return [
      ['Straße', 'ß', 4, null],
      ['Straße', new String('ß'), 4, null],
      ['Straße', 'ß', 4, null],
      ['Straße', 'e', 5, null],
      ['Straße', 'e', -1, 4],
      ['Straße', ' ', -1, null],
      ['Se se', 's', 3, null],
      ['Se se', 's', 3, 100],
      ['Se se', 'e', 1, 2],
      ['Se se', 's', -1, -1],
    ];
  }

  /**
   * @param $string
   * @param $char
   *
   * @dataProvider dataLastIndexOfString
   */
  public function testLastIndexOfString($string, $char, $position)
  {
    $pos = String::lastIndexOfString($string, 0, mb_strlen((string)$string), $char, mb_strlen((string)$string));
    $this->assertEquals($position, $pos);
  }

  public function dataLastIndexOfString()
  {
    return [
      ['Straße', 'ß', 4],
      ['Straße', new String('ß'), 4],
      ['Straße', 'ß', 4],
      ['Straße', 'e', 5],
    ];
  }

  /**
   * @param $string
   * @param $char
   * @param $position
   * @dataProvider dataIndexOf
   */
  public function testIndexOf($string, $char, $position)
  {
    $pos = (new String($string))->indexOf($char);
    $this->assertEquals($position, $pos);
  }

  /**
   * @param $string
   * @param $char
   * @param $position
   * @dataProvider dataIndexOf
   */
  public function testIndexOfIgnoreCase($string, $char, $position)
  {
    $pos = (new String($string))->indexOfIgnoreCase(mb_strtoupper((string)$char));
    $this->assertEquals($position, $pos);
    $pos = (new String($string))->indexOfIgnoreCase(mb_strtolower((string)$char));
    $this->assertEquals($position, $pos);
  }

  public function dataIndexOf()
  {
    return [
      ['Straße', 'ß', 4],
      ['Straße', 'ße', 4],
      ['Straße', new String('ß'), 4],
      ['Straße', new String('ße'), 4],
      ['StraßeS', 'S', 0],
      ['StraßeS', 'St', 0],
      ['Straße', 'e', 5],
      ['eStraße', 'e', 0],
      ['Straße', 'ç', -1],
    ];
  }

  public function testConcat()
  {
    $this->assertEquals(self::STRING_VALUE.'0', $this->string->concat(new String('0')));
  }

  public function testReplace()
  {
    $this->assertEquals($this->string, $this->string->replace('T', 'T'));
    $this->assertEquals('0est String', $this->string->replace('T', '0')->getOriginalValue());
  }

  public function testContains()
  {
    $this->assertTrue($this->string->contains('Test'));
    $this->assertTrue($this->string->contains('String'));
    $this->assertFalse($this->string->contains('Bear'));
  }

  public function testSplit()
  {
    $string = new String('abc');
    $array = $string->split('//');
    $this->assertEquals('a',(string)$array[0]);
    $this->assertEquals('b',(string)$array[1]);
    $this->assertEquals('c',(string)$array[2]);
  }

  /**
   * @expectedException \PHPJ\Util\Regex\Exceptions\PatternSyntaxException
   */
  public function testSplitException()
  {
    $string = new String('abc');
    $array = $string->split('//i/');
  }

  /**
   * @param $begin
   * @param $end
   * @param $dstBegin
   *
   * @expectedException \PHPJ\Lang\Exceptions\StringIndexOutOfBoundsException
   * @dataProvider dataGetCharsException
   */
  public function testGetCharsException($begin, $end, $dstBegin = 0)
  {
    $this->string->getCharsFromTo($begin, $end, $dst, $dstBegin);
  }

  public function dataGetCharsException()
  {
    return array_merge($this->dataSubstringException(),[
      [0,2, 100],
    ]);
  }

  /**
   * @dataProvider dataGetChars
   */
  public function testGetChars($srcBegin, $srcEnd, $dst, $dstBegin, $expected)
  {
    $dstReturn = $this->string->getCharsFromTo($srcBegin, $srcEnd, $dst, $dstBegin);
    $this->assertEquals($expected, (string)$dst);
    $this->assertEquals($expected, (string)$dstReturn);
  }

  public function dataGetChars()
  {
    return [
      [0, 3, "\0\0\0", 0, 'Tes'],
      [1, 3, "\0\0", 0, 'es'],
      [2, 3, "\0", 0, 's'],
      [3, 3, "", 0, ''],
      [3, 4, "\0", 0, 't'],
      [0, 3, "ß\0\0", 0, 'Tes'],
      [0, 3, 'ßßß', 0, 'Tes'],
      [0, 3, 'ßßßß', 0, 'Tesß'],
      [0, 3, 'ßßßß', 1, 'ßTes'],
      [0, 3, "ßßßß\0\0", 3, 'ßßßTes'],
    ];
  }

  public function testBuilder()
  {
//    $sb = new StringBuilder(16);
//    $sb->append("string");
//    var_dump($sb->toString());
  }

//  public function testSmpCasePerformance()
//  {
//    $str = str_repeat('Straße', 1000);
//    $t = microtime(true);
//    for($i = 0;$i<100000;$i++){
//      if(is_string($str) && mb_strlen($str) === 1){}
//    }
//    $t1 = microtime(true) - $t;
//    $t = microtime(true);
//    for($i = 0;$i<100000;$i++){
//      if(is_string($str) && $str === mb_substr($str, 0, 1)){}
//    }
//    $t2 = microtime(true) - $t;
//    var_dump($t1, $t2);
//  }

}