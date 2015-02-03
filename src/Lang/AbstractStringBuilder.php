<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Lang;

use PHPJ\Lang\Exceptions\IndexOutOfBoundsException;
use PHPJ\Lang\Exceptions\StringIndexOutOfBoundsException;
use PHPJ\Lang\Interfaces\Appendable;
use PHPJ\Lang\Interfaces\CharSequence;
use PHPJ\Util\Arrays;

class AbstractStringBuilder extends ObjectClass implements Appendable, CharSequence
{
  /**
   * @var CharArray
   * The value is used for character storage.
   */
  protected $value;

  /**
   * @var int
   * The count is the number of characters used.
   */
  protected $count = 0;

  /**
   * @param int $capacity
   */
  public function __construct($capacity)
  {
    $capacity = (int)$capacity;
    $this->value = new CharArray($capacity);
  }

  /**
   * Returns the length (character count).
   *
   * @return  int
   *          the length of the sequence of characters currently
   *          represented by this object
   */
  public function length()
  {
    return $this->count;
  }

  /**
   * Returns the current capacity. The capacity is the amount of storage
   * available for newly inserted characters, beyond which an allocation
   * will occur.
   *
   * @return  int
   *          the current capacity
   */
  public function capacity()
  {
    return $this->value->getSize();
  }

  /**
   * Ensures that the capacity is at least equal to the specified minimum.
   * If the current capacity is less than the argument, then a new internal
   * array is allocated with greater capacity. The new capacity is the
   * larger of:
   * <ul>
   * <li>The {@code minimumCapacity} argument.
   * <li>Twice the old capacity, plus {@code 2}.
   * </ul>
   * If the {@code minimumCapacity} argument is nonpositive, this
   * method takes no action and simply returns.
   * Note that subsequent operations on this object can reduce the
   * actual capacity below that requested here.
   *
   * @param   $minimumCapacity int
   *          the minimum desired capacity.
   */
  public function ensureCapacity($minimumCapacity)
  {
    if ($minimumCapacity > 0) {
      $this->ensureCapacityInternal($minimumCapacity);
    }
  }

  /**
   * This method has the same contract as ensureCapacity, but is
   * never synchronized.
   * @param $minimumCapacity int
   */
  private function ensureCapacityInternal($minimumCapacity)
  {
    // overflow-conscious code
    if ($minimumCapacity - $this->capacity() > 0) {
      $this->expandCapacity($minimumCapacity);
    }
  }

  /**
   * This implements the expansion semantics of ensureCapacity with no
   * size check or synchronization.
   * @param $minimumCapacity int
   */
  public function expandCapacity($minimumCapacity)
  {
    $newCapacity = $this->capacity() * 2 + 2;
    if ($newCapacity - $minimumCapacity < 0) {
      $newCapacity = $minimumCapacity;
    }
    //if ($newCapacity < 0) {
    //  $newCapacity = PHP_INT_MAX;
    //}
    //$this->value = Arrays::copyOfFixedArray($this->value, $newCapacity);
    $this->value->setSize($newCapacity);
  }

  /**
   * Attempts to reduce storage used for the character sequence.
   * If the buffer is larger than necessary to hold its current sequence of
   * characters, then it may be resized to become more space efficient.
   * Calling this method may, but is not required to, affect the value
   * returned by a subsequent call to the {@link #capacity()} method.
   */
  public function trimToSize()
  {
    if ($this->length() < $this->capacity()) {
      //$this->value = Arrays::copyOfFixedArray($this->value, $this->count);
      $this->value->setSize($this->length());
    }
  }

