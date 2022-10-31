<?php
namespace Rokanthemes\WidgetSubCategory\Plugin\Widget\Model;
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
		
		$media_Url = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		
		if(key_exists("image_background", $params)) {
            $url = $params["image_background"];
            if(strpos($url,$media_Url) !== false) {
                $params["image_background"] = str_replace($media_Url,"",$url);
            }
        }

        return array($type, $params, $asIs);
    }
}
