<?php

namespace ITM\MagB1\Api;

interface OrderfilesInterface
{

   /**
    * Get list
    *
    * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    * @return \Magento\Framework\Api\SearchResultsInterface
    */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     *
     * @api
     * @param ITM\MagB1\Api\Data\OrderfilesDataInterface $entity
     * @param string $fileName
     * @return ITM\MagB1\Api\Data\OrderfilesDataInterface
     */
    public function save($entity, $fileName);

    /**
     * @param string $increment_id
     * @return bool Will returned true if deleted
     */
    public function delete($increment_id);
}