  /**
   * Sets the length of the character sequence.
   * The sequence is changed to a new character sequence
   * whose length is specified by the argument. For every nonnegative
   * index <i>k</i> less than {@code newLength}, the character at
   * index <i>k</i> in the new character sequence is the same as the
   * character at index <i>k</i> in the old sequence if <i>k</i> is less
   * than the length of the old character sequence; otherwise, it is the
   * null character {@code '\0'}.
   *
   * In other words, if the {@code newLength} argument is less than
   * the current length, the length is changed to the specified length.
   * <p>
   * If the {@code newLength} argument is greater than or equal
   * to the current length, sufficient null characters
   * ({@code '\0'}) are appended so that
   * length becomes the {@code newLength} argument.
   * <p>
   * The {@code newLength} argument must be greater than or equal
   * to {@code 0}.
   *
   * @param      $newLength   int
   *             the new length
   * @throws     StringIndexOutOfBoundsException  if the
   *               {@code newLength} argument is negative.
   */
  public function setLength($newLength)
  {
    if ($newLength < 0) {
      throw new StringIndexOutOfBoundsException($newLength);
    }
    $this->ensureCapacityInternal($newLength);
    if ($this->length() < $newLength) {
      Arrays::fillFromTo($this->value, $this->length(), $newLength, "\0");
    }
    $this->count = $newLength;
  }


  /**
   * @param int $index
   * @return string
   * @override
   */
  public function charAt($index)
  {
    $index = (int)$index;
    if (($index < 0) || ($index >= $this->length())) {
      throw new StringIndexOutOfBoundsException($index);
    }
    return $this->value->offsetGet($index);
  }

  /**
   * Characters are copied from this sequence into the
   * destination character array {@code dst}. The first character to
   * be copied is at index {@code srcBegin}; the last character to
   * be copied is at index {@code srcEnd-1}. The total number of
   * characters to be copied is {@code srcEnd-srcBegin}. The
   * characters are copied into the subarray of {@code dst} starting
   * at index {@code dstBegin} and ending at index:
   * <pre>{@code
   * dstbegin + (srcEnd-srcBegin) - 1
   * }</pre>
   *
   * @param      int $srcBegin start copying at this offset.
   * @param      int $srcEnd stop copying at this offset.
   * @param      string $dst the string to copy the data into.
   * @param      int $dstBegin offset into {@code dst}.
   * @return     string
   * @throws     StringIndexOutOfBoundsException  if any of the following is true:
   *             <ul>
   *             <li>{@code srcBegin} is negative
   *             <li>{@code dstBegin} is negative
   *             <li>the {@code srcBegin} argument is greater than
   *             the {@code srcEnd} argument.
   *             <li>{@code srcEnd} is greater than
   *             {@code this.length()}.
   *             <li>{@code dstBegin+srcEnd-srcBegin} is greater than
   *             {@code dst.length}
   *             </ul>
   */
  public function getChars($srcBegin, $srcEnd, &$dst, $dstBegin)
  {
    $this->checkSrcBeginEnd($this->value, $srcBegin, $srcEnd);

    $dst = preg_split('//u', $dst, 0, PREG_SPLIT_NO_EMPTY);
    for ($i = 0; $i < $srcEnd - $srcBegin; $i++) {
      $dst[$i + $dstBegin] = $this->value[$i + $srcBegin];
    }
    return $dst = implode('', $dst);
  }

  /**
   * @param CharArray $src
   * @param int $srcBegin
   * @param int $srcEnd
   */
  protected function checkSrcBeginEnd(CharArray $src, $srcBegin, $srcEnd)
  {
    if ($srcBegin < 0) {
      throw new StringIndexOutOfBoundsException($srcBegin);
    }
    if ($srcEnd < 0 || $srcEnd > $src->length()) {
      throw new StringIndexOutOfBoundsException($srcEnd);
    }
    if ($srcBegin > $srcEnd) {
      throw new StringIndexOutOfBoundsException("srcBegin is greater than srcEnd");
    }
  }

