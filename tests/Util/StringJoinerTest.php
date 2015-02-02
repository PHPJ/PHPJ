<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Tests\Util;


use PHPJ\Tests\Test;
use PHPJ\Util\StringJoiner;

class StringJoinerTest extends Test
{

  protected function getClassName()
  {
    return '\PHPJ\Util\StringJoiner';
  }

  public function testJoin()
  {
    $sj = new StringJoiner(" Delim ");
    $sj->add("First");
    $sj->add("Second");
    $this->assertEquals("First Delim Second", $sj->toString()->getOriginalValue());
  }

  public function testJoinPostPre()
  {
    $sj = new StringJoiner("Delim", "Pre", "Post");
    $sj->add("_String_");
    $sj->add("_Second_");
    $this->assertEquals("Pre_String_Delim_Second_Post", $sj->toString()->getOriginalValue());
  }

  public function testMerge()
  {
    $sj = new StringJoiner("_");
    $sj->add("1");
    $sj->add("2");

    $sj1 = new StringJoiner("=");
    $sj1->add("3");
    $sj1->add("4");

    $sj->merge($sj1);
    $this->assertEquals("1_2_3=4", (string)$sj);
  }

  public function testLength(){
    $sj = new StringJoiner("_");
    $this->assertEquals(0, $sj->length());

    $sj = new StringJoiner("_", ">","<");
    $this->assertEquals(2, $sj->length());

    $sj = new StringJoiner("_");
    $sj->add("1");
    $sj->add("2");
    $this->assertEquals(3, $sj->length());
  }

  /**
   * @expectedException \PHPJ\Lang\Exceptions\NullPointerException
   * @expectedExceptionMessage The delimiter must not be null
   */
  public function testException1()
  {
    $sj = new StringJoiner(null);
  }

  /**
   * @expectedException \PHPJ\Lang\Exceptions\NullPointerException
   * @expectedExceptionMessage The suffix must not be null
   */
  public function testException2()
  {
    $sj = new StringJoiner("str", "pre");
  }

  /**
   * @expectedException \PHPJ\Lang\Exceptions\NullPointerException
   * @expectedExceptionMessage The prefix must not be null
   */
  public function testException3()
  {
    $sj = new StringJoiner("str", null, "post");
  }
}