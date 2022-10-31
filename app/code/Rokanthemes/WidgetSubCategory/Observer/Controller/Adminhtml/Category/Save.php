<?php
namespace Rokanthemes\WidgetSubCategory\Observer\Controller\Adminhtml\Category;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Save implements ObserverInterface {
	
	protected $_imageUploader;

	public function __construct(
        \Rokanthemes\WidgetSubCategory\Model\ImageUploader $imageUploader
    ) {
    	$this->_imageUploader = $imageUploader;
    }

	public function execute(Observer $observer) {
		$var_request = $observer->getRequest();
		$category_mode = $observer->getCategory();
		$data_post = $var_request->getPostValue();
		$thumbnail = '';
		
		if(isset($data_post['thumbnail']) && is_array($data_post['thumbnail'])){
			if(isset($data_post['thumbnail'][0]['name'])){
				if(isset($data_post['thumbnail'][0]['is_saved'])){
					$thumbnail = $data_post['thumbnail'][0]['name'];
				}
				else{
					$thumbnail = $this->_imageUploader->moveFileFromTmp($data_post['thumbnail'][0]['name']);
				}
			}
        }
		
        $category_mode->setThumbnail($thumbnail);
	}
}