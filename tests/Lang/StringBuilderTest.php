<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Tests\Lang;


use PHPJ\Lang\StringBuilder;
use PHPJ\Tests\Test;

class StringBuilderTest extends Test{

  protected function getClassName()
  {
    return '\PHPJ\Lang\StringBuilder';
  }

  public function testLengthCapacity()
  {
    $sb = new StringBuilder();
    $this->assertEquals(0, $sb->length());
    $this->assertEquals(16, $sb->capacity());
    $this->assertEquals("", $sb->toString()->getOriginalValue());

    $sb->append("My Straße");
    $this->assertEquals(9, $sb->length());
    $this->assertEquals(16, $sb->capacity());
    $this->assertEquals("My Straße", $sb->toString()->getOriginalValue());

    $sb->append("123456789");
    $this->assertEquals(18, $sb->length());
    $this->assertEquals(34, $sb->capacity());
  }

  public function testEnsureCapacity(){
    $sb = new StringBuilder();
    $this->assertEquals(16, $sb->capacity());
    $sb->ensureCapacity(-1);
    $this->assertEquals(16, $sb->capacity());
    $sb->ensureCapacity(16);
    $this->assertEquals(16, $sb->capacity());
    $sb->ensureCapacity(20);
    $this->assertEquals(34, $sb->capacity());

    $sb->ensureCapacity(100);
    $this->assertEquals(100, $sb->capacity());

    $sb = new StringBuilder();
    $sb->ensureCapacity(200);
    $this->assertEquals(200, $sb->capacity());
  }

  public function testTrimToSize()
  {
    $sb = new StringBuilder(100);
    $this->assertEquals(100, $sb->capacity());
    $this->assertEquals(0, $sb->length());
    $sb->trimToSize();
    $this->assertEquals(0, $sb->capacity());
    $this->assertEquals(0, $sb->length());


  }

  /**
   * @expectedException \PHPJ\Lang\Exceptions\StringIndexOutOfBoundsException
   */
  public function testSetLengthException()
  {
    $sb = new StringBuilder();
    $sb->setLength(-1);
  }

  public function testSetLength()
  {
    $sb = new StringBuilder();
    $sb->setLength(200);
    $this->assertEquals(200, $sb->capacity());
    $this->assertEquals(200, $sb->length());

    $sb = new StringBuilder();
    $sb->setLength(20);
    $this->assertEquals(34, $sb->capacity());
    $this->assertEquals(20, $sb->length());
  }

  /**
   * @expectedException \PHPJ\Lang\Exceptions\StringIndexOutOfBoundsException
   */
  public function testCharAtException()
  {
    $sb = new StringBuilder();
    $sb->charAt(0);
  }

  public function testCharAt()
  {
    $sb = new StringBuilder();
    $sb->append("String");
    $this->assertEquals("S", $sb->charAt(0));
    $this->assertEquals("g", $sb->charAt(5));
  }

  public function testAppend()
  {
    $sb = new StringBuilder();
    $sb->append('My');
    $sb->append(' ');
    $sb->append('Value');
    $this->assertEquals("My Value", $sb->toString()->getOriginalValue());
    $this->assertEquals("My Value", (string)$sb);
  }

  public function testAppendNull()
  {
    $sb = new StringBuilder();
    $sb->append('This');
    $sb->append(' ');
    $sb->append('is ');
    $sb->append(null);
    $sb->append(' and ');
    $sb->append(true);
    $sb->append(' and ');
    $sb->append(false);
    $this->assertEquals("This is null and true and false", $sb->toString()->getOriginalValue());
    $this->assertEquals("This is null and true and false", (string)$sb);
  }

  public function testDelete()
  {
    $sb = new StringBuilder();
    $sb->append('This');
    $sb->delete(1,3);
    $this->assertEquals('Ts', (string)$sb);
  }

  public function testAppendCodePoint()
  {
    $sb = new StringBuilder();
    $sb->appendCodePoint(97);
    $this->assertEquals('a', (string)$sb);
    $sb->appendCodePoint(223);
    $this->assertEquals('aß', (string)$sb);

    //$string = '↗';
    //$sb->appendCodePoint(14845591);
    //$this->assertEquals('a↗', (string)$sb);
  }
}