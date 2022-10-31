<?php
namespace Rokanthemes\CustomMenu\Observer\Controller\Adminhtml\Category;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Save implements ObserverInterface {
	
	protected $_imageUploader;

	public function __construct(
        \Rokanthemes\CustomMenu\Model\ImageUploader $imageUploader
    ) {
    	$this->_imageUploader = $imageUploader;
    }

	public function execute(Observer $observer) {
		$var_request = $observer->getRequest();
		$category_mode = $observer->getCategory();
		$data_post = $var_request->getPostValue();
		$rt_menu_icon_img = '';
		$rt_menu_bg_img = '';
		
		if(isset($data_post['rt_menu_bg_img']) && is_array($data_post['rt_menu_bg_img'])){
			if(isset($data_post['rt_menu_bg_img'][0]['name'])){
				if(isset($data_post['rt_menu_bg_img'][0]['is_saved'])){
					$rt_menu_bg_img = $data_post['rt_menu_bg_img'][0]['name'];
				}
				else{
					$rt_menu_bg_img = $this->_imageUploader->moveFileFromTmp($data_post['rt_menu_bg_img'][0]['name']);
				}
			}
        }
		
		$category_mode->setRtMenuBgImg($rt_menu_bg_img);
		
		if(isset($data_post['rt_menu_icon_img']) && is_array($data_post['rt_menu_icon_img'])){
			if(isset($data_post['rt_menu_icon_img'][0]['name'])){
				if(isset($data_post['rt_menu_icon_img'][0]['is_saved'])){
					$rt_menu_icon_img = $data_post['rt_menu_icon_img'][0]['name'];
				}
				else{
					$rt_menu_icon_img = $this->_imageUploader->moveFileFromTmp($data_post['rt_menu_icon_img'][0]['name']);
				}
			}
        }
		
		$category_mode->setRtMenuIconImg($rt_menu_icon_img);
	}
}