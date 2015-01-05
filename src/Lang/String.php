<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Lang;

use PHPJ\Lang\Exceptions\StringIndexOutOfBoundsException;
use PhpOption\Option;

/**
 * Class String
 * @package PHPJ\Lang
 * @todo implement Interfaces
 */
class String extends ObjectClass
{

  /**
   * @var string
   */
  private $value;

  /** @var  int */
  private $length;

  /** @var  int */
  private $hash = 0;

  /**
   * @param string $string
   */
  public function __construct($string = '')
  {
    $this->value = (string)$string;
  }

  public function getOriginalValue()
  {
    return $this->value;
  }

  /**
   * Returns the length of this string.
   * The length is equal to the number of <a href="Character.html#unicode">Unicode
   * code units</a> in the string.
   *
   * @return int the length of the sequence of characters represented by this
   *          object.
   */
  public function length()
  {
    return Option::fromValue($this->length)->getOrCall(function () {
      return $this->length = strlen($this->value);
    });
  }

  /**
   * Returns {@code true} if, and only if, {@link #length()} is {@code 0}.
   *
   * @return boolean {@code true} if {@link #length()} is {@code 0}, otherwise
   * {@code false}
   *
   * @since 1.6
   */
  public function isEmpty()
  {
    return empty($this->value);
  }

  /**
   * Returns the {@code char} value at the
   * specified index. An index ranges from {@code 0} to
   * {@code length() - 1}. The first {@code char} value of the sequence
   * is at index {@code 0}, the next at index {@code 1},
   * and so on, as for array indexing.
   *
   * <p>If the {@code char} value specified by the index is a
   * <a href="Character.html#unicode">surrogate</a>, the surrogate
   * value is returned.
   *
   * @param      int $index the index of the {@code char} value.
   * @return     string the {@code char} value at the specified index of this string.
   *             The first {@code char} value is at index {@code 0}.
   * @exception  StringIndexOutOfBoundsException  if the {@code index}
   *             argument is negative or not less than the length of this
   *             string.
   */
  public function charAt($index)
  {
    if (($index < 0) || ($index >= $this->length())) {
      throw new StringIndexOutOfBoundsException($index);
    }
    return $this->value[$index];
  }


  /**
   * Returns a string whose value is this string, with any leading and trailing
   * whitespace removed.
   * <p>
   * If this {@code String} object represents an empty character
   * sequence, or the first and last characters of character sequence
   * represented by this {@code String} object both have codes
   * greater than {@code '\u005Cu0020'} (the space character), then a
   * reference to this {@code String} object is returned.
   * <p>
   * Otherwise, if there is no character with a code greater than
   * {@code '\u005Cu0020'} in the string, then a
   * {@code String} object representing an empty string is
   * returned.
   * <p>
   * Otherwise, let <i>k</i> be the index of the first character in the
   * string whose code is greater than {@code '\u005Cu0020'}, and let
   * <i>m</i> be the index of the last character in the string whose code
   * is greater than {@code '\u005Cu0020'}. A {@code String}
   * object is returned, representing the substring of this string that
   * begins with the character at index <i>k</i> and ends with the
   * character at index <i>m</i>-that is, the result of
   * {@code this.substring(k, m + 1)}.
   * <p>
   * This method may be used to trim whitespace (as defined above) from
   * the beginning and end of a string.
   *
   * @return  \PHPJ\Lang\String A string whose value is this string, with any leading and trailing white
   *          space removed, or this string if it has no leading or
   *          trailing white space.
   */
  public function trim()
  {
    return new String(trim($this->value));
    //                  int len = value . length;
    //        int st = 0;
    //        char[] val = value;    /* avoid getfield opcode */
    //
    //        while ((st < len) && (val{
    //          [st] <= ' '})) {
    //  st++;
    //}
    //        while ((st < len) && (val{
    //          [len - 1] <= ' '})) {
    //  len--;
    //}
    //        return ((st > 0) || (len < value . length)) ? substring(st, len) : this;
  }

  /**
   * Returns a string that is a substring of this string.
   * The substring begins at the specified {@code beginIndex}.
   * In case {@code endIndex} is not specified the substring
   * extends to the end of this string, otherwise
   * extends to the character at index {@code endIndex - 1}.
   * Thus the length of the substring is {@code endIndex-beginIndex}.
   * <p>
   * Examples:
   * <blockquote><pre>
   * "unhappy".substring(2) returns "happy"
   * "Harbison".substring(3) returns "bison"
   * "emptiness".substring(9) returns "" (an empty string)
   * "hamburger".substring(4, 8) returns "urge"
   * "smiles".substring(1, 5) returns "mile"
   * </pre></blockquote>
   *
   * @param      $beginIndex   int - the beginning index, inclusive.
   * @param      $endIndex     int - the ending index, exclusive.
   * @return     \PHPJ\Lang\String the specified substring.
   * @exception  StringIndexOutOfBoundsException  if the
   *             {@code beginIndex} is negative, or
   *             {@code endIndex} is larger than the length of
   *             this {@code String} object, or
   *             {@code beginIndex} is larger than
   *             {@code endIndex}.
   */
  public function substring($beginIndex, $endIndex = null)
  {
    if ($beginIndex < 0) {
      throw new StringIndexOutOfBoundsException($beginIndex);
    }
    $endIndex = $endIndex ?: $this->length();
    if ($endIndex > $this->length()) {
      throw new StringIndexOutOfBoundsException($endIndex);
    }
    $subLen = $endIndex - $beginIndex;
    if ($subLen < 0) {
      throw new StringIndexOutOfBoundsException($subLen);
    }
    return (($beginIndex == 0) && ($endIndex == $this->length()))
      ? $this
      : new String(substr($this->value, $beginIndex, $subLen));
  }

  /**
   * This object (which is already a string!) is itself returned.
   *
   * @return \PHPJ\Lang\String the string itself.
   */
  public function toString()
  {
    return $this;
  }

  /**
   * Returns a hash code for this string. The hash code for a
   * {@code String} object is computed as
   * <blockquote><pre>
   * s[0]*31^(n-1) + s[1]*31^(n-2) + ... + s[n-1]
   * </pre></blockquote>
   * using {@code int} arithmetic, where {@code s[i]} is the
   * <i>i</i>th character of the string, {@code n} is the length of
   * the string, and {@code ^} indicates exponentiation.
   * (The hash value of the empty string is zero.)
   *
   * Java involves 32-bit arithmetic overflow, in PHP we use {@code & 0xffffffff} for that
   *
   * @return  int - a hash code value for this object.
   */
    public function hashCode(){
      $h = $this->hash;
      if ($h == 0 && $this->length() > 0) {
        for($i = 0; $i < $this->length(); $i++){
          $h = (int)(31 * $h + ord($this->value[$i])) & 0xffffffff;
        }
        $this->hash = $h;
      }
      return $h;
    }

  /**
   * Compares this string to the specified object.  The result is {@code
   * true} if and only if the argument is not {@code null} and is a {@code
   * String} object that represents the same sequence of characters as this
   * object.
   *
   * @param  \PHPJ\Lang\Object anObject
   *         The object to compare this {@code String} against
   *
   * @return  boolean {@code true} if the given object represents a {@code String}
   *          equivalent to this string, {@code false} otherwise
   *
   * @see  \PHPJ\Lang\String::compareTo(String)
   * @see  \PHPJ\Lang\String::equalsIgnoreCase(String)
   */
  public function equals(Object $anObject = null)
  {
    if ($this === $anObject) {
      return true;
    }
    if ($anObject instanceof String) {
      return $this->value === $anObject->value;
    }
    return false;
  }

  /**
   * Compares two strings lexicographically.
   * The comparison is based on the Unicode value of each character in
   * the strings. The character sequence represented by this
   * {@code String} object is compared lexicographically to the
   * character sequence represented by the argument string. The result is
   * a negative integer if this {@code String} object
   * lexicographically precedes the argument string. The result is a
   * positive integer if this {@code String} object lexicographically
   * follows the argument string. The result is zero if the strings
   * are equal; {@code compareTo} returns {@code 0} exactly when
   * the {@link #equals(Object)} method would return {@code true}.
   * <p>
   * This is the definition of lexicographic ordering. If two strings are
   * different, then either they have different characters at some index
   * that is a valid index for both strings, or their lengths are different,
   * or both. If they have different characters at one or more index
   * positions, let <i>k</i> be the smallest such index; then the string
   * whose character at position <i>k</i> has the smaller value, as
   * determined by using the &lt; operator, lexicographically precedes the
   * other string. In this case, {@code compareTo} returns the
   * difference of the two character values at position {@code k} in
   * the two string -- that is, the value:
   * <blockquote><pre>
   * this.charAt(k)-anotherString.charAt(k)
   * </pre></blockquote>
   * If there is no index position at which they differ, then the shorter
   * string lexicographically precedes the longer string. In this case,
   * {@code compareTo} returns the difference of the lengths of the
   * strings -- that is, the value:
   * <blockquote><pre>
   * this.length()-anotherString.length()
   * </pre></blockquote>
   *
   * @param   \PHPJ\Lang\String $anotherString the {@code String} to be compared.
   * @param   boolean $fast
   *
   * @return  int - the value {@code 0} if the argument string is equal to
   *          this string; a value less than {@code 0} if this string
   *          is lexicographically less than the string argument; and a
   *          value greater than {@code 0} if this string is
   *          lexicographically greater than the string argument.
   */
  public function compareTo(String $anotherString, $fast = false)
  {
    $res = strcmp($this->value, $anotherString->value);
    if($fast || 0 === $res ){
      return $res;
    }

    return $this->_compareTo($anotherString);
  }

  protected function _compareTo(String $anotherString)
  {
    $len1 = $this->length();
    $len2 = $anotherString->length();
    $lim = Math::min($len1, $len2);
    $v1 = $this->value;
    $v2 = $anotherString->value;
    $k = 0;

    while ($k < $lim) {
      $c1 = $v1[$k];
      $c2 = $v2[$k];
      if ($c1 != $c2) {
        return ord($c1) - ord($c2);
      }
      $k++;
    }
    return $len1 - $len2;
  }

  /**
   * Compares two strings lexicographically, ignoring case
   * differences.
   *
   * @param   $str \PHPJ\Lang\String - the {@code String} to be compared.
   * @param   $fast bool
   *
   * @return int - negative integer, zero, or a positive integer as the
   *          specified String is greater than, equal to, or less
   * than this String, ignoring case considerations.
   */
  public function compareToIgnoreCase(String $str, $fast = false)
  {
    $res = strcmp(strtolower($this->value), strtolower($str->value));
    if($fast || 0 === $res){
      return $res;
    }
    return $this->_compareToIgnoreCase($str);
  }

  protected function _compareToIgnoreCase(String $str)
  {
    $len1 = $this->length();
    $len2 = $str->length();
    $lim = Math::min($len1, $len2);
    $v1 = $this->value;
    $v2 = $str->value;
    $k = 0;

    while ($k < $lim) {
      $c1 = $v1[$k];
      $c2 = $v2[$k];
      if ($c1 != $c2) {
        $c1 = strtoupper($c1);
        $c2 = strtoupper($c2);
        if ($c1 != $c2) {
          $c1 = strtolower($c1);
          $c2 = strtolower($c2);
          if ($c1 != $c2) {
            // No overflow because of numeric promotion
            return ord($c1) - ord($c2);
          }
        }
      }
      $k++;
    }
    return $len1 - $len2;
  }