  /**
   * The character at the specified index is set to {@code ch}. This
   * sequence is altered to represent a new character sequence that is
   * identical to the old character sequence, except that it contains the
   * character {@code ch} at position {@code index}.
   * <p>
   * The index argument must be greater than or equal to
   * {@code 0}, and less than the length of this sequence.
   *
   * @param      int $index the index of the character to modify.
   * @param      string $ch the new character.
   * @throws     StringIndexOutOfBoundsException  if {@code index} is
   *             negative or greater than or equal to {@code length()}.
   * @todo refactor
   */
  public function setCharAt($index, $ch)
  {
    if ($index < 0 || $index >= $this->length()) {
      throw new StringIndexOutOfBoundsException($index);
    }
    $this->value->offsetSet($index, $ch);
  }

  /**
   * @param $string string
   * @param $start  int [optional]
   * @param $end    int [optional]
   * @todo start end
   * @return $this
   */
  public function append($string, $start = null, $end = null)
  {
    if (null === $string) {
      return $this->appendNull();
    }
    $string = $this->getAppendStringValue($string);
    $len = $string->length();
    $this->ensureCapacityInternal($this->length() + $len);
    $string->getCharsFromTo(0, $len, $this->value, $this->length());
    $this->count += $len;
    return $this;
  }

  protected function appendNull()
  {
    $c = $this->length();
    $this->ensureCapacityInternal($c + 4);
    $this->value[$c++] = 'n';
    $this->value[$c++] = 'u';
    $this->value[$c++] = 'l';
    $this->value[$c++] = 'l';
    $this->count = $c;
    return $this;
  }

  protected function getAppendStringValue($string)
  {
    if ($string instanceof String) {
      return $string;
    }
    if (is_bool($string)) {
      return new String($string ? 'true' : 'false');
    }

    return new String((string)$string);
  }

  /**
   * Appends the string representation of the {@code codePoint}
   * argument to this sequence.
   *
   * @todo there is behavioral difference between Java and PHP
   * (eg.: 233 = ß in PHP and something other in Java)
   *
   * @param   int $codePoint
   *          a Unicode code point
   * @return  $this
   *          a reference to this object.
   */
  public function appendCodePoint($codePoint)
  {
    $char = mb_convert_encoding('&#' . intval($codePoint) . ';', 'UTF-8', 'HTML-ENTITIES');
    //$char = hex2bin(dechex($codePoint));
    $count = $this->length();
    $this->ensureCapacityInternal($count + 1);
    $this->value[$count] = $char;
    ++$this->count;
    return $this;

  }

  /**
   * Removes the characters in a substring of this sequence.
   * The substring begins at the specified {@code start} and extends to
   * the character at index {@code end - 1} or to the end of the
   * sequence if no such character exists. If
   * {@code start} is equal to {@code end}, no changes are made.
   *
   * @param      int $start The beginning index, inclusive.
   * @param      int $end The ending index, exclusive.
   * @return     $this object.
   * @throws     StringIndexOutOfBoundsException  if {@code start}
   *             is negative, greater than {@code length()}, or
   *             greater than {@code end}.
   */
  public function delete($start, $end)
  {
    $start = (int)$start;
    $end = (int)$end;
    if ($start < 0) {
      throw new StringIndexOutOfBoundsException($start);
    }
    if ($end > $this->length()) {
      $end = $this->length();
    }
    if ($start > $end) {
      throw new StringIndexOutOfBoundsException();
    }
    $len = $end - $start;
    if ($len > 0) {
      System::arraycopy($this->value, $start + $len, $this->value, $start, $this->length() - $end);
      $this->count -= $len;
    }
    $this->trimToSize();
    return $this;
  }

