<?php
namespace Rokanthemes\Superdeals\Plugin\Widget\Model;
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
		
		if(key_exists("background_image", $params)) {
            $url = $params["background_image"];
            if(strpos($url,$media_Url) !== false) {
                $params["background_image"] = str_replace($media_Url,"",$url);
            }
        }
		
		if(key_exists("deal_image", $params)) {
            $url = $params["deal_image"];
            if(strpos($url,$media_Url) !== false) {
                $params["deal_image"] = str_replace($media_Url,"",$url);
            }
        }

        return array($type, $params, $asIs);
    }
}
