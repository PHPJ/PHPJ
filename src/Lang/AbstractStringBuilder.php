<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Lang;


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

  /** @var int */
  protected $capacity;

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
    $this->value = Arrays::copyOfFixedArray($this->value, $newCapacity);
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
    if ($this->count < $this->capacity()) {
      $this->value = Arrays::copyOfFixedArray($this->value, $this->count);
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
    if($newLength < 0){
      throw new StringIndexOutOfBoundsException($newLength);
    }
    $this->ensureCapacityInternal($newLength);
    if($this->count < $newLength){
      Arrays::fillFromTo($this->value, $this->count, $newLength, "\0");
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
   * @param      int $srcBegin   start copying at this offset.
   * @param      int $srcEnd     stop copying at this offset.
   * @param      string $dst     the string to copy the data into.
   * @param      int $dstBegin   offset into {@code dst}.
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
  public function getChars($srcBegin, $srcEnd, &$dst, $dstBegin){
    if ($srcBegin < 0)
      throw new StringIndexOutOfBoundsException($srcBegin);
    if (($srcEnd < 0) || ($srcEnd > $this->count))
      throw new StringIndexOutOfBoundsException($srcEnd);
    if ($srcBegin > $srcEnd)
      throw new StringIndexOutOfBoundsException("srcBegin > srcEnd");

    $dst = preg_split('//u', $dst, 0, PREG_SPLIT_NO_EMPTY);
    for($i = 0; $i < $srcEnd - $srcBegin; $i ++){
      $dst[$i + $dstBegin] = $this->value[$i + $srcBegin];
    }
    return $dst = implode('', $dst);
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
   * @param      int $index   the index of the character to modify.
   * @param      string $ch      the new character.
   * @throws     StringIndexOutOfBoundsException  if {@code index} is
   *             negative or greater than or equal to {@code length()}.
   * @todo refactor
   */
  public function setCharAt($index, $ch)
  {
    if (($index < 0) || ($index >= $this->length())) {
      throw new StringIndexOutOfBoundsException($index);
    }
    $this->value->offsetSet($index, $ch);
  }

  /**
   * @param $string string
   * @param $start  int
   * @param $end    int
   * @return Appendable
   */
  public function append($string, $start = null, $end = null)
  {
    $string = $string instanceof String ? $string : new String($string);
    $len = $string->length();
    $this->ensureCapacityInternal($this->length() + $len);
    $string->getChars(0, $len, $this->value, $this->length());
    $this->count += $len;
    return $this;
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
    // TODO: Implement subSequence() method.
  }

  /**
   * @return  \PHPJ\Lang\String - a string consisting of exactly this sequence of characters
   */
  public function toString()
  {
    return $this->count
      ? new String($this->value->toString())
      : new String();
  }
}