  /**
   * Removes the {@code char} at the specified position in this
   * sequence. This sequence is shortened by one {@code char}.
   *
   * <p>Note: If the character at the given index is a supplementary
   * character, this method does not remove the entire character. If
   * correct handling of supplementary characters is required,
   * determine the number of {@code char}s to remove by calling
   * {@code Character.charCount(thisSequence.codePointAt(index))},
   * where {@code thisSequence} is this sequence.
   *
   * @param       int $index
   *              Index of {@code char} to remove
   * @return      $this
   * @throws      StringIndexOutOfBoundsException
   *              if the {@code index}
   *              is negative or greater than or equal to
   *              {@code length()}.
   */
  public function deleteCharAt($index)
  {
    $index = (int)$index;
    if (($index < 0) || ($index >= $this->length())) {
      throw new StringIndexOutOfBoundsException($index);
    }
    System::arraycopy($this->value, $index + 1, $this->value, $index, $this->length() - $index - 1);
    $this->count--;
    $this->trimToSize();
    return $this;
  }

  /**
   * @param int $start
   * @param int $end
   * @param \PHPJ\Lang\String $str
   * @return $this
   */
  public function replace($start, $end, String $str)
  {
    if ($start < 0) {
      throw new StringIndexOutOfBoundsException($start);
    }
    if ($start > $this->length()) {
      throw new StringIndexOutOfBoundsException("start is greater than length()");
    }
    if ($start > $end) {
      throw new StringIndexOutOfBoundsException("start is greater than end");
    }

    if ($end > $this->length()) {
      $end = $this->length();
    }
    $len = $str->length();
    $newCount = $this->length() + $len - ($end - $start);
    $this->ensureCapacityInternal($newCount);

    System::arraycopy($this->value, $end, $this->value, $start + $len, $this->length() - $end);
    $str->getChars($this->value, $start);
    $this->count = (int)$newCount;
    $this->trimToSize();
    return $this;
  }

  /**
   * Returns a new {@code String} that contains a subsequence of
   * characters currently contained in this sequence. The
   * substring begins at the specified {@code start} and
   * extends to the character at index {@code end - 1}.
   *
   * @param      int $start The beginning index, inclusive.
   * @param      int $end [Optional] The ending index, exclusive.
   * @return     \PHPJ\Lang\String
   *             The new string.
   * @throws     StringIndexOutOfBoundsException  if {@code start}
   *             or {@code end} are negative or greater than
   *             {@code length()}, or {@code start} is
   *             greater than {@code end}.
   */
  public function substring($start, $end = null)
  {
    $end = $end === null ? $this->length() : $end;
    $start = (int)$start;
    $end = (int)$end;
    if ($start < 0) {
      throw new StringIndexOutOfBoundsException($start);
    }
    if ($end > $this->length()) {
      throw new StringIndexOutOfBoundsException($end);
    }
    if ($start > $end) {
      throw new StringIndexOutOfBoundsException($end - $start);
    }
    return new String($this->value, $start, $end - $start);
  }

  /**
   * @param   $start   int - the start index, inclusive
   * @param   $end     int - the end index, exclusive
   *
   * @return  CharSequence
   *          the specified subsequence
   *
   * @throws  StringIndexOutOfBoundsException
   *          if <tt>start</tt> or <tt>end</tt> are negative,
   *          if <tt>end</tt> is greater than <tt>length()</tt>,
   *          or if <tt>start</tt> is greater than <tt>end</tt>
   */
  public function subSequence($start, $end)
  {
    return $this->substring($start, $end);
  }

  /**
   * @param $dstOffset int
   * @param null $str CharSequence|string
   * @param null $start int [optional]
   * @param null $end int [optional]
   *
   * @return $this
   */
  public function insert($dstOffset, $str, $start = null, $end = null)
  {
    if ($dstOffset < 0 || $dstOffset > $this->length()) {
      throw new StringIndexOutOfBoundsException($dstOffset);
    }

    $str = $this->processStr($str);

    if ($str->length() === 1) {
      return $this->insertChar($dstOffset, $str);
    }

    if (is_integer($start) && is_integer($end)) {
      $str = $this->processStrStartEnd($str, $start, $end);
    }

    return $this->insertCharArray($dstOffset, $str);
  }

