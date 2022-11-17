<?php
/**
 * Copyright © ITM-Development All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace ITM\MagB1\Model\AttributeSource;

class ItmProperties extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory
     */
    protected $_optionFactory;

    /**
     * @param \Magento\Tax\Model\ResourceModel\TaxClass\CollectionFactory $classesFactory
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory $optionFactory
     * @param \Magento\Tax\Api\TaxClassRepositoryInterface $taxClassRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     */
    public function __construct(
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory $optionFactory
    ) {

        $this->_optionFactory = $optionFactory;
    }


    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [
            ['value' => 'p01', 'label' => __('Property01')],
            ['value' => 'p02', 'label' => __('Property02')],
            ['value' => 'p03', 'label' => __('Property03')],
            ['value' => 'p04', 'label' => __('Property04')],
            ['value' => 'p05', 'label' => __('Property05')],
            ['value' => 'p06', 'label' => __('Property06')],
            ['value' => 'p07', 'label' => __('Property07')],
            ['value' => 'p08', 'label' => __('Property08')],
            ['value' => 'p09', 'label' => __('Property09')],
            ['value' => 'p10', 'label' => __('Property10')],
            ['value' => 'p11', 'label' => __('Property11')],
            ['value' => 'p12', 'label' => __('Property12')],
            ['value' => 'p13', 'label' => __('Property13')],
            ['value' => 'p14', 'label' => __('Property14')],
            ['value' => 'p15', 'label' => __('Property15')],
            ['value' => 'p16', 'label' => __('Property16')],
            ['value' => 'p17', 'label' => __('Property17')],
            ['value' => 'p18', 'label' => __('Property18')],
            ['value' => 'p19', 'label' => __('Property19')],
            ['value' => 'p20', 'label' => __('Property20')],
            ['value' => 'p21', 'label' => __('Property21')],
            ['value' => 'p22', 'label' => __('Property22')],
            ['value' => 'p23', 'label' => __('Property23')],
            ['value' => 'p24', 'label' => __('Property24')],
            ['value' => 'p25', 'label' => __('Property25')],
            ['value' => 'p26', 'label' => __('Property26')],
            ['value' => 'p27', 'label' => __('Property27')],
            ['value' => 'p28', 'label' => __('Property28')],
            ['value' => 'p29', 'label' => __('Property29')],
            ['value' => 'p30', 'label' => __('Property30')],
            ['value' => 'p31', 'label' => __('Property31')],
            ['value' => 'p32', 'label' => __('Property32')],
            ['value' => 'p33', 'label' => __('Property33')],
            ['value' => 'p34', 'label' => __('Property34')],
            ['value' => 'p35', 'label' => __('Property35')],
            ['value' => 'p36', 'label' => __('Property36')],
            ['value' => 'p37', 'label' => __('Property37')],
            ['value' => 'p38', 'label' => __('Property38')],
            ['value' => 'p39', 'label' => __('Property39')],
            ['value' => 'p40', 'label' => __('Property40')],
            ['value' => 'p41', 'label' => __('Property41')],
            ['value' => 'p42', 'label' => __('Property42')],
            ['value' => 'p43', 'label' => __('Property43')],
            ['value' => 'p44', 'label' => __('Property44')],
            ['value' => 'p45', 'label' => __('Property45')],
            ['value' => 'p46', 'label' => __('Property46')],
            ['value' => 'p47', 'label' => __('Property47')],
            ['value' => 'p48', 'label' => __('Property48')],
            ['value' => 'p49', 'label' => __('Property49')],
            ['value' => 'p50', 'label' => __('Property50')],
            ['value' => 'p51', 'label' => __('Property51')],
            ['value' => 'p52', 'label' => __('Property52')],
            ['value' => 'p53', 'label' => __('Property53')],
            ['value' => 'p54', 'label' => __('Property54')],
            ['value' => 'p55', 'label' => __('Property55')],
            ['value' => 'p56', 'label' => __('Property56')],
            ['value' => 'p57', 'label' => __('Property57')],
            ['value' => 'p58', 'label' => __('Property58')],
            ['value' => 'p59', 'label' => __('Property59')],
            ['value' => 'p60', 'label' => __('Property60')],
            ['value' => 'p61', 'label' => __('Property61')],
            ['value' => 'p62', 'label' => __('Property62')],
            ['value' => 'p63', 'label' => __('Property63')],
            ['value' => 'p64', 'label' => __('Property64')]
        ];
        return $this->_options;
    }

    /**
     * @return array
     */
    public function getFlatColumns()
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        return [
            $attributeCode => [
                'unsigned' => false,
                'default' => null,
                'extra' => null,
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => $attributeCode . ' column',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getFlatIndexes()
    {
        $indexes = [];

        $index = 'IDX_' . strtoupper($this->getAttribute()->getAttributeCode());
        $indexes[$index] = ['type' => 'index', 'fields' => [$this->getAttribute()->getAttributeCode()]];

        return $indexes;
    }

    /**
     * @param int $store
     * @return \Magento\Framework\DB\Select|null
     */
    public function getFlatUpdateSelect($store)
    {

        return $this->_optionFactory->create()->getFlatUpdateSelect($this->getAttribute(), $store);
    }
}
