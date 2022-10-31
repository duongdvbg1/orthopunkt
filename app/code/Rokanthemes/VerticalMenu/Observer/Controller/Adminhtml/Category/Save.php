<?php
namespace Rokanthemes\VerticalMenu\Observer\Controller\Adminhtml\Category;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Save implements ObserverInterface {
	
	protected $_imageUploader;

	public function __construct(
        \Rokanthemes\VerticalMenu\Model\ImageUploader $imageUploader
    ) {
    	$this->_imageUploader = $imageUploader;
    }

	public function execute(Observer $observer) {
		$var_request = $observer->getRequest();
		$category_mode = $observer->getCategory();
		$data_post = $var_request->getPostValue();
		$vc_menu_icon_img = '';
		$vertcial_menu_bg_img = '';
		
		if(isset($data_post['vertcial_menu_bg_img']) && is_array($data_post['vertcial_menu_bg_img'])){
			if(isset($data_post['vertcial_menu_bg_img'][0]['name'])){
				if(isset($data_post['vertcial_menu_bg_img'][0]['is_saved'])){
					$vertcial_menu_bg_img = $data_post['vertcial_menu_bg_img'][0]['name'];
				}
				else{
					$vertcial_menu_bg_img = $this->_imageUploader->moveFileFromTmp($data_post['vertcial_menu_bg_img'][0]['name']);
				}
			}
        }
		
		$category_mode->setVertcialMenuBgImg($vertcial_menu_bg_img);
		
		if(isset($data_post['vc_menu_icon_img']) && is_array($data_post['vc_menu_icon_img'])){
			if(isset($data_post['vc_menu_icon_img'][0]['name'])){
				if(isset($data_post['vc_menu_icon_img'][0]['is_saved'])){
					$vc_menu_icon_img = $data_post['vc_menu_icon_img'][0]['name'];
				}
				else{
					$vc_menu_icon_img = $this->_imageUploader->moveFileFromTmp($data_post['vc_menu_icon_img'][0]['name']);
				}
			}
        }
		
		$category_mode->setVcMenuIconImg($vc_menu_icon_img);
	}
}