  /**
   * @param $str
   * @return CharArray
   */
  protected function processStr($str)
  {
    if($str instanceof CharArray){
      return $str;
    }
    if(null === $str){
      $str = 'null';
    }
    if(is_bool($str)){
      $str = $str ? 'true' : 'false';
    }

    return CharArray::fromString($str);
  }

  protected function insertChar($dstOffset, $char)
  {
    $this->ensureCapacityInternal($this->length() + 1);
    System::arraycopy($this->value, $dstOffset, $this->value, $dstOffset + 1, $this->length() - $dstOffset);
    $this->value[$dstOffset] = (string)$char;
    $this->count++;
    $this->trimToSize();
    return $this;
  }

  protected function insertCharArray($dstOffset, CharArray $str)
  {
    $start = 0;
    $end = $len = $str->length();
    $this->ensureCapacityInternal($this->length() + $len);
    System::arraycopy($this->value, $dstOffset, $this->value, $dstOffset + $len, $this->length() - $dstOffset);
    for ($i = $start; $i < $end; $i++) {
      $this->value[$dstOffset++] = $str[$i];
    }
    $this->count += $len;
    return $this;
  }

  /**
   * @param CharArray $str
   * @param int $start
   * @param int $end
   * @return CharArray
   */
  protected function processStrStartEnd(CharArray $str, $start, $end)
  {
    $this->checkSrcBeginEnd($str, $start, $end);

    $len = $end - $start;
    $strCut = new CharArray($len);
    $str = System::arraycopy($str, $start, $strCut, 0, $len);
    return $str;
  }

  /**
   * Returns the index within this string of the first occurrence of the
   * specified substring, starting at the specified index.  The integer
   * returned is the smallest value {@code k} for which:
   * <pre>{@code
   *     k >= Math.min(fromIndex, this.length()) &&
   *                   this.toString().startsWith(str, k)
   * }</pre>
   * If no such value of <i>k</i> exists, then -1 is returned.
   *
   * @param   \PHPJ\Lang\String str
   *          the substring for which to search.
   * @param   int $fromIndex [Optional]
   *          the index from which to start the search.
   * @return  int
   *          the index within this string of the first occurrence of the
   *          specified substring, starting at the specified index.
   */
  public function indexOf(String $str, $fromIndex = null)
  {
    return (new String($this->value))->indexOf($str, $fromIndex);
  }

  /**
   * Returns the index within this string of the last occurrence of the
   * specified substring. The integer returned is the largest value <i>k</i>
   * such that:
   * <pre>{@code
   *     k <= Math.min(fromIndex, this.length()) &&
   *                   this.toString().startsWith(str, k)
   * }</pre>
   * If no such value of <i>k</i> exists, then -1 is returned.
   *
   * @param   \PHPJ\Lang\String str
   *          the substring for which to search.
   * @param   int $fromIndex [Optional]
   *          the index from which to start the search.
   * @return  int
   *          the index within this string of the first occurrence of the
   *          specified substring, starting at the specified index.
   */
  public function lastIndexOf(String $str, $fromIndex = null)
  {
    return (new String($this->value))->lastIndexOf($str, $fromIndex);
  }

  /**
   * Causes this character sequence to be replaced by the reverse of
   * the sequence.
   *
   * @return  $this reference to this object.
   */
  public function reverse()
  {
    $n = $this->length() - 1;
    for ($j = ($n - 1) >> 1; $j >= 0; $j--) {
      $k = $n - $j;
      $cj = $this->value[$j];
      $ck = $this->value[$k];
      $this->value[$j] = $ck;
      $this->value[$k] = $cj;
    }
    return $this;
  }


  /**
   * @return  \PHPJ\Lang\String - a string consisting of exactly this sequence of characters
   */
  public function toString()
  {
    return $this->length()
      ? new String($this->value->toString())
      : new String();
  }
}