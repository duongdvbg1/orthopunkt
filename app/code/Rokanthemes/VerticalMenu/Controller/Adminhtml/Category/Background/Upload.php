<?php

namespace Rokanthemes\VerticalMenu\Controller\Adminhtml\Category\Background;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Upload extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
	
	protected $imageUploader;

    
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Rokanthemes\VerticalMenu\Model\ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }
	
	public function execute()
    {
        $imageId = $this->_request->getParam('param_name', 'vertcial_menu_bg_img');

        try {
            $result = $this->imageUploader->saveFileToTmpDir($imageId);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
} 