<?php
 
namespace Rokanthemes\VerticalMenu\Plugin\Model\Category;
 
class DataProvider
{    
    protected $_storeManager;
    protected $_imageUploader;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Rokanthemes\VerticalMenu\Model\ImageUploader $imageUploader
    ) {
        $this->_storeManager = $storeManager;
        $this->_imageUploader = $imageUploader;
    }

    public function afterGetData(\Magento\Catalog\Model\Category\DataProvider $subject, $result)
    {    
        $process_result = $result;
        $catagory = $subject->getCurrentCategory();
        $vc_menu_icon_img = $catagory->getVcMenuIconImg();
		$vertcial_menu_bg_img = $catagory->getVertcialMenuBgImg();

        if(is_array($process_result) && !empty($process_result)){
            foreach($process_result as $key_p_r => $val_p_r){
                if($vc_menu_icon_img != ''){
                    $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA );
                    $convert_name[0]['name'] = $vc_menu_icon_img;
                    $convert_name[0]['url'] = $mediaUrl.$this->_imageUploader->getBasePath().'/'.$vc_menu_icon_img;
                    $convert_name[0]['is_saved'] = 1;
                    $process_result[$key_p_r]['vc_menu_icon_img'] = $convert_name;
                }
                break;
            }
			
			foreach($process_result as $key_p_r => $val_p_r){
                if($vertcial_menu_bg_img != ''){
                    $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA );
                    $convert_name[0]['name'] = $vertcial_menu_bg_img;
                    $convert_name[0]['url'] = $mediaUrl.$this->_imageUploader->getBasePath().'/'.$vertcial_menu_bg_img;
                    $convert_name[0]['is_saved'] = 1;
                    $process_result[$key_p_r]['vertcial_menu_bg_img'] = $convert_name;
                }
                break;
            }
        }

        return $process_result;
    }    
}
?>