  /**
   * Returns the character (Unicode code point) at the specified
   * index. The index refers to {@code char} values
   * (Unicode code units) and ranges from {@code 0} to
   * {@link #length()}{@code  - 1}.
   *
   * @param      index the index to the {@code char} values
   * @return     the code point value of the character at the
   *             {@code index}
   * @exception  IndexOutOfBoundsException  if the {@code index}
   *             argument is negative or not less than the length of this
   *             string.
   * @since      1.5
   * @todo
   */
  //    public int codePointAt(int index) {
  //    if ((index < 0) || (index >= value.length)) {
  //        throw new StringIndexOutOfBoundsException(index);
  //    }
  //    return Character.codePointAtImpl(value, index, value.length);
  //}

  /**
   * Returns the character (Unicode code point) before the specified
   * index. The index refers to {@code char} values
   * (Unicode code units) and ranges from {@code 1} to {@link
   * CharSequence#length() length}.
   *
   * <p> If the {@code char} value at {@code (index - 1)}
   * is in the low-surrogate range, {@code (index - 2)} is not
   * negative, and the {@code char} value at {@code (index -
   * 2)} is in the high-surrogate range, then the
   * supplementary code point value of the surrogate pair is
   * returned. If the {@code char} value at {@code index -
   * 1} is an unpaired low-surrogate or a high-surrogate, the
   * surrogate value is returned.
   *
   * @param     index the index following the code point that should be returned
   * @return    the Unicode code point value before the given index.
   * @exception IndexOutOfBoundsException if the {@code index}
   *            argument is less than 1 or greater than the length
   *            of this string.
   * @since     1.5
   * @todo
   */
  //    public int codePointBefore(int index) {
  //    int i = index - 1;
  //        if ((i < 0) || (i >= value.length)) {
  //            throw new StringIndexOutOfBoundsException(index);
  //        }
  //        return Character.codePointBeforeImpl(value, index, 0);
  //    }

  /**
   * Returns the number of Unicode code points in the specified text
   * range of this {@code String}. The text range begins at the
   * specified {@code beginIndex} and extends to the
   * {@code char} at index {@code endIndex - 1}. Thus the
   * length (in {@code char}s) of the text range is
   * {@code endIndex-beginIndex}. Unpaired surrogates within
   * the text range count as one code point each.
   *
   * @param beginIndex the index to the first {@code char} of
   * the text range.
   * @param endIndex the index after the last {@code char} of
   * the text range.
   * @return the number of Unicode code points in the specified text
   * range
   * @exception IndexOutOfBoundsException if the
   * {@code beginIndex} is negative, or {@code endIndex}
   * is larger than the length of this {@code String}, or
   * {@code beginIndex} is larger than {@code endIndex}.
   * @since  1.5
   * @todo
   */
  //    public int codePointCount(int beginIndex, int endIndex) {
  //    if (beginIndex < 0 || endIndex > value.length || beginIndex > endIndex) {
  //        throw new IndexOutOfBoundsException();
  //    }
  //    return Character.codePointCountImpl(value, beginIndex, endIndex - beginIndex);
  //}

  /**
   * Returns the index within this {@code String} that is
   * offset from the given {@code index} by
   * {@code codePointOffset} code points. Unpaired surrogates
   * within the text range given by {@code index} and
   * {@code codePointOffset} count as one code point each.
   *
   * @param index the index to be offset
   * @param codePointOffset the offset in code points
   * @return the index within this {@code String}
   * @exception IndexOutOfBoundsException if {@code index}
   *   is negative or larger then the length of this
   *   {@code String}, or if {@code codePointOffset} is positive
   *   and the substring starting with {@code index} has fewer
   *   than {@code codePointOffset} code points,
   *   or if {@code codePointOffset} is negative and the substring
   *   before {@code index} has fewer than the absolute value
   *   of {@code codePointOffset} code points.
   * @since 1.5
   * @todo
   */
  //    public int offsetByCodePoints(int index, int codePointOffset) {
  //    if (index < 0 || index > value.length) {
  //        throw new IndexOutOfBoundsException();
  //    }
  //    return Character.offsetByCodePointsImpl(value, 0, value.length,
  //        index, codePointOffset);
  //}

  /**
   * Copy characters from this string into dst starting at dstBegin.
   * This method doesn't perform any range checking.
   */
  //void getChars(char dst[], int dstBegin)
  //{
  //System.arraycopy(value, 0, dst, dstBegin, value.length);
  //}

  /**
   * Copies characters from this string into the destination character
   * array.
   * <p>
   * The first character to be copied is at index {@code srcBegin};
   * the last character to be copied is at index {@code srcEnd-1}
   * (thus the total number of characters to be copied is
   * {@code srcEnd-srcBegin}). The characters are copied into the
   * subarray of {@code dst} starting at index {@code dstBegin}
   * and ending at index:
   * <blockquote><pre>
   *     dstbegin + (srcEnd-srcBegin) - 1
   * </pre></blockquote>
   *
   * @param      srcBegin   index of the first character in the string
   *                        to copy.
   * @param      srcEnd     index after the last character in the string
   *                        to copy.
   * @param      dst        the destination array.
   * @param      dstBegin   the start offset in the destination array.
   * @exception IndexOutOfBoundsException If any of the following
   *            is true:
   *            <ul><li>{@code srcBegin} is negative.
   *            <li>{@code srcBegin} is greater than {@code srcEnd}
   *            <li>{@code srcEnd} is greater than the length of this
   *                string
   *            <li>{@code dstBegin} is negative
   *            <li>{@code dstBegin+(srcEnd-srcBegin)} is larger than
   *                {@code dst.length}</ul>
   */
  //public
  //void getChars(int srcBegin, int srcEnd, char dst[], int dstBegin) {
  //  if (srcBegin < 0) {
  //    throw new StringIndexOutOfBoundsException(srcBegin);
  //  }
  //  if (srcEnd > value . length) {
  //    throw new StringIndexOutOfBoundsException(srcEnd);
  //  }
  //  if (srcBegin > srcEnd) {
  //    throw new StringIndexOutOfBoundsException(srcEnd - srcBegin);
  //  }
  //  System . arraycopy(value, srcBegin, dst, dstBegin, srcEnd - srcBegin);
  //}

  /**
   * Encodes this {@code String} into a sequence of bytes using the named
   * charset, storing the result into a new byte array.
   *
   * <p> The behavior of this method when this string cannot be encoded in
   * the given charset is unspecified.  The {@link
   * java.nio.charset.CharsetEncoder} class should be used when more control
   * over the encoding process is required.
   *
   * @param  charsetName
   *         The name of a supported {@linkplain java.nio.charset.Charset
   *         charset}
   *
   * @return  The resultant byte array
   *
   * @throws  UnsupportedEncodingException
   *          If the named charset is not supported
   *
   * @since  JDK1.1
   */
  //    public byte[] getBytes(String charsetName)
  //            throws UnsupportedEncodingException {
  //  if (charsetName == null) {
  //    throw new NullPointerException();
  //  }
  //  return StringCoding . encode(charsetName, value, 0, value . length);
  //}

  /**
   * Encodes this {@code String} into a sequence of bytes using the given
   * {@linkplain java.nio.charset.Charset charset}, storing the result into a
   * new byte array.
   *
   * <p> This method always replaces malformed-input and unmappable-character
   * sequences with this charset's default replacement byte array.  The
   * {@link java.nio.charset.CharsetEncoder} class should be used when more
   * control over the encoding process is required.
   *
   * @param  charset
   *         The {@linkplain java.nio.charset.Charset} to be used to encode
   *         the {@code String}
   *
   * @return  The resultant byte array
   *
   * @since  1.6
   */
  //    public byte[] getBytes(Charset charset) {
  //  if (charset == null) {
  //    throw new NullPointerException();
  //  }
  //  return StringCoding . encode(charset, value, 0, value . length);
  //}

  /**
   * Encodes this {@code String} into a sequence of bytes using the
   * platform's default charset, storing the result into a new byte array.
   *
   * <p> The behavior of this method when this string cannot be encoded in
   * the default charset is unspecified.  The {@link
   * java.nio.charset.CharsetEncoder} class should be used when more control
   * over the encoding process is required.
   *
   * @return  The resultant byte array
   *
   * @since      JDK1.1
   */
  //    public byte[] getBytes(){
  //        return StringCoding . encode(value, 0, value . length);
  //    }



  /**
   * Compares this string to the specified {@code StringBuffer}.  The result
   * is {@code true} if and only if this {@code String} represents the same
   * sequence of characters as the specified {@code StringBuffer}. This method
   * synchronizes on the {@code StringBuffer}.
   *
   * @param  sb
   *         The {@code StringBuffer} to compare this {@code String} against
   *
   * @return  {@code true} if this {@code String} represents the same
   *          sequence of characters as the specified {@code StringBuffer},
   *          {@code false} otherwise
   *
   * @since  1.4
   */
  //    public boolean contentEquals(StringBuffer sb) {
  //  return contentEquals((CharSequence)sb);
  //    }
  //
  //    private boolean nonSyncContentEquals(AbstractStringBuilder sb) {
  //  char v1[] = value;
  //        char v2[] = sb . getValue();
  //        int n = v1 . length;
  //        if (n != sb . length()) {
  //          return false;
  //        }
  //        for (int i = 0; i < n {
  //          ;
  //        } i++) {
  //    if (v1 {
  //      [i] != v2}
  //    [i]) {
  //      return false;
  //    }
  //        }
  //        return true;
  //    }

  /**
   * Compares this string to the specified {@code CharSequence}.  The
   * result is {@code true} if and only if this {@code String} represents the
   * same sequence of char values as the specified sequence. Note that if the
   * {@code CharSequence} is a {@code StringBuffer} then the method
   * synchronizes on it.
   *
   * @param  cs
   *         The sequence to compare this {@code String} against
   *
   * @return  {@code true} if this {@code String} represents the same
   *          sequence of char values as the specified sequence, {@code
   *          false} otherwise
   *
   * @since  1.5
   */
  //    public boolean contentEquals(CharSequence cs) {
  //  // Argument is a StringBuffer, StringBuilder
  //  if (cs instanceof AbstractStringBuilder) {
  //    if (cs instanceof StringBuffer) {
  //      synchronized(cs){
  //                   return nonSyncContentEquals((AbstractStringBuilder)cs);
  //                }
  //            } else {
  //      return nonSyncContentEquals((AbstractStringBuilder)cs);
  //            }
  //  }
  //  // Argument is a String
  //  if (cs . equals(this)) {
  //    return true;
  //  }
  //  // Argument is a generic CharSequence
  //  char v1[] = value;
  //        int n = v1 . length;
  //        if (n != cs . length()) {
  //          return false;
  //        }
  //        for (int i = 0; i < n {
  //          ;
  //        } i++) {
  //    if (v1 {
  //      [i] != cs . charAt(i)}) {
  //      return false;
  //    }
  //  }
  //        return true;
  //    }

