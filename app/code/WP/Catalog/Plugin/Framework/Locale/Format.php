<?php
namespace WP\Catalog\Plugin\Framework\Locale;

class Format
{
    public function afterGetPriceFormat($subject, $result) {
        $result['precision'] = 2;
        $result['requiredPrecision'] = 2;

        return $result;
    }
}
