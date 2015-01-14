<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\Lang\Interfaces;


interface Appendable {

  /**
   * @param $string string
   * @param $start  int
   * @param $end    int
   * @return Appendable
   */
  public function append($string, $start, $end);
}