  /**
   * Compares this {@code String} to another {@code String}, ignoring case
   * considerations.  Two strings are considered equal ignoring case if they
   * are of the same length and corresponding characters in the two strings
   * are equal ignoring case.
   *
   * <p> Two characters {@code c1} and {@code c2} are considered the same
   * ignoring case if at least one of the following is true:
   * <ul>
   *   <li> The two characters are the same (as compared by the
   *        {@code ==} operator)
   *   <li> Applying the method {@link
   *        java.lang.Character#toUpperCase(char)} to each character
   *        produces the same result
   *   <li> Applying the method {@link
   *        java.lang.Character#toLowerCase(char)} to each character
   *        produces the same result
   * </ul>
   *
   * @param  anotherString
   *         The {@code String} to compare this {@code String} against
   *
   * @return  boolean {@code true} if the argument is not {@code null} and it
   *          represents an equivalent {@code String} ignoring case; {@code
   *          false} otherwise
   *
   * @see  #equals(Object)
   */
  //public function equalsIgnoreCase(String anotherString) {
  //      return (this == anotherString) ? true
  //        : (anotherString != null)
  //          && (anotherString . value . length == value . length)
  //          && regionMatches(true, 0, anotherString, 0, value . length);
  //}


  /**
   * Tests if two string regions are equal.
   * <p>
   * A substring of this {@code String} object is compared to a substring
   * of the argument other. The result is true if these substrings
   * represent identical character sequences. The substring of this
   * {@code String} object to be compared begins at index {@code toffset}
   * and has length {@code len}. The substring of other to be compared
   * begins at index {@code ooffset} and has length {@code len}. The
   * result is {@code false} if and only if at least one of the following
   * is true:
   * <ul><li>{@code toffset} is negative.
   * <li>{@code ooffset} is negative.
   * <li>{@code toffset+len} is greater than the length of this
   * {@code String} object.
   * <li>{@code ooffset+len} is greater than the length of the other
   * argument.
   * <li>There is some nonnegative integer <i>k</i> less than {@code len}
   * such that:
   * {@code this.charAt(toffset + }<i>k</i>{@code ) != other.charAt(ooffset + }
   * <i>k</i>{@code )}
   * </ul>
   *
   * @param   toffset   the starting offset of the subregion in this string.
   * @param   other     the string argument.
   * @param   ooffset   the starting offset of the subregion in the string
   *                    argument.
   * @param   len       the number of characters to compare.
   * @return  {@code true} if the specified subregion of this string
   *          exactly matches the specified subregion of the string argument;
   *          {@code false} otherwise.
   */
  //    public boolean regionMatches(int toffset, String other, int ooffset,
  //            int len) {
  //  char ta[] = value;
  //        int to = toffset;
  //        char pa[] = other . value;
  //        int po = ooffset;
  //        // Note: toffset, ooffset, or len might be near -1>>>1.
  //        if ((ooffset < 0) || (toffset < 0)
  //            || (toffset > (long){
  //          value . length - len})
  //                || (ooffset > (long)other . value . length - len)) {
  //    return false;
  //  }
  //        while (len-- > 0) {
  //          if (ta {
  //            [to++] != pa}
  //          [po++]) {
  //            return false;
  //          }
  //        }
  //        return true;
  //    }

  /**
   * Tests if two string regions are equal.
   * <p>
   * A substring of this {@code String} object is compared to a substring
   * of the argument {@code other}. The result is {@code true} if these
   * substrings represent character sequences that are the same, ignoring
   * case if and only if {@code ignoreCase} is true. The substring of
   * this {@code String} object to be compared begins at index
   * {@code toffset} and has length {@code len}. The substring of
   * {@code other} to be compared begins at index {@code ooffset} and
   * has length {@code len}. The result is {@code false} if and only if
   * at least one of the following is true:
   * <ul><li>{@code toffset} is negative.
   * <li>{@code ooffset} is negative.
   * <li>{@code toffset+len} is greater than the length of this
   * {@code String} object.
   * <li>{@code ooffset+len} is greater than the length of the other
   * argument.
   * <li>{@code ignoreCase} is {@code false} and there is some nonnegative
   * integer <i>k</i> less than {@code len} such that:
   * <blockquote><pre>
   * this.charAt(toffset+k) != other.charAt(ooffset+k)
   * </pre></blockquote>
   * <li>{@code ignoreCase} is {@code true} and there is some nonnegative
   * integer <i>k</i> less than {@code len} such that:
   * <blockquote><pre>
   * Character.toLowerCase(this.charAt(toffset+k)) !=
   * Character.toLowerCase(other.charAt(ooffset+k))
   * </pre></blockquote>
   * and:
   * <blockquote><pre>
   * Character.toUpperCase(this.charAt(toffset+k)) !=
   *         Character.toUpperCase(other.charAt(ooffset+k))
   * </pre></blockquote>
   * </ul>
   *
   * @param   ignoreCase   if {@code true}, ignore case when comparing
   *                       characters.
   * @param   toffset      the starting offset of the subregion in this
   *                       string.
   * @param   other        the string argument.
   * @param   ooffset      the starting offset of the subregion in the string
   *                       argument.
   * @param   len          the number of characters to compare.
   * @return  {@code true} if the specified subregion of this string
   *          matches the specified subregion of the string argument;
   *          {@code false} otherwise. Whether the matching is exact
   *          or case insensitive depends on the {@code ignoreCase}
   *          argument.
   */
  //    public boolean regionMatches(boolean ignoreCase, int toffset,
  //            String other, int ooffset, int len) {
  //  char ta[] = value;
  //        int to = toffset;
  //        char pa[] = other . value;
  //        int po = ooffset;
  //        // Note: toffset, ooffset, or len might be near -1>>>1.
  //        if ((ooffset < 0) || (toffset < 0)
  //            || (toffset > (long){
  //          value . length - len})
  //                || (ooffset > (long)other . value . length - len)) {
  //    return false;
  //  }
  //        while (len-- > 0) {
  //          char c1 = ta[to++];
  //            char c2 = pa[po++];
  //            if (c1 == c2) {
  //              continue;
  //            }
  //            if (ignoreCase) {
  //              // If characters don't match but case may be ignored,
  //              // try converting both characters to uppercase.
  //              // If the results match, then the comparison scan should
  //              // continue.
  //              char u1 = Character . toUpperCase(c1);
  //                char u2 = Character . toUpperCase(c2);
  //                if (u1 == u2) {
  //                  continue;
  //                }
  //                // Unfortunately, conversion to uppercase does not work properly
  //                // for the Georgian alphabet, which has strange rules about case
  //                // conversion.  So we need to make one last check before
  //                // exiting.
  //                if (Character . toLowerCase(u1) == Character . toLowerCase(u2)) {
  //                  continue;
  //                }
  //            }
  //            return false;
  //        }
  //        return true;
  //    }

  /**
   * Tests if the substring of this string beginning at the
   * specified index starts with the specified prefix.
   *
   * @param   prefix    the prefix.
   * @param   toffset   where to begin looking in this string.
   * @return  {@code true} if the character sequence represented by the
   *          argument is a prefix of the substring of this object starting
   *          at index {@code toffset}; {@code false} otherwise.
   *          The result is {@code false} if {@code toffset} is
   *          negative or greater than the length of this
   *          {@code String} object; otherwise the result is the same
   *          as the result of the expression
   *          <pre>
   *          this.substring(toffset).startsWith(prefix)
   *          </pre>
   */
  //    public boolean startsWith(String prefix, int toffset) {
  //  char ta[] = value;
  //        int to = toffset;
  //        char pa[] = prefix . value;
  //        int po = 0;
  //        int pc = prefix . value . length;
  //        // Note: toffset might be near -1>>>1.
  //        if ((toffset < 0) || (toffset > value . length - pc)) {
  //          return false;
  //        }
  //        while (--pc >= 0) {
  //          if (ta {
  //            [to++] != pa}
  //          [po++]) {
  //            return false;
  //          }
  //        }
  //        return true;
  //    }

  /**
   * Tests if this string starts with the specified prefix.
   *
   * @param   prefix   the prefix.
   * @return  {@code true} if the character sequence represented by the
   *          argument is a prefix of the character sequence represented by
   *          this string; {@code false} otherwise.
   *          Note also that {@code true} will be returned if the
   *          argument is an empty string or is equal to this
   *          {@code String} object as determined by the
   *          {@link #equals(Object)} method.
   * @since   1. 0
   */
  //    public boolean startsWith(String prefix) {
  //  return startsWith(prefix, 0);
  //}

  /**
   * Tests if this string ends with the specified suffix.
   *
   * @param   suffix   the suffix.
   * @return  {@code true} if the character sequence represented by the
   *          argument is a suffix of the character sequence represented by
   *          this object; {@code false} otherwise. Note that the
   *          result will be {@code true} if the argument is the
   *          empty string or is equal to this {@code String} object
   *          as determined by the {@link #equals(Object)} method.
   */
  //    public boolean endsWith(String suffix) {
  //  return startsWith(suffix, value . length - suffix . value . length);
  //}



  /**
   * Returns the index within this string of the first occurrence of
   * the specified character. If a character with value
   * {@code ch} occurs in the character sequence represented by
   * this {@code String} object, then the index (in Unicode
   * code units) of the first such occurrence is returned. For
   * values of {@code ch} in the range from 0 to 0xFFFF
   * (inclusive), this is the smallest value <i>k</i> such that:
   * <blockquote><pre>
   * this.charAt(<i>k</i>) == ch
   * </pre></blockquote>
   * is true. For other values of {@code ch}, it is the
   * smallest value <i>k</i> such that:
   * <blockquote><pre>
   * this.codePointAt(<i>k</i>) == ch
   * </pre></blockquote>
   * is true. In either case, if no such character occurs in this
   * string, then {@code -1} is returned.
   *
   * @param   ch   a character (Unicode code point).
   * @return  the index of the first occurrence of the character in the
   *          character sequence represented by this object, or
   *          {@code -1} if the character does not occur.
   */
  //    public int indexOf(int ch) {
  //  return indexOf(ch, 0);
  //}

