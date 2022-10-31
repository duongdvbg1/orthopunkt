<?php
namespace Rokanthemes\ProductBanner\Plugin\Widget\Model;
use \Magento\Widget\Model\Widget as BaseWidget;
class Widget
{
	
	protected $_storeManager;
	
	public function __construct(      
        \Magento\Store\Model\StoreManagerInterface $storeManager 
    )
    {        
        $this->_storeManager = $storeManager;
    }

    public function beforeGetWidgetDeclaration(BaseWidget $subject, $type, $params = [], $asIs = true)
    {
		if(key_exists("banner_image", $params)) {
			$media_Url = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            $url = $params["banner_image"];
            if(strpos($url,$media_Url) !== false) {
                $params["banner_image"] = str_replace($media_Url,"",$url);
            }
        }

        return array($type, $params, $asIs);
    }
}
