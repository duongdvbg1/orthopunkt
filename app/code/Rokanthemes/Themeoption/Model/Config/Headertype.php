<?php
namespace Rokanthemes\Themeoption\Model\Config;

class Headertype implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'header_digital_01', 'label' => __('Digital 01')],
            ['value' => 'header_digital_02', 'label' => __('Digital 02')],
            ['value' => 'header_digital_03', 'label' => __('Digital 03')],
            ['value' => 'header_digital_04', 'label' => __('Digital 04')],
            ['value' => 'header_digital_05', 'label' => __('Digital 05')],
            ['value' => 'header_digital_06', 'label' => __('Digital 06')],
            ['value' => 'header_tool_01', 'label' => __('Tool 01')],
            ['value' => 'header_tool_02', 'label' => __('Tool 02')],
            ['value' => 'header_sport_01', 'label' => __('Sport 01')],
            ['value' => 'header_medical_01', 'label' => __('Medical 01')],
            ['value' => 'header_medical_02', 'label' => __('Medical 02')],    
            ['value' => 'header_organic_01', 'label' => __('Organic 01')],  
            ['value' => 'header_market_01', 'label' => __('Market 01')],  
        ];
    }

    public function toArray()
    {
        return [
            'header_digital_01' => __('Digital 01'),
            'header_digital_02' => __('Digital 02'),
            'header_digital_03' => __('Digital 03'),
            'header_digital_04' => __('Digital 04'),
            'header_digital_05' => __('Digital 05'),
            'header_digital_06' => __('Digital 06'),
            'header_tool_01' => __('Tool 01'),
            'header_tool_02' => __('Tool 02'),
           'header_sport_01' => __('Sport 01'),
           'header_medical_01' => __('Medical 01'),
           'header_medical_02' => __('Medical 02'),
           'header_organic_01' => __('Organic 01'),
           'header_market_01' => __('Market 01'),
        ];
    }
}