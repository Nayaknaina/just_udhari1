<?php
class TypeCode128 {
    public function encode($text) {
        // VERY SIMPLIFIED: Turn each char into binary bits
        $bars = [];
        foreach (str_split($text) as $char) {
            $ascii = ord($char);
            $bin = str_pad(decbin($ascii), 8, '0', STR_PAD_LEFT);
            foreach (str_split($bin) as $bit) {
                $bars[] = $bit === '1';
            }
            // small spacer
            $bars[] = false;
        }
        return $bars;
    }
}
