<?php
namespace Rokanthemes\ProductBanner\Model\Config\Source;

class ListMode implements \Magento\Framework\Option\ArrayInterface{

	const TYPE_BEST_SELLER = 1;
    const TYPE_FEATURER = 2;
    const TYPE_MOST_VIEWED = 3;
    const TYPE_NEW = 4;
    const TYPE_TOP_RATE = 5;
    const TYPE_ON_SALE = 6;
    const TYPE_RANDOM = 7;
	
    public function toOptionArray()
    {
        $result = [
			['value' => self::TYPE_BEST_SELLER, 'label' => __('Best Seller')],
			['value' => self::TYPE_FEATURER, 'label' => __('Featured')],
			['value' => self::TYPE_MOST_VIEWED, 'label' => __('Most Viewed')],
			['value' => self::TYPE_NEW, 'label' => __('New arrival')],
			['value' => self::TYPE_TOP_RATE, 'label' => __('Top Rate')],
			['value' => self::TYPE_ON_SALE, 'label' => __('On Sale')],
			['value' => self::TYPE_RANDOM, 'label' => __('Random')]
        ];
        return $result;
    }
}
