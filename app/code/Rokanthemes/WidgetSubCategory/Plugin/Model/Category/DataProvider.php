<?php
 
namespace Rokanthemes\WidgetSubCategory\Plugin\Model\Category;
 
class DataProvider
{    
    protected $_storeManager;
    protected $_imageUploader;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Rokanthemes\WidgetSubCategory\Model\ImageUploader $imageUploader
    ) {
        $this->_storeManager = $storeManager;
        $this->_imageUploader = $imageUploader;
    }

    public function afterGetData(\Magento\Catalog\Model\Category\DataProvider $subject, $result)
    {    
        $process_result = $result;
        $catagory = $subject->getCurrentCategory();
        $thumbnail = $catagory->getThumbnail();
        if(is_array($process_result) && !empty($process_result)){
            foreach($process_result as $key_p_r => $val_p_r){
                if($thumbnail != ''){
                    $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA );
                    $convert_name[0]['name'] = $thumbnail;
                    $convert_name[0]['url'] = $mediaUrl.$this->_imageUploader->getBasePath().'/'.$thumbnail;
                    $convert_name[0]['is_saved'] = 1;
                    $process_result[$key_p_r]['thumbnail'] = $convert_name;
                }
                break;
            }
        }
        return $process_result;
    }    
}
?>