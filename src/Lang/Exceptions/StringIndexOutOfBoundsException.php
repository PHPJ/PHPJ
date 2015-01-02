<?php
/**
 * Created by IntelliJ IDEA.
 * User: ykmship
 * Date: 03/01/15
 * Time: 1:06 AM
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