  /**
   * Returns the index within this string of the first occurrence of the
   * specified character, starting the search at the specified index.
   * <p>
   * If a character with value {@code ch} occurs in the
   * character sequence represented by this {@code String}
   * object at an index no smaller than {@code fromIndex}, then
   * the index of the first such occurrence is returned. For values
   * of {@code ch} in the range from 0 to 0xFFFF (inclusive),
   * this is the smallest value <i>k</i> such that:
   * <blockquote><pre>
   * (this.charAt(<i>k</i>) == ch) {@code &&} (<i>k</i> &gt;= fromIndex)
   * </pre></blockquote>
   * is true. For other values of {@code ch}, it is the
   * smallest value <i>k</i> such that:
   * <blockquote><pre>
   * (this.codePointAt(<i>k</i>) == ch) {@code &&} (<i>k</i> &gt;= fromIndex)
   * </pre></blockquote>
   * is true. In either case, if no such character occurs in this
   * string at or after position {@code fromIndex}, then
   * {@code -1} is returned.
   *
   * <p>
   * There is no restriction on the value of {@code fromIndex}. If it
   * is negative, it has the same effect as if it were zero: this entire
   * string may be searched. If it is greater than the length of this
   * string, it has the same effect as if it were equal to the length of
   * this string: {@code -1} is returned.
   *
   * <p>All indices are specified in {@code char} values
   * (Unicode code units).
   *
   * @param   ch          a character (Unicode code point).
   * @param   fromIndex   the index to start the search from.
   * @return  the index of the first occurrence of the character in the
   *          character sequence represented by this object that is greater
   *          than or equal to {@code fromIndex}, or {@code -1}
   *          if the character does not occur.
   */
  //    public int indexOf(int ch, int fromIndex) {
  //  final int max = value . length;
  //        if (fromIndex < 0) {
  //          fromIndex = 0;
  //        } else {
  //          if (fromIndex >= max) {
  //            // Note: fromIndex might be near -1>>>1.
  //            return -1;
  //          }
  //        }
  //
  //        if (ch < Character . MIN_SUPPLEMENTARY_CODE_POINT) {
  //          // handle most cases here (ch is a BMP code point or a
  //          // negative value (invalid code point))
  //          final char[] value = this . value;
  //            for (int i = fromIndex; i < max {
  //              ;
  //            } i++) {
  //            if (value {
  //              [i] == ch}) {
  //              return i;
  //            }
  //          }
  //            return -1;
  //        } else {
  //          return indexOfSupplementary(ch, fromIndex);
  //        }
  //    }

  /**
   * Handles (rare) calls of indexOf with a supplementary character.
   */
  //    private int indexOfSupplementary(int ch, int fromIndex) {
  //  if (Character . isValidCodePoint(ch)) {
  //    final char[] value = this . value;
  //            final char hi = Character . highSurrogate(ch);
  //            final char lo = Character . lowSurrogate(ch);
  //            final int max = value . length - 1;
  //            for (int i = fromIndex; i < max {
  //              ;
  //            } i++) {
  //      if (value {
  //        [i] == hi && value}
  //      [i + 1] == lo) {
  //        return i;
  //      }
  //            }
  //        }
  //  return -1;
  //}

  /**
   * Returns the index within this string of the last occurrence of
   * the specified character. For values of {@code ch} in the
   * range from 0 to 0xFFFF (inclusive), the index (in Unicode code
   * units) returned is the largest value <i>k</i> such that:
   * <blockquote><pre>
   * this.charAt(<i>k</i>) == ch
   * </pre></blockquote>
   * is true. For other values of {@code ch}, it is the
   * largest value <i>k</i> such that:
   * <blockquote><pre>
   * this.codePointAt(<i>k</i>) == ch
   * </pre></blockquote>
   * is true.  In either case, if no such character occurs in this
   * string, then {@code -1} is returned.  The
   * {@code String} is searched backwards starting at the last
   * character.
   *
   * @param   ch   a character (Unicode code point).
   * @return  the index of the last occurrence of the character in the
   *          character sequence represented by this object, or
   *          {@code -1} if the character does not occur.
   */
  //    public int lastIndexOf(int ch) {
  //  return lastIndexOf(ch, value . length - 1);
  //}

  /**
   * Returns the index within this string of the last occurrence of
   * the specified character, searching backward starting at the
   * specified index. For values of {@code ch} in the range
   * from 0 to 0xFFFF (inclusive), the index returned is the largest
   * value <i>k</i> such that:
   * <blockquote><pre>
   * (this.charAt(<i>k</i>) == ch) {@code &&} (<i>k</i> &lt;= fromIndex)
   * </pre></blockquote>
   * is true. For other values of {@code ch}, it is the
   * largest value <i>k</i> such that:
   * <blockquote><pre>
   * (this.codePointAt(<i>k</i>) == ch) {@code &&} (<i>k</i> &lt;= fromIndex)
   * </pre></blockquote>
   * is true. In either case, if no such character occurs in this
   * string at or before position {@code fromIndex}, then
   * {@code -1} is returned.
   *
   * <p>All indices are specified in {@code char} values
   * (Unicode code units).
   *
   * @param   ch          a character (Unicode code point).
   * @param   fromIndex   the index to start the search from. There is no
   *          restriction on the value of {@code fromIndex}. If it is
   *          greater than or equal to the length of this string, it has
   *          the same effect as if it were equal to one less than the
   *          length of this string: this entire string may be searched.
   *          If it is negative, it has the same effect as if it were -1:
   *          -1 is returned.
   * @return  the index of the last occurrence of the character in the
   *          character sequence represented by this object that is less
   *          than or equal to {@code fromIndex}, or {@code -1}
   *          if the character does not occur before that point.
   */
  //    public int lastIndexOf(int ch, int fromIndex) {
  //  if (ch < Character . MIN_SUPPLEMENTARY_CODE_POINT) {
  //    // handle most cases here (ch is a BMP code point or a
  //    // negative value (invalid code point))
  //    final char[] value = this . value;
  //            int i = Math . min(fromIndex, value . length - 1);
  //            for (; i >= 0; i--) {
  //              if (value {
  //                [i] == ch}) {
  //                return i;
  //              }
  //            }
  //            return -1;
  //        } else {
  //    return lastIndexOfSupplementary(ch, fromIndex);
  //  }
  //}

  /**
   * Handles (rare) calls of lastIndexOf with a supplementary character.
   */
  //    private int lastIndexOfSupplementary(int ch, int fromIndex) {
  //  if (Character . isValidCodePoint(ch)) {
  //    final char[] value = this . value;
  //            char hi = Character . highSurrogate(ch);
  //            char lo = Character . lowSurrogate(ch);
  //            int i = Math . min(fromIndex, value . length - 2);
  //            for (; i >= 0; i--) {
  //              if (value {
  //                [i] == hi && value}
  //              [i + 1] == lo) {
  //                return i;
  //              }
  //            }
  //        }
  //  return -1;
  //}

  /**
   * Returns the index within this string of the first occurrence of the
   * specified substring.
   *
   * <p>The returned index is the smallest value <i>k</i> for which:
   * <blockquote><pre>
   * this.startsWith(str, <i>k</i>)
   * </pre></blockquote>
   * If no such value of <i>k</i> exists, then {@code -1} is returned.
   *
   * @param   str   the substring to search for.
   * @return  the index of the first occurrence of the specified substring,
   *          or {@code -1} if there is no such occurrence.
   */
  //    public int indexOf(String str) {
  //  return indexOf(str, 0);
  //}

  /**
   * Returns the index within this string of the first occurrence of the
   * specified substring, starting at the specified index.
   *
   * <p>The returned index is the smallest value <i>k</i> for which:
   * <blockquote><pre>
   * <i>k</i> &gt;= fromIndex {@code &&} this.startsWith(str, <i>k</i>)
   * </pre></blockquote>
   * If no such value of <i>k</i> exists, then {@code -1} is returned.
   *
   * @param   str         the substring to search for.
   * @param   fromIndex   the index from which to start the search.
   * @return  the index of the first occurrence of the specified substring,
   *          starting at the specified index,
   *          or {@code -1} if there is no such occurrence.
   */
  //    public int indexOf(String str, int fromIndex) {
  //  return indexOf(value, 0, value . length,
  //    str . value, 0, str . value . length, fromIndex);
  //}

  /**
   * Code shared by String and AbstractStringBuilder to do searches. The
   * source is the character array being searched, and the target
   * is the string being searched for.
   *
   * @param   source       the characters being searched.
   * @param   sourceOffset offset of the source string.
   * @param   sourceCount  count of the source string.
   * @param   target       the characters being searched for.
   * @param   fromIndex    the index to begin searching from.
   */
  //    static int indexOf(char[] source, int sourceOffset, int sourceCount,
  //            String target, int fromIndex) {
  //  return indexOf(source, sourceOffset, sourceCount,
  //    target . value, 0, target . value . length,
  //    fromIndex);
  //}

  /**
   * Code shared by String and StringBuffer to do searches. The
   * source is the character array being searched, and the target
   * is the string being searched for.
   *
   * @param   source       the characters being searched.
   * @param   sourceOffset offset of the source string.
   * @param   sourceCount  count of the source string.
   * @param   target       the characters being searched for.
   * @param   targetOffset offset of the target string.
   * @param   targetCount  count of the target string.
   * @param   fromIndex    the index to begin searching from.
   */
  //    static int indexOf(char[] source, int sourceOffset, int sourceCount,
  //            char[] target, int targetOffset, int targetCount,
  //            int fromIndex) {
  //  if (fromIndex >= sourceCount) {
  //    return (targetCount == 0 ? sourceCount : -1);
  //  }
  //  if (fromIndex < 0) {
  //    fromIndex = 0;
  //  }
  //  if (targetCount == 0) {
  //    return fromIndex;
  //  }
  //
  //  char first = target[targetOffset];
  //        int max = sourceOffset + (sourceCount - targetCount);
  //
  //        for (int i = sourceOffset + fromIndex; i <= max {
  //          ;
  //        } i++) {
  //    /* Look for first character. */
  //    if (source {
  //      [i] != first}) {
  //      while (++i <= max && source {
  //        [i] != first});
  //    }
  //
  //    /* Found first character, now look at the rest of v2 */
  //    if (i <= max) {
  //      int j = i + 1;
  //                int end = j + targetCount - 1;
  //                for (int k = targetOffset + 1; j < end && source {
  //                  [j]
  //                  == target}[k]; j++, k++);
  //
  //                if (j == end) {
  //                  /* Found whole string. */
  //                  return i - sourceOffset;
  //                }
  //            }
  //  }
  //        return -1;
  //    }

  /**
   * Returns the index within this string of the last occurrence of the
   * specified substring.  The last occurrence of the empty string ""
   * is considered to occur at the index value {@code this.length()}.
   *
   * <p>The returned index is the largest value <i>k</i> for which:
   * <blockquote><pre>
   * this.startsWith(str, <i>k</i>)
   * </pre></blockquote>
   * If no such value of <i>k</i> exists, then {@code -1} is returned.
   *
   * @param   str   the substring to search for.
   * @return  the index of the last occurrence of the specified substring,
   *          or {@code -1} if there is no such occurrence.
   */
  //    public int lastIndexOf(String str) {
  //  return lastIndexOf(str, value . length);
  //}

  /**
   * Returns the index within this string of the last occurrence of the
   * specified substring, searching backward starting at the specified index.
   *
   * <p>The returned index is the largest value <i>k</i> for which:
   * <blockquote><pre>
   * <i>k</i> {@code <=} fromIndex {@code &&} this.startsWith(str, <i>k</i>)
   * </pre></blockquote>
   * If no such value of <i>k</i> exists, then {@code -1} is returned.
   *
   * @param   str         the substring to search for.
   * @param   fromIndex   the index to start the search from.
   * @return  the index of the last occurrence of the specified substring,
   *          searching backward from the specified index,
   *          or {@code -1} if there is no such occurrence.
   */
  //    public int lastIndexOf(String str, int fromIndex) {
  //  return lastIndexOf(value, 0, value . length,
  //    str . value, 0, str . value . length, fromIndex);
  //}

  /**
   * Code shared by String and AbstractStringBuilder to do searches. The
   * source is the character array being searched, and the target
   * is the string being searched for.
   *
   * @param   source       the characters being searched.
   * @param   sourceOffset offset of the source string.
   * @param   sourceCount  count of the source string.
   * @param   target       the characters being searched for.
   * @param   fromIndex    the index to begin searching from.
   */
  //    static int lastIndexOf(char[] source, int sourceOffset, int sourceCount,
  //            String target, int fromIndex) {
  //  return lastIndexOf(source, sourceOffset, sourceCount,
  //    target . value, 0, target . value . length,
  //    fromIndex);
  //}

  /**
   * Code shared by String and StringBuffer to do searches. The
   * source is the character array being searched, and the target
   * is the string being searched for.
   *
   * @param   source       the characters being searched.
   * @param   sourceOffset offset of the source string.
   * @param   sourceCount  count of the source string.
   * @param   target       the characters being searched for.
   * @param   targetOffset offset of the target string.
   * @param   targetCount  count of the target string.
   * @param   fromIndex    the index to begin searching from.
   */
  //    static int lastIndexOf(char[] source, int sourceOffset, int sourceCount,
  //            char[] target, int targetOffset, int targetCount,
  //            int fromIndex) {
  //  /*
  //   * Check arguments; return immediately where possible. For
  //   * consistency, don't check for null str.
  //   */
  //  int rightIndex = sourceCount - targetCount;
  //        if (fromIndex < 0) {
  //          return -1;
  //        }
  //        if (fromIndex > rightIndex) {
  //          fromIndex = rightIndex;
  //        }
  //        /* Empty string always matches. */
  //        if (targetCount == 0) {
  //          return fromIndex;
  //        }
  //
  //        int strLastIndex = targetOffset + targetCount - 1;
  //        char strLastChar = target[strLastIndex];
  //        int min = sourceOffset + targetCount - 1;
  //        int i = min + fromIndex;
  //
  //    startSearchForLastChar:
  //        while (true) {
  //          while (i >= min && source {
  //            [i] != strLastChar}) {
  //            i--;
  //          }
  //          if (i < min) {
  //            return -1;
  //          }
  //          int j = i - 1;
  //            int start = j - (targetCount - 1);
  //            int k = strLastIndex - 1;
  //
  //            while (j > start) {
  //              if (source {
  //                [j--] != target}
  //              [k--]) {
  //                i--;
  //                continue startSearchForLastChar;
  //              }
  //            }
  //            return start - sourceOffset + 1;
  //        }
  //    }


  /**
   * Returns a character sequence that is a subsequence of this sequence.
   *
   * <p> An invocation of this method of the form
   *
   * <blockquote><pre>
   * str.subSequence(begin,&nbsp;end)</pre></blockquote>
   *
   * behaves in exactly the same way as the invocation
   *
   * <blockquote><pre>
   * str.substring(begin,&nbsp;end)</pre></blockquote>
   *
   * @apiNote
   * This method is defined so that the {@code String} class can implement
   * the {@link CharSequence} interface.
   *
   * @param   beginIndex   the begin index, inclusive.
   * @param   endIndex     the end index, exclusive.
   * @return  the specified subsequence.
   *
   * @throws  IndexOutOfBoundsException
   *          if {@code beginIndex} or {@code endIndex} is negative,
   *          if {@code endIndex} is greater than {@code length()},
   *          or if {@code beginIndex} is greater than {@code endIndex}
   *
   * @since 1.4
   * @spec JSR-51
   */
  //    public CharSequence subSequence(int beginIndex, int endIndex) {
  //  return this . substring(beginIndex, endIndex);
  //}

  /**
   * Concatenates the specified string to the end of this string.
   * <p>
   * If the length of the argument string is {@code 0}, then this
   * {@code String} object is returned. Otherwise, a
   * {@code String} object is returned that represents a character
   * sequence that is the concatenation of the character sequence
   * represented by this {@code String} object and the character
   * sequence represented by the argument string.<p>
   * Examples:
   * <blockquote><pre>
   * "cares".concat("s") returns "caress"
   * "to".concat("get").concat("her") returns "together"
   * </pre></blockquote>
   *
   * @param   str   the {@code String} that is concatenated to the end
   *                of this {@code String}.
   * @return  a string that represents the concatenation of this object's
   *          characters followed by the string argument's characters.
   */
  //    public String concat(String str) {
  //  int otherLen = str . length();
  //        if (otherLen == 0) {
  //          return this;
  //        }
  //        int len = value . length;
  //        char buf[] = Arrays . copyOf(value, len + otherLen);
  //        str . getChars(buf, len);
  //        return new String(buf, true);
  //    }

  /**
   * Returns a string resulting from replacing all occurrences of
   * {@code oldChar} in this string with {@code newChar}.
   * <p>
   * If the character {@code oldChar} does not occur in the
   * character sequence represented by this {@code String} object,
   * then a reference to this {@code String} object is returned.
   * Otherwise, a {@code String} object is returned that
   * represents a character sequence identical to the character sequence
   * represented by this {@code String} object, except that every
   * occurrence of {@code oldChar} is replaced by an occurrence
   * of {@code newChar}.
   * <p>
   * Examples:
   * <blockquote><pre>
   * "mesquite in your cellar".replace('e', 'o')
   *         returns "mosquito in your collar"
   * "the war of baronets".replace('r', 'y')
   *         returns "the way of bayonets"
   * "sparring with a purple porpoise".replace('p', 't')
   *         returns "starring with a turtle tortoise"
   * "JonL".replace('q', 'x') returns "JonL" (no change)
   * </pre></blockquote>
   *
   * @param   oldChar   the old character.
   * @param   newChar   the new character.
   * @return  a string derived from this string by replacing every
   *          occurrence of {@code oldChar} with {@code newChar}.
   */
  //    public String replace(char oldChar, char newChar) {
  //  if (oldChar != newChar) {
  //    int len = value . length;
  //            int i = -1;
  //            char[] val = value; /* avoid getfield opcode */
  //
  //            while (++i < len) {
  //              if (val {
  //                [i] == oldChar}) {
  //                break;
  //              }
  //            }
  //            if (i < len) {
  //              char buf[] = new char[len];
  //                for (int j = 0; j < i {
  //                  ;
  //                } j++) {
  //                buf[j] = val[j];
  //                }
  //                while (i < len) {
  //                  char c = val[i];
  //                    buf[i] = (c == oldChar) ? newChar : c;
  //                    i++;
  //                }
  //                return new String(buf, true);
  //            }
  //        }
  //  return this;
  //}

  /**
   * Tells whether or not this string matches the given <a
   * href="../util/regex/Pattern.html#sum">regular expression</a>.
   *
   * <p> An invocation of this method of the form
   * <i>str</i>{@code .matches(}<i>regex</i>{@code )} yields exactly the
   * same result as the expression
   *
   * <blockquote>
   * {@link java.util.regex.Pattern}.{@link java.util.regex.Pattern#matches(String,CharSequence)
   * matches(<i>regex</i>, <i>str</i>)}
   * </blockquote>
   *
   * @param   regex
   *          the regular expression to which this string is to be matched
   *
   * @return  {@code true} if, and only if, this string matches the
   *          given regular expression
   *
   * @throws  PatternSyntaxException
   *          if the regular expression's syntax is invalid
   *
   * @see java.util.regex.Pattern
   *
   * @since 1.4
   * @spec JSR-51
   */
  //    public boolean matches(String regex) {
  //  return Pattern . matches(regex, this);
  //}

  /**
   * Returns true if and only if this string contains the specified
   * sequence of char values.
   *
   * @param s the sequence to search for
   * @return true if this string contains {@code s}, false otherwise
   * @since 1.5
   */
  //    public boolean contains(CharSequence s) {
  //  return indexOf(s . toString()) > -1;
  //}

  /**
   * Replaces the first substring of this string that matches the given <a
   * href="../util/regex/Pattern.html#sum">regular expression</a> with the
   * given replacement.
   *
   * <p> An invocation of this method of the form
   * <i>str</i>{@code .replaceFirst(}<i>regex</i>{@code ,} <i>repl</i>{@code )}
   * yields exactly the same result as the expression
   *
   * <blockquote>
   * <code>
   * {@link java.util.regex.Pattern}.{@link
   * java.util.regex.Pattern#compile compile}(<i>regex</i>).{@link
   * java.util.regex.Pattern#matcher(java.lang.CharSequence) matcher}(<i>str</i>).{@link
   * java.util.regex.Matcher#replaceFirst replaceFirst}(<i>repl</i>)
   * </code>
   * </blockquote>
   *
   *<p>
   * Note that backslashes ({@code \}) and dollar signs ({@code $}) in the
   * replacement string may cause the results to be different than if it were
   * being treated as a literal replacement string; see
   * {@link java.util.regex.Matcher#replaceFirst}.
   * Use {@link java.util.regex.Matcher#quoteReplacement} to suppress the special
   * meaning of these characters, if desired.
   *
   * @param   regex
   *          the regular expression to which this string is to be matched
   * @param   replacement
   *          the string to be substituted for the first match
   *
   * @return  The resulting {@code String}
   *
   * @throws  PatternSyntaxException
   *          if the regular expression's syntax is invalid
   *
   * @see java.util.regex.Pattern
   *
   * @since 1.4
   * @spec JSR-51
   */
  //    public String replaceFirst(String regex, String replacement) {
  //  return Pattern . compile(regex) . matcher(this) . replaceFirst(replacement);
  //}

  /**
   * Replaces each substring of this string that matches the given <a
   * href="../util/regex/Pattern.html#sum">regular expression</a> with the
   * given replacement.
   *
   * <p> An invocation of this method of the form
   * <i>str</i>{@code .replaceAll(}<i>regex</i>{@code ,} <i>repl</i>{@code )}
   * yields exactly the same result as the expression
   *
   * <blockquote>
   * <code>
   * {@link java.util.regex.Pattern}.{@link
   * java.util.regex.Pattern#compile compile}(<i>regex</i>).{@link
   * java.util.regex.Pattern#matcher(java.lang.CharSequence) matcher}(<i>str</i>).{@link
   * java.util.regex.Matcher#replaceAll replaceAll}(<i>repl</i>)
   * </code>
   * </blockquote>
   *
   *<p>
   * Note that backslashes ({@code \}) and dollar signs ({@code $}) in the
   * replacement string may cause the results to be different than if it were
   * being treated as a literal replacement string; see
   * {@link java.util.regex.Matcher#replaceAll Matcher.replaceAll}.
   * Use {@link java.util.regex.Matcher#quoteReplacement} to suppress the special
   * meaning of these characters, if desired.
   *
   * @param   regex
   *          the regular expression to which this string is to be matched
   * @param   replacement
   *          the string to be substituted for each match
   *
   * @return  The resulting {@code String}
   *
   * @throws  PatternSyntaxException
   *          if the regular expression's syntax is invalid
   *
   * @see java.util.regex.Pattern
   *
   * @since 1.4
   * @spec JSR-51
   */
  //    public String replaceAll(String regex, String replacement) {
  //  return Pattern . compile(regex) . matcher(this) . replaceAll(replacement);
  //}

