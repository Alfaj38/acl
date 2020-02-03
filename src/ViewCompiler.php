<?php
namespace Alfaj\Acl;

trait ViewCompiler {

    /**
     * @author Alfaj
     * Check parser nullable string
     */
    private static function _nullsafeParser($str, $pre = '') {
        $head = substr($str, 0, strpos($str, '->'));
        $tail = substr($str, strlen($head) + 2);

        if (strpos($tail, '->') == 0) {
            return '(' . $pre . $head . ')?' . $pre . $head . '->' . $tail . ':\'\'';
        } else {
            return '(' . $pre . $head . ')?' . self::_nullsafeParser($tail, $pre . $head . '->') . ':\'\'';
        }
    }

}
