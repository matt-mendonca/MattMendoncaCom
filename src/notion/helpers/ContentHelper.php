<?php
namespace Notion\Helpers;

class ContentHelper
{
    public static function getDirectoryTree($dir) {
        $directory_tree = array();
        foreach ( $dir as $node ):
            if ($node->isDir() && !$node->isDot()):
                $directory_tree[$node->getFilename()] = getDirectoryTree(
                    new DirectoryIterator(
                        $node->getPathname()
                    )
                );
            elseif ($node->isFile()):
                $directory_tree[] = $node->getFilename();
            endif;
        endforeach;

        return $directory_tree;
    }
}