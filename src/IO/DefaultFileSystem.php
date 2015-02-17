<?php
/**
 * @author Yury Kozyrev [https://github.com/urakozz]
 */

namespace PHPJ\IO;


use Kozz\Components\Cache\StaticCache;
use PHPJ\Lang\ObjectClass;
use Symfony\Component\DependencyInjection\Definition;

class DefaultFileSystem extends ObjectClass
{

  /**
   * @return FileSystem
   */
  public static function getFileSystem()
  {
    return StaticCache::loadInjection('file_system', new Definition(UnixFileSystem::class));
  }
}