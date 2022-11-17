<?php
namespace ITM\MagB1\Controller\Config;

use Psr\Log\LoggerInterface;
use \Magento\Framework\App\RequestInterface;

class Index extends \Magento\Framework\App\Action\Action
{

    protected $objectManager;


    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager

    ) {
        parent::__construct($context);
        $this->objectManager = $objectManager;
    }


    /**
     * say hello text
     */
    public function execute()
    {

    }
}