  /**
   * Replaces each substring of this string that matches the literal target
   * sequence with the specified literal replacement sequence. The
   * replacement proceeds from the beginning of the string to the end, for
   * example, replacing "aa" with "b" in the string "aaa" will result in
   * "ba" rather than "ab".
   *
   * @param  target The sequence of char values to be replaced
   * @param  replacement The replacement sequence of char values
   * @return  The resulting string
   * @since 1.5
   */
  //    public String replace(CharSequence target, CharSequence replacement) {
  //  return Pattern . compile(target . toString(), Pattern . LITERAL) . matcher(
  //    this) . replaceAll(Matcher . quoteReplacement(replacement . toString()));
  //}

  /**
   * Splits this string around matches of the given
   * <a href="../util/regex/Pattern.html#sum">regular expression</a>.
   *
   * <p> The array returned by this method contains each substring of this
   * string that is terminated by another substring that matches the given
   * expression or is terminated by the end of the string.  The substrings in
   * the array are in the order in which they occur in this string.  If the
   * expression does not match any part of the input then the resulting array
   * has just one element, namely this string.
   *
   * <p> When there is a positive-width match at the beginning of this
   * string then an empty leading substring is included at the beginning
   * of the resulting array. A zero-width match at the beginning however
   * never produces such empty leading substring.
   *
   * <p> The {@code limit} parameter controls the number of times the
   * pattern is applied and therefore affects the length of the resulting
   * array.  If the limit <i>n</i> is greater than zero then the pattern
   * will be applied at most <i>n</i>&nbsp;-&nbsp;1 times, the array's
   * length will be no greater than <i>n</i>, and the array's last entry
   * will contain all input beyond the last matched delimiter.  If <i>n</i>
   * is non-positive then the pattern will be applied as many times as
   * possible and the array can have any length.  If <i>n</i> is zero then
   * the pattern will be applied as many times as possible, the array can
   * have any length, and trailing empty strings will be discarded.
   *
   * <p> The string {@code "boo:and:foo"}, for example, yields the
   * following results with these parameters:
   *
   * <blockquote><table cellpadding=1 cellspacing=0 summary="Split example showing regex, limit, and result">
   * <tr>
   *     <th>Regex</th>
   *     <th>Limit</th>
   *     <th>Result</th>
   * </tr>
   * <tr><td align=center>:</td>
   *     <td align=center>2</td>
   *     <td>{@code { "boo", "and:foo" }}</td></tr>
   * <tr><td align=center>:</td>
   *     <td align=center>5</td>
   *     <td>{@code { "boo", "and", "foo" }}</td></tr>
   * <tr><td align=center>:</td>
   *     <td align=center>-2</td>
   *     <td>{@code { "boo", "and", "foo" }}</td></tr>
   * <tr><td align=center>o</td>
   *     <td align=center>5</td>
   *     <td>{@code { "b", "", ":and:f", "", "" }}</td></tr>
   * <tr><td align=center>o</td>
   *     <td align=center>-2</td>
   *     <td>{@code { "b", "", ":and:f", "", "" }}</td></tr>
   * <tr><td align=center>o</td>
   *     <td align=center>0</td>
   *     <td>{@code { "b", "", ":and:f" }}</td></tr>
   * </table></blockquote>
   *
   * <p> An invocation of this method of the form
   * <i>str.</i>{@code split(}<i>regex</i>{@code ,}&nbsp;<i>n</i>{@code )}
   * yields the same result as the expression
   *
   * <blockquote>
   * <code>
   * {@link java.util.regex.Pattern}.{@link
   * java.util.regex.Pattern#compile compile}(<i>regex</i>).{@link
   * java.util.regex.Pattern#split(java.lang.CharSequence,int) split}(<i>str</i>,&nbsp;<i>n</i>)
   * </code>
   * </blockquote>
   *
   *
   * @param  regex
   *         the delimiting regular expression
   *
   * @param  limit
   *         the result threshold, as described above
   *
   * @return  the array of strings computed by splitting this string
   *          around matches of the given regular expression
   *
   * @throws  PatternSyntaxException
   *          if the regular expression's syntax is invalid
   *
   * @see java.util.regex.Pattern
   *
   * @since 1.4
   * @spec JSR-51
   */
  //    public String[] split(String regex, int limit) {
  //  /* fastpath if the regex is a
  //   (1)one-char String and this character is not one of the
  //      RegEx's meta characters ".$|()[{^?*+\\", or
  //   (2)two-char String and the first char is the backslash and
  //      the second is not the ascii digit or ascii letter.
  //   */
  //  char ch = 0;
  //        if (((regex . value . length == 1
  //              && ".$|()[{^?*+\\" . indexOf(ch = regex . charAt(0)) == -1)
  //             || (regex . length() == 2
  //                 && regex . charAt(0) == '\\'
  //                 && (((ch = regex . charAt(1)) - '0') | ('9' - ch)) < 0
  //                 && ((ch - 'a') | ('z' - ch)) < 0
  //                 && ((ch - 'A') | ('Z' - ch)) < 0))
  //            && (ch < Character . MIN_HIGH_SURROGATE
  //                || ch > Character . MAX_LOW_SURROGATE)
  //        ) {
  //          int off = 0;
  //            int next = 0;
  //            boolean limited = limit > 0;
  //            ArrayList < String> list = new ArrayList <> ();
  //            while ((next = indexOf(ch, off)) != -1) {
  //              if (!limited || list.size() < limit - 1) {
  //                list.add(substring(off, next));
  //                    off = next + 1;
  //                } else {    // last one
  //                //assert (list.size() == limit - 1);
  //                list.add(substring(off, value . length));
  //                    off = value . length;
  //                    break;
  //                }
  //            }
  //            // If no match was found, return this
  //            if (off == 0) {
  //              return new String[]}{
  //            this};
  //
  //            // Add remaining segment
  //            if (!limited || list.size() < limit)
  //                list.add(substring(off, value . length));
  //
  //            // Construct result
  //            int resultSize = list.size();
  //            if (limit == 0) {
  //              while (resultSize > 0 && list.get(resultSize - 1) . length() == 0) {
  //                resultSize--;
  //              }
  //            }
  //            String[] result = new String[resultSize];
  //            return list.subList(0, resultSize) . toArray(result);
  //        }
  //        return Pattern . compile(regex) . split(this, limit);
  //    }

  /**
   * Splits this string around matches of the given <a
   * href="../util/regex/Pattern.html#sum">regular expression</a>.
   *
   * <p> This method works as if by invoking the two-argument {@link
   * #split(String, int) split} method with the given expression and a limit
   * argument of zero.  Trailing empty strings are therefore not included in
   * the resulting array.
   *
   * <p> The string {@code "boo:and:foo"}, for example, yields the following
   * results with these expressions:
   *
   * <blockquote><table cellpadding=1 cellspacing=0 summary="Split examples showing regex and result">
   * <tr>
   *  <th>Regex</th>
   *  <th>Result</th>
   * </tr>
   * <tr><td align=center>:</td>
   *     <td>{@code { "boo", "and", "foo" }}</td></tr>
   * <tr><td align=center>o</td>
   *     <td>{@code { "b", "", ":and:f" }}</td></tr>
   * </table></blockquote>
   *
   *
   * @param  regex
   *         the delimiting regular expression
   *
   * @return  the array of strings computed by splitting this string
   *          around matches of the given regular expression
   *
   * @throws  PatternSyntaxException
   *          if the regular expression's syntax is invalid
   *
   * @see java.util.regex.Pattern
   *
   * @since 1.4
   * @spec JSR-51
   */
  //    public String[] split(String regex) {
  //  return split(regex, 0);
  //}

  /**
   * Returns a new String composed of copies of the
   * {@code CharSequence elements} joined together with a copy of
   * the specified {@code delimiter}.
   *
   * <blockquote>For example,
   * <pre>{@code
   *     String message = String.join("-", "Java", "is", "cool");
   *     // message returned is: "Java-is-cool"
   * }</pre></blockquote>
   *
   * Note that if an element is null, then {@code "null"} is added.
   *
   * @param  delimiter the delimiter that separates each element
   * @param  elements the elements to join together.
   *
   * @return a new {@code String} that is composed of the {@code elements}
   *         separated by the {@code delimiter}
   *
   * @throws NullPointerException If {@code delimiter} or {@code elements}
   *         is {@code null}
   *
   * @see java.util.StringJoiner
   * @since 1.8
   */
  //    public static String join(CharSequence delimiter, CharSequence... elements) {
  //  Objects . requireNonNull(delimiter);
  //  Objects . requireNonNull(elements);
  //  // Number of elements not likely worth Arrays.stream overhead.
  //  StringJoiner joiner = new StringJoiner(delimiter);
  //        for (CharSequence cs: elements) {
  //          joiner . add(cs);
  //        }
  //        return joiner . toString();
  //    }

  /**
   * Returns a new {@code String} composed of copies of the
   * {@code CharSequence elements} joined together with a copy of the
   * specified {@code delimiter}.
   *
   * <blockquote>For example,
   * <pre>{@code
   *     List<String> strings = new LinkedList<>();
   *     strings.add("Java");strings.add("is");
   *     strings.add("cool");
   *     String message = String.join(" ", strings);
   *     //message returned is: "Java is cool"
   *
   *     Set<String> strings = new LinkedHashSet<>();
   *     strings.add("Java"); strings.add("is");
   *     strings.add("very"); strings.add("cool");
   *     String message = String.join("-", strings);
   *     //message returned is: "Java-is-very-cool"
   * }</pre></blockquote>
   *
   * Note that if an individual element is {@code null}, then {@code "null"} is added.
   *
   * @param  delimiter a sequence of characters that is used to separate each
   *         of the {@code elements} in the resulting {@code String}
   * @param  elements an {@code Iterable} that will have its {@code elements}
   *         joined together.
   *
   * @return a new {@code String} that is composed from the {@code elements}
   *         argument
   *
   * @throws NullPointerException If {@code delimiter} or {@code elements}
   *         is {@code null}
   *
   * @see    #join(CharSequence,CharSequence...)
   * @see    java.util.StringJoiner
   * @since 1.8
   */
  //    public static String join(CharSequence delimiter,
  //            Iterable <? extends CharSequence > elements) {
  //  Objects . requireNonNull(delimiter);
  //  Objects . requireNonNull(elements);
  //  StringJoiner joiner = new StringJoiner(delimiter);
  //        for (CharSequence cs: elements) {
  //          joiner . add(cs);
  //        }
  //        return joiner . toString();
  //    }

