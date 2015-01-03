<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Lang\Exceptions;

/**
 * Thrown to indicate that an index of string  is out of range.
 * <p>
 * Applications can subclass this class to indicate similar exceptions.
 *
 * Class StringIndexOutOfBoundsException
 * @package PHPJ\Lang\Exceptions
 */
class StringIndexOutOfBoundsException extends IndexOutOfBoundsException
{

    public function __construct($index)
    {
        parent::__construct("String index out of range: " . $index);
    }
}