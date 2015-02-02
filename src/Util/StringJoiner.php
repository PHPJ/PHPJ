<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Util;

use PHPJ\Lang\Exceptions\NullPointerException;
use PHPJ\Lang\ObjectClass;
use PHPJ\Lang\String;
use PHPJ\Lang\StringBuilder;

final class StringJoiner extends ObjectClass
{

  /** @var \PHPJ\Lang\String */
  private $prefix;
  /** @var \PHPJ\Lang\String */
  private $delimiter;
  /** @var \PHPJ\Lang\String */
  private $suffix;

  /**
   * @var StringBuilder
   * StringBuilder value -- at any time, the characters constructed from the
   * prefix, the added element separated by the delimiter, but without the
   * suffix, so that we can more easily add elements without having to jigger
   * the suffix each time.
   */
  private $value;

  /**
   * @var \PHPJ\Lang\String
   * By default, the string consisting of prefix+suffix, returned by
   * toString(), or properties of value, when no elements have yet been added,
   * i.e. when it is empty.  This may be overridden by the user to be some
   * other value including the empty String.
   */
  private $emptyValue;


  /**
   * Constructs a {@code StringJoiner} with no characters in it using copies
   * of the supplied {@code prefix}, {@code delimiter} and {@code suffix}.
   * If no characters are added to the {@code StringJoiner} and methods
   * accessing the string value of it are invoked, it will return the
   * {@code prefix + suffix} (or properties thereof) in the result, unless
   * {@code setEmptyValue} has first been called.
   *
   * @param  string $delimiter
   *         the sequence of characters to be used between each
   *         element added to the {@code StringJoiner}
   * @param  string $prefix
   *         the sequence of characters to be used at the beginning
   * @param  string $suffix
   *         the sequence of characters to be used at the end
   * @throws NullPointerException if {@code prefix}, {@code delimiter}, or
   *         {@code suffix} is {@code null}
   */
  public function __construct($delimiter, $prefix = null, $suffix = null)
  {
    Objects::requireNonNull($delimiter, "The delimiter must not be null");
    if(null === $prefix xor null === $suffix){
      Objects::requireNonNull($prefix, "The prefix must not be null");
      Objects::requireNonNull($suffix, "The suffix must not be null");
    }
    $this->prefix = new String($prefix);
    $this->delimiter = new String($delimiter);
    $this->suffix = new String($suffix);
    $this->emptyValue = $this->prefix->concat($this->suffix);
  }

  /**
   * Sets the sequence of characters to be used when determining the string
   * representation of this {@code StringJoiner} and no elements have been
   * added yet, that is, when it is empty.  A copy of the {@code emptyValue}
   * parameter is made for this purpose. Note that once an add method has been
   * called, the {@code StringJoiner} is no longer considered empty, even if
   * the element(s) added correspond to the empty {@code String}.
   *
   * @param  $emptyValue string
   *         the characters to return as the value of an empty
   *         {@code StringJoiner}
   * @throws NullPointerException when the {@code emptyValue} parameter is
   *         {@code null}
   * @return $this {@code StringJoiner} itself so the calls may be chained
   */
  public function setEmptyValue($emptyValue)
  {
    Objects::requireNonNull($emptyValue, "The empty value must not be null");
    $this->emptyValue = new String($emptyValue);
    return $this;
  }

  /**
   * Returns the current value, consisting of the {@code prefix}, the values
   * added so far separated by the {@code delimiter}, and the {@code suffix},
   * unless no elements have been added in which case, the
   * {@code prefix + suffix} or the {@code emptyValue} characters are returned
   *
   * @return \PHPJ\Lang\String
   *         the string representation of this {@code StringJoiner}
   */
  public function toString()
  {
    if ($this->value == null) {
      return $this->emptyValue;
    }

    if ($this->suffix->equals(new String(""))) {
      return $this->value->toString();
    }

    $initialLength = $this->value->length();
    $result = $this->value->append($this->suffix)->toString();
    // reset value to pre-append initialLength
    $this->value->setLength($initialLength);
    return $result;

  }

  /**
   * Adds a copy of the given {@code CharSequence} value as the next
   * element of the {@code StringJoiner} value. If {@code newElement} is
   * {@code null}, then {@code "null"} is added.
   *
   * @param  $newElement string
   *         The element to add
   * @return $this a reference to this {@code StringJoiner}
   */
  public function add($newElement)
  {
    $this->prepareBuilder()->append($newElement);
    return $this;
  }

  /**
   * Adds the contents of the given {@code StringJoiner} without prefix and
   * suffix as the next element if it is non-empty. If the given {@code
   * StringJoiner} is empty, the call has no effect.
   *
   * <p>A {@code StringJoiner} is empty if {@link #add(CharSequence) add()}
   * has never been called, and if {@code merge()} has never been called
   * with a non-empty {@code StringJoiner} argument.
   *
   * <p>If the other {@code StringJoiner} is using a different delimiter,
   * then elements from the other {@code StringJoiner} are concatenated with
   * that delimiter and the result is appended to this {@code StringJoiner}
   * as a single element.
   *
   * @param $other StringJoiner
   *        The {@code StringJoiner} whose contents should be merged into this one
   * @throws NullPointerException if the other {@code StringJoiner} is null
   * @return $this {@code StringJoiner}
   */
  public function merge(StringJoiner $other = null)
  {
    Objects::requireNonNull($other);
    if ($other->value !== null) {
      $length = $other->value->length();
      // lock the length so that we can seize the data to be appended
      // before initiate copying to avoid interference, especially when
      // merge 'this'
      $builder = $this->prepareBuilder();
      $builder->append($other->value, $other->prefix->length(), $length);
    }
    return $this;
  }

  private function prepareBuilder()
  {
    if ($this->value !== null) {
      $this->value->append($this->delimiter);
    } else {
      $this->value = (new StringBuilder())->append($this->prefix);
    }
    return $this->value;
  }

  /**
   * Returns the length of the {@code String} representation
   * of this {@code StringJoiner}. Note that if
   * no add methods have been called, then the length of the {@code String}
   * representation (either {@code prefix + suffix} or {@code emptyValue})
   * will be returned. The value should be equivalent to
   * {@code toString().length()}.
   *
   * @return int
   *         the length of the current value of {@code StringJoiner}
   */
  public function length()
  {
    // Remember that we never actually append the suffix unless we return
    // the full (present) value or some sub-string or length of it, so that
    // we can add on more if we need to.
    return $this->value !== null
      ? $this->value->length() + $this->suffix->length()
      : $this->emptyValue->length();
  }

}