  /**
   * Converts all of the characters in this {@code String} to lower
   * case using the rules of the given {@code Locale}.  Case mapping is based
   * on the Unicode Standard version specified by the {@link java.lang.Character Character}
   * class. Since case mappings are not always 1:1 char mappings, the resulting
   * {@code String} may be a different length than the original {@code String}.
   * <p>
   * Examples of lowercase  mappings are in the following table:
   * <table border="1" summary="Lowercase mapping examples showing language code of locale, upper case, lower case, and description">
   * <tr>
   *   <th>Language Code of Locale</th>
   *   <th>Upper Case</th>
   *   <th>Lower Case</th>
   *   <th>Description</th>
   * </tr>
   * <tr>
   *   <td>tr (Turkish)</td>
   *   <td>&#92;u0130</td>
   *   <td>&#92;u0069</td>
   *   <td>capital letter I with dot above -&gt; small letter i</td>
   * </tr>
   * <tr>
   *   <td>tr (Turkish)</td>
   *   <td>&#92;u0049</td>
   *   <td>&#92;u0131</td>
   *   <td>capital letter I -&gt; small letter dotless i </td>
   * </tr>
   * <tr>
   *   <td>(all)</td>
   *   <td>French Fries</td>
   *   <td>french fries</td>
   *   <td>lowercased all chars in String</td>
   * </tr>
   * <tr>
   *   <td>(all)</td>
   *   <td><img src="doc-files/capiota.gif" alt="capiota"><img src="doc-files/capchi.gif" alt="capchi">
   *       <img src="doc-files/captheta.gif" alt="captheta"><img src="doc-files/capupsil.gif" alt="capupsil">
   *       <img src="doc-files/capsigma.gif" alt="capsigma"></td>
   *   <td><img src="doc-files/iota.gif" alt="iota"><img src="doc-files/chi.gif" alt="chi">
   *       <img src="doc-files/theta.gif" alt="theta"><img src="doc-files/upsilon.gif" alt="upsilon">
   *       <img src="doc-files/sigma1.gif" alt="sigma"></td>
   *   <td>lowercased all chars in String</td>
   * </tr>
   * </table>
   *
   * @param locale use the case transformation rules for this locale
   * @return the {@code String}, converted to lowercase.
   * @see     java.lang.String#toLowerCase()
   * @see     java.lang.String#toUpperCase()
   * @see     java.lang.String#toUpperCase(Locale)
   * @since   1.1
   */
  //    public String toLowerCase(Locale locale) {
  //  if (locale == null) {
  //    throw new NullPointerException();
  //  }
  //
  //  int firstUpper;
  //        final int len = value . length;
  //
  //        /* Now check if there are any characters that need to be changed. */
  //        scan: {
  //    for (firstUpper = 0; firstUpper < len;) {
  //      char c = value[firstUpper];
  //                if ((c >= Character . MIN_HIGH_SURROGATE)
  //                    && (c <= Character . MAX_HIGH_SURROGATE)
  //                ) {
  //                  int supplChar = codePointAt(firstUpper);
  //                    if (supplChar != Character . toLowerCase(supplChar)) {
  //                      break scan;
  //                    }
  //                    firstUpper += Character . charCount(supplChar);
  //                } else {
  //                  if (c != Character . toLowerCase(c)) {
  //                    break scan;
  //                  }
  //                  firstUpper++;
  //                }
  //            }
  //    return this;
  //  }
  //
  //        char[] result = new char[len];
  //        int resultOffset = 0;  /* result may grow, so i+resultOffset
  //                                * is the write location in result */
  //
  //        /* Just copy the first few lowerCase characters. */
  //        System . arraycopy(value, 0, result, 0, firstUpper);
  //
  //        String lang = locale . getLanguage();
  //        boolean localeDependent =
  //    (lang == "tr" || lang == "az" || lang == "lt");
  //        char[] lowerCharArray;
  //        int lowerChar;
  //        int srcChar;
  //        int srcCount;
  //        for (int i = firstUpper; i < len {
  //          ;
  //        } i += srcCount) {
  //    srcChar = (int)value[i];
  //            if ((char) {
  //              srcChar >= Character . MIN_HIGH_SURROGATE
  //              && (char)}srcChar <= Character . MAX_HIGH_SURROGATE) {
  //      srcChar = codePointAt(i);
  //      srcCount = Character . charCount(srcChar);
  //    } else {
  //      srcCount = 1;
  //    }
  //            if (localeDependent
  //                || srcChar == '\u03A3'
  //                || // GREEK CAPITAL LETTER SIGMA
  //                srcChar == '\u0130'
  //            ) { // LATIN CAPITAL LETTER I WITH DOT ABOVE
  //              lowerChar = ConditionalSpecialCasing . toLowerCaseEx(this, i, locale);
  //            } else {
  //              lowerChar = Character . toLowerCase(srcChar);
  //            }
  //            if ((lowerChar == Character . ERROR)
  //                || (lowerChar >= Character . MIN_SUPPLEMENTARY_CODE_POINT)
  //            ) {
  //              if (lowerChar == Character . ERROR) {
  //                lowerCharArray =
  //                  ConditionalSpecialCasing . toLowerCaseCharArray(this, i, locale);
  //              } else {
  //                if (srcCount == 2) {
  //                  resultOffset += Character . toChars(lowerChar, result, i + resultOffset) - srcCount;
  //                  continue;
  //                } else {
  //                  lowerCharArray = Character . toChars(lowerChar);
  //                }
  //              }
  //
  //              /* Grow result if needed */
  //              int mapLen = lowerCharArray . length;
  //                if (mapLen > srcCount) {
  //                  char[] result2 = new char[result . length + mapLen - srcCount];
  //                    System . arraycopy(result, 0, result2, 0, i + resultOffset);
  //                    result = result2;
  //                }
  //                for (int x = 0; x < mapLen {
  //                  ;
  //                } ++x) {
  //                result[i + resultOffset + x] = lowerCharArray[x];
  //                }
  //                resultOffset += (mapLen - srcCount);
  //            } else {
  //              result[i + resultOffset] = (char)lowerChar;
  //            }
  //        }
  //        return new String(result, 0, len + resultOffset);
  //    }

  /**
   * Converts all of the characters in this {@code String} to lower
   * case using the rules of the default locale. This is equivalent to calling
   * {@code toLowerCase(Locale.getDefault())}.
   * <p>
   * <b>Note:</b> This method is locale sensitive, and may produce unexpected
   * results if used for strings that are intended to be interpreted locale
   * independently.
   * Examples are programming language identifiers, protocol keys, and HTML
   * tags.
   * For instance, {@code "TITLE".toLowerCase()} in a Turkish locale
   * returns {@code "t\u005Cu0131tle"}, where '\u005Cu0131' is the
   * LATIN SMALL LETTER DOTLESS I character.
   * To obtain correct results for locale insensitive strings, use
   * {@code toLowerCase(Locale.ROOT)}.
   * <p>
   * @return  the {@code String}, converted to lowercase.
   * @see     java.lang.String#toLowerCase(Locale)
   */
  //    public String toLowerCase(){
  //        return toLowerCase(Locale . getDefault());
  //    }

  /**
   * Converts all of the characters in this {@code String} to upper
   * case using the rules of the given {@code Locale}. Case mapping is based
   * on the Unicode Standard version specified by the {@link java.lang.Character Character}
   * class. Since case mappings are not always 1:1 char mappings, the resulting
   * {@code String} may be a different length than the original {@code String}.
   * <p>
   * Examples of locale-sensitive and 1:M case mappings are in the following table.
   *
   * <table border="1" summary="Examples of locale-sensitive and 1:M case mappings. Shows Language code of locale, lower case, upper case, and description.">
   * <tr>
   *   <th>Language Code of Locale</th>
   *   <th>Lower Case</th>
   *   <th>Upper Case</th>
   *   <th>Description</th>
   * </tr>
   * <tr>
   *   <td>tr (Turkish)</td>
   *   <td>&#92;u0069</td>
   *   <td>&#92;u0130</td>
   *   <td>small letter i -&gt; capital letter I with dot above</td>
   * </tr>
   * <tr>
   *   <td>tr (Turkish)</td>
   *   <td>&#92;u0131</td>
   *   <td>&#92;u0049</td>
   *   <td>small letter dotless i -&gt; capital letter I</td>
   * </tr>
   * <tr>
   *   <td>(all)</td>
   *   <td>&#92;u00df</td>
   *   <td>&#92;u0053 &#92;u0053</td>
   *   <td>small letter sharp s -&gt; two letters: SS</td>
   * </tr>
   * <tr>
   *   <td>(all)</td>
   *   <td>Fahrvergn&uuml;gen</td>
   *   <td>FAHRVERGN&Uuml;GEN</td>
   *   <td></td>
   * </tr>
   * </table>
   * @param locale use the case transformation rules for this locale
   * @return the {@code String}, converted to uppercase.
   * @see     java.lang.String#toUpperCase()
   * @see     java.lang.String#toLowerCase()
   * @see     java.lang.String#toLowerCase(Locale)
   * @since   1.1
   */
  //    public String toUpperCase(Locale locale) {
  //  if (locale == null) {
  //    throw new NullPointerException();
  //  }
  //
  //  int firstLower;
  //        final int len = value . length;
  //
  //        /* Now check if there are any characters that need to be changed. */
  //        scan: {
  //    for (firstLower = 0; firstLower < len;) {
  //      int c = (int)value[firstLower];
  //                int srcCount;
  //                if ((c >= Character . MIN_HIGH_SURROGATE)
  //                    && (c <= Character . MAX_HIGH_SURROGATE)
  //                ) {
  //                  c = codePointAt(firstLower);
  //                  srcCount = Character . charCount(c);
  //                } else {
  //                  srcCount = 1;
  //                }
  //                int upperCaseChar = Character . toUpperCaseEx(c);
  //                if ((upperCaseChar == Character . ERROR)
  //                    || (c != upperCaseChar)
  //                ) {
  //                  break scan;
  //                }
  //                firstLower += srcCount;
  //            }
  //    return this;
  //  }
  //
  //        /* result may grow, so i+resultOffset is the write location in result */
  //        int resultOffset = 0;
  //        char[] result = new char[len]; /* may grow */
  //
  //        /* Just copy the first few upperCase characters. */
  //        System . arraycopy(value, 0, result, 0, firstLower);
  //
  //        String lang = locale . getLanguage();
  //        boolean localeDependent =
  //    (lang == "tr" || lang == "az" || lang == "lt");
  //        char[] upperCharArray;
  //        int upperChar;
  //        int srcChar;
  //        int srcCount;
  //        for (int i = firstLower; i < len {
  //          ;
  //        } i += srcCount) {
  //    srcChar = (int)value[i];
  //            if ((char) {
  //              srcChar >= Character . MIN_HIGH_SURROGATE
  //              && (char)}srcChar <= Character . MAX_HIGH_SURROGATE) {
  //      srcChar = codePointAt(i);
  //      srcCount = Character . charCount(srcChar);
  //    } else {
  //      srcCount = 1;
  //    }
  //            if (localeDependent) {
  //              upperChar = ConditionalSpecialCasing . toUpperCaseEx(this, i, locale);
  //            } else {
  //              upperChar = Character . toUpperCaseEx(srcChar);
  //            }
  //            if ((upperChar == Character . ERROR)
  //                || (upperChar >= Character . MIN_SUPPLEMENTARY_CODE_POINT)
  //            ) {
  //              if (upperChar == Character . ERROR) {
  //                if (localeDependent) {
  //                  upperCharArray =
  //                    ConditionalSpecialCasing . toUpperCaseCharArray(this, i, locale);
  //                } else {
  //                  upperCharArray = Character . toUpperCaseCharArray(srcChar);
  //                }
  //              } else {
  //                if (srcCount == 2) {
  //                  resultOffset += Character . toChars(upperChar, result, i + resultOffset) - srcCount;
  //                  continue;
  //                } else {
  //                  upperCharArray = Character . toChars(upperChar);
  //                }
  //              }
  //
  //              /* Grow result if needed */
  //              int mapLen = upperCharArray . length;
  //                if (mapLen > srcCount) {
  //                  char[] result2 = new char[result . length + mapLen - srcCount];
  //                    System . arraycopy(result, 0, result2, 0, i + resultOffset);
  //                    result = result2;
  //                }
  //                for (int x = 0; x < mapLen {
  //                  ;
  //                } ++x) {
  //                result[i + resultOffset + x] = upperCharArray[x];
  //                }
  //                resultOffset += (mapLen - srcCount);
  //            } else {
  //              result[i + resultOffset] = (char)upperChar;
  //            }
  //        }
  //        return new String(result, 0, len + resultOffset);
  //    }

