<?php

namespace Rokanthemes\WidgetSubCategory\Controller\Adminhtml\Category\Thumbnail;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Upload extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    const CATEGORY_ATTRIBUTE_IMAGE = 'thumbnail';
    protected $imageUploader;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Rokanthemes\WidgetSubCategory\Model\ImageUploader $imageUploader 
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }
    
    public function execute()
    {
        $imageId = $this->_request->getParam('param_name', 'thumbnail');

        try {
            $result = $this->imageUploader->saveFileToTmpDir($imageId);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
