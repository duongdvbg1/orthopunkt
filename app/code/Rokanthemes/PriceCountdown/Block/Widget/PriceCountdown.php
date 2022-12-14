<?php
namespace Rokanthemes\PriceCountdown\Block\Widget;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\DataObject\IdentityInterface;
 
class PriceCountdown extends \Magento\Catalog\Block\Product\AbstractProduct  implements \Magento\Widget\Block\BlockInterface
{
    protected $_template = 'Rokanthemes_PriceCountdown::pricecountdown.phtml';
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
    protected $productCollectionFactory;
    protected $storeManager;
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
        $this->productCollectionFactory = $productCollectionFactory;
		$this->_productImageHelper = $productImageHelper;
        $this->storeManager = $context->getStoreManager();
        $this->catalogConfig = $context->getCatalogConfig();
        $this->productVisibility = $productVisibility;
        parent::__construct(
            $context,
            $data
        );
    }
	public function getProducts()
    {
		$storeId    = $this->storeManager->getStore()->getId();
		$products = $this->productCollectionFactory->create()->setStoreId($storeId);
		$todayDate= date('Y-m-d', time());
		$products
		->addAttributeToSelect($this->catalogConfig->getProductAttributes())
				->addMinimalPrice()
				->addFinalPrice()
				->addTaxPercents()
				->addUrlRewrite()
				->setVisibility($this->productVisibility->getVisibleInCatalogIds())
		->addAttributeToFilter('special_to_date', array('date'=>true, 'from'=> $todayDate))
		->addAttributeToFilter('type_id', array('eq' => 'simple'))
		->addAttributeToFilter('special_price',array('lt'=>new \Zend_Db_Expr("price_index.price")));
			$products->setPageSize($this->getConfig('qty'))->setCurPage(1);
		$this->_eventManager->dispatch(
				'catalog_block_product_list_collection',
				['collection' => $products]
			);
		return $products;
    }
	public function getConfig($att) 
	{
		return $this->getData($att);
	}
	
	function cut_string_countdownproduct($string,$number){
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
	
	public function getAddToCartPostParams(\Magento\Catalog\Model\Product $product)
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
	public function resizeImage($product, $imageId, $width, $height = null)
    {
        $resizedImage = $this->_productImageHelper
                           ->init($product, $imageId)
                           ->constrainOnly(TRUE)
                           ->keepAspectRatio(TRUE)
                           ->keepTransparency(TRUE)
                           ->keepFrame(FALSE)
                           ->resize($width,$height);
        return $resizedImage;
    }
}
?>