  /**
   * Converts all of the characters in this {@code String} to upper
   * case using the rules of the default locale. This method is equivalent to
   * {@code toUpperCase(Locale.getDefault())}.
   * <p>
   * <b>Note:</b> This method is locale sensitive, and may produce unexpected
   * results if used for strings that are intended to be interpreted locale
   * independently.
   * Examples are programming language identifiers, protocol keys, and HTML
   * tags.
   * For instance, {@code "title".toUpperCase()} in a Turkish locale
   * returns {@code "T\u005Cu0130TLE"}, where '\u005Cu0130' is the
   * LATIN CAPITAL LETTER I WITH DOT ABOVE character.
   * To obtain correct results for locale insensitive strings, use
   * {@code toUpperCase(Locale.ROOT)}.
   * <p>
   * @return  the {@code String}, converted to uppercase.
   * @see     java.lang.String#toUpperCase(Locale)
   */
  //    public String toUpperCase(){
  //        return toUpperCase(Locale . getDefault());
  //    }

  /**
   * Converts this string to a new character array.
   *
   * @return  a newly allocated character array whose length is the length
   *          of this string and whose contents are initialized to contain
   *          the character sequence represented by this string.
   */
  //    public char[] toCharArray(){
  //                  // Cannot use Arrays.copyOf because of class initialization order issues
  //                  char result[] = new char[value . length];
  //        System . arraycopy(value, 0, result, 0, value . length);
  //        return result;
  //    }

  /**
   * Returns a formatted string using the specified format string and
   * arguments.
   *
   * <p> The locale always used is the one returned by {@link
   * java.util.Locale#getDefault() Locale.getDefault()}.
   *
   * @param  format
   *         A <a href="../util/Formatter.html#syntax">format string</a>
   *
   * @param  args
   *         Arguments referenced by the format specifiers in the format
   *         string.  If there are more arguments than format specifiers, the
   *         extra arguments are ignored.  The number of arguments is
   *         variable and may be zero.  The maximum number of arguments is
   *         limited by the maximum dimension of a Java array as defined by
   *         <cite>The Java&trade; Virtual Machine Specification</cite>.
   *         The behaviour on a
   *         {@code null} argument depends on the <a
   *         href="../util/Formatter.html#syntax">conversion</a>.
   *
   * @throws  java.util.IllegalFormatException
   *          If a format string contains an illegal syntax, a format
   *          specifier that is incompatible with the given arguments,
   *          insufficient arguments given the format string, or other
   *          illegal conditions.  For specification of all possible
   *          formatting errors, see the <a
   *          href="../util/Formatter.html#detail">Details</a> section of the
   *          formatter class specification.
   *
   * @return  A formatted string
   *
   * @see  java.util.Formatter
   * @since  1.5
   */
  //    public static String format(String format, Object... args) {
  //  return new Formatter() . format(format, args) . toString();
  //}

  /**
   * Returns a formatted string using the specified locale, format string,
   * and arguments.
   *
   * @param  l
   *         The {@linkplain java.util.Locale locale} to apply during
   *         formatting.  If {@code l} is {@code null} then no localization
   *         is applied.
   *
   * @param  format
   *         A <a href="../util/Formatter.html#syntax">format string</a>
   *
   * @param  args
   *         Arguments referenced by the format specifiers in the format
   *         string.  If there are more arguments than format specifiers, the
   *         extra arguments are ignored.  The number of arguments is
   *         variable and may be zero.  The maximum number of arguments is
   *         limited by the maximum dimension of a Java array as defined by
   *         <cite>The Java&trade; Virtual Machine Specification</cite>.
   *         The behaviour on a
   *         {@code null} argument depends on the
   *         <a href="../util/Formatter.html#syntax">conversion</a>.
   *
   * @throws  java.util.IllegalFormatException
   *          If a format string contains an illegal syntax, a format
   *          specifier that is incompatible with the given arguments,
   *          insufficient arguments given the format string, or other
   *          illegal conditions.  For specification of all possible
   *          formatting errors, see the <a
   *          href="../util/Formatter.html#detail">Details</a> section of the
   *          formatter class specification
   *
   * @return  A formatted string
   *
   * @see  java.util.Formatter
   * @since  1.5
   * //     */
  //    public static String format(Locale l, String format, Object... args) {
  //  return new Formatter(l) . format(format, args) . toString();
  //}

  /**
   * Returns the string representation of the {@code Object} argument.
   *
   * @param   obj   an {@code Object}.
   * @return  if the argument is {@code null}, then a string equal to
   *          {@code "null"}; otherwise, the value of
   *          {@code obj.toString()} is returned.
   * @see     java.lang.Object#toString()
   */
  //    public static String valueOf(Object obj) {
  //  return (obj == null) ? "null" : obj . toString();
  //}

  /**
   * Returns the string representation of the {@code char} array
   * argument. The contents of the character array are copied; subsequent
   * modification of the character array does not affect the returned
   * string.
   *
   * @param   data     the character array.
   * @return  a {@code String} that contains the characters of the
   *          character array.
   */
  //    public static String valueOf(char data[]) {
  //  return new String(data);
  //}

  /**
   * Returns the string representation of a specific subarray of the
   * {@code char} array argument.
   * <p>
   * The {@code offset} argument is the index of the first
   * character of the subarray. The {@code count} argument
   * specifies the length of the subarray. The contents of the subarray
   * are copied; subsequent modification of the character array does not
   * affect the returned string.
   *
   * @param   data     the character array.
   * @param   offset   initial offset of the subarray.
   * @param   count    length of the subarray.
   * @return  a {@code String} that contains the characters of the
   *          specified subarray of the character array.
   * @exception IndexOutOfBoundsException if {@code offset} is
   *          negative, or {@code count} is negative, or
   *          {@code offset+count} is larger than
   *          {@code data.length}.
   */
  //    public static String valueOf(char data[], int offset, int count) {
  //  return new String(data, offset, count);
  //}

  /**
   * Equivalent to {@link #valueOf(char[], int, int)}.
   *
   * @param   data     the character array.
   * @param   offset   initial offset of the subarray.
   * @param   count    length of the subarray.
   * @return  a {@code String} that contains the characters of the
   *          specified subarray of the character array.
   * @exception IndexOutOfBoundsException if {@code offset} is
   *          negative, or {@code count} is negative, or
   *          {@code offset+count} is larger than
   *          {@code data.length}.
   */
  //    public static String copyValueOf(char data[], int offset, int count) {
  //  return new String(data, offset, count);
  //}

  /**
   * Equivalent to {@link #valueOf(char[])}.
   *
   * @param   data   the character array.
   * @return  a {@code String} that contains the characters of the
   *          character array.
   */
  //    public static String copyValueOf(char data[]) {
  //  return new String(data);
  //}

  /**
   * Returns the string representation of the {@code boolean} argument.
   *
   * @param   b   a {@code boolean}.
   * @return  if the argument is {@code true}, a string equal to
   *          {@code "true"} is returned; otherwise, a string equal to
   *          {@code "false"} is returned.
   */
  //    public static String valueOf(boolean b) {
  //  return b ? "true" : "false";
  //}

  /**
   * Returns the string representation of the {@code char}
   * argument.
   *
   * @param   c   a {@code char}.
   * @return  a string of length {@code 1} containing
   *          as its single character the argument {@code c}.
   */
  //    public static String valueOf(char c) {
  //  char data[] = {
  //    c};
  //        return new String(data, true);
  //    }

  /**
   * Returns the string representation of the {@code int} argument.
   * <p>
   * The representation is exactly the one returned by the
   * {@code Integer.toString} method of one argument.
   *
   * @param   i   an {@code int}.
   * @return  a string representation of the {@code int} argument.
   * @see     java.lang.Integer#toString(int, int)
   */
  //    public static String valueOf(int i) {
  //  return Integer . toString(i);
  //}

  /**
   * Returns the string representation of the {@code long} argument.
   * <p>
   * The representation is exactly the one returned by the
   * {@code Long.toString} method of one argument.
   *
   * @param   l   a {@code long}.
   * @return  a string representation of the {@code long} argument.
   * @see     java.lang.Long#toString(long)
   */
  //    public static String valueOf(long l) {
  //  return Long . toString(l);
  //}

  /**
   * Returns the string representation of the {@code float} argument.
   * <p>
   * The representation is exactly the one returned by the
   * {@code Float.toString} method of one argument.
   *
   * @param   f   a {@code float}.
   * @return  a string representation of the {@code float} argument.
   * @see     java.lang.Float#toString(float)
   */
  //    public static String valueOf(float f) {
  //  return Float . toString(f);
  //}

  /**
   * Returns the string representation of the {@code double} argument.
   * <p>
   * The representation is exactly the one returned by the
   * {@code Double.toString} method of one argument.
   *
   * @param   d   a {@code double}.
   * @return  a  string representation of the {@code double} argument.
   * @see     java.lang.Double#toString(double)
   */
  //    public static String valueOf(double d) {
  //  return Double . toString(d);
  //}

  /**
   * Returns a canonical representation for the string object.
   * <p>
   * A pool of strings, initially empty, is maintained privately by the
   * class {@code String}.
   * <p>
   * When the intern method is invoked, if the pool already contains a
   * string equal to this {@code String} object as determined by
   * the {@link #equals(Object)} method, then the string from the pool is
   * returned. Otherwise, this {@code String} object is added to the
   * pool and a reference to this {@code String} object is returned.
   * <p>
   * It follows that for any two strings {@code s} and {@code t},
   * {@code s.intern() == t.intern()} is {@code true}
   * if and only if {@code s.equals(t)} is {@code true}.
   * <p>
   * All literal strings and string-valued constant expressions are
   * interned. String literals are defined in section 3.10.5 of the
   * <cite>The Java&trade; Language Specification</cite>.
   *
   * @return  a string that has the same contents as this string, but is
   *          guaranteed to be from a pool of unique strings.
   */
  //public native String intern();
}