<?php
namespace Rokanthemes\ProductBanner\Block\Widget;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\DataObject\IdentityInterface;
use Rokanthemes\ProductTab\Helper\Data as HelperModule;

class ProductBanner extends \Magento\Catalog\Block\Product\AbstractProduct  implements \Magento\Widget\Block\BlockInterface
{
    protected $_template = "widget/banner_slide.phtml";
	
    protected $_defaultToolbarBlock = 'Magento\Catalog\Block\Product\ProductList\Toolbar';
    /**
     * Product Collection
     *
     * @var AbstractCollection
     */
    protected $_productCollection;

    /**
     * Catalog layer
     *
     * @var \Magento\Catalog\Model\Layer
     */
    protected $_catalogLayer;

    /**
     * @var \Magento\Framework\Data\Helper\PostHelper
     */
    protected $_postDataHelper;

    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    protected $urlHelper;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;
    protected $_productCollectionFactory;
    protected $_storeManager;
    protected $catalogConfig;
    protected $productVisibility;
    protected $scopeConfig;
	protected $_productImageHelper;
    /**
     * @param Context $context
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
		\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
		\Magento\Catalog\Helper\Image $productImageHelper,
		\Magento\Catalog\Model\Product\Visibility $productVisibility,
        array $data = []
    ) { 
        $this->_catalogLayer = $layerResolver->get();
        $this->_postDataHelper = $postDataHelper;
        $this->categoryRepository = $categoryRepository;
        $this->urlHelper = $urlHelper;
        $this->_productCollectionFactory = $productCollectionFactory;
		$this->_productImageHelper = $productImageHelper;
        $this->_storeManager = $context->getStoreManager();
        $this->catalogConfig = $context->getCatalogConfig();
        $this->productVisibility = $productVisibility;
        parent::__construct(
            $context,
            $data
        );
    }
	
	public function getConfig($value=''){
	   $config = $this->getData($value);
	   return $config; 
	}
	
    public function _getProductCollection()
    {
        $type = $this->getConfig('type');
        switch ($type)
        {
            case HelperModule::TYPE_BEST_SELLER:
                return $this->_getBestSellerProductCollection();
                break;
            case HelperModule::TYPE_FEATURER:
                return $this->_getFeaturedProductCollection();
                break;
            case HelperModule::TYPE_MOST_VIEWED:
                return $this->_getMostViewProductCollection();
                break;
            case HelperModule::TYPE_NEW:
                return $this->_getRecentlyAddedProductsCollection();
                break;
            case HelperModule::TYPE_TOP_RATE:
                return $this->_getTopRateProductCollection();
                break;
            case HelperModule::TYPE_ON_SALE:
                return $this->_getOnSaleProductCollection();
                break;
        }
        return $this->_getRandomProductCollection();
    }
    /**
     * Prepare collection for recent product list
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection|Object|\Magento\Framework\Data\Collection
     */
    protected function _getRecentlyAddedProductsCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->addAttributeToFilter('new', 1)
            ->addAttributeToSort('created_at', 'desc')
            ->setPageSize($this->getConfig('limit'));
        return $collection;
    }
    protected function _getBestSellerProductCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

        $tableMostViewed = $collection->getTable('rokanthemes_sorting_bestseller');
        $tableAlias = $collection::MAIN_TABLE_ALIAS;
        $storeId = $this->_storeManager->getStore()->getId();
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->setPageSize($this->getConfig('limit'));
        $collection->getSelect()->joinLeft(
            ['bestseller' => $tableMostViewed],
            "$tableAlias.entity_id = bestseller.product_id and bestseller.store_id = $storeId",
            ["bestseller.bestseller"]
        );
        $collection->getSelect()->order('bestseller desc');
        return $collection;
    }
    protected function _getFeaturedProductCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->addAttributeToFilter('is_featured', 1)
            ->setPageSize($this->getConfig('limit'));
        return $collection;
    }
    protected function _getMostViewProductCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $tableMostViewed = $collection->getTable('rokanthemes_sorting_most_viewed');
        $tableAlias = $collection::MAIN_TABLE_ALIAS;
        $storeId = $this->_storeManager->getStore()->getId();
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->setPageSize($this->getPageSize());
        $collection->getSelect()->joinLeft(
            ['mostviewed' => $tableMostViewed],
            "$tableAlias.entity_id = mostviewed.product_id and mostviewed.store_id = $storeId",
            ["mostviewed.viewed"]
        );
        $collection->getSelect()->order('viewed desc');
        return $collection;
    }
    protected function _getTopRateProductCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $tableReview = $collection->getTable('review_entity_summary');
        $tableAlias = $collection::MAIN_TABLE_ALIAS;
        $storeId = $this->_storeManager->getStore()->getId();
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->setPageSize($this->getConfig('limit'));
        $collection->getSelect()->joinLeft(
            ['top_review' => $tableReview],
        "$tableAlias.entity_id = top_review.entity_pk_value and top_review.store_id = $storeId and top_review.entity_type = 1",
            ["top_review.rating_summary", 'top_review.reviews_count']
        );
        $collection->getSelect()->order('rating_summary desc');
        $collection->getSelect()->order('reviews_count desc');
        return $collection;
    }
    protected function _getOnSaleProductCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $date = $this->_date->gmtDate();
        $tableAlias = $collection::MAIN_TABLE_ALIAS;
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->setPageSize($this->getConfig('limit'));
        $collection->addAttributeToFilter('special_price', ['notnull'=> true]);
        //$collection->addAttributeToFilter('special_price', ['lt'=> new \Zend_Db_Expr("$tableAlias.price")]);
        $collection->addAttributeToFilter('special_from_date', [['lteq'=> $date],['null'=> true]]);
        $collection->addAttributeToFilter('special_to_date', [['gteq'=> $date],['null'=> true]]);
        return $collection;
    }
    protected function _getRandomProductCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->setPageSize($this->getConfig('limit'));
        $collection->getSelect()->order('RAND()');
        return $collection;
    }
	
	function cut_string_featuredproduct($string,$number){
		if(strlen($string) <= $number) {
			return $string;
		}
		else {	
			if(strpos($string," ",$number) > $number){
				$new_space = strpos($string," ",$number);
				$new_string = substr($string,0,$new_space)."..";
				return $new_string;
			}
			$new_string = substr($string,0,$number)."..";
			return $new_string;
		}
	}
	
	protected function getAddToCartPostParams(\Magento\Catalog\Model\Product $product)
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED =>
                    $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }

}
?>
