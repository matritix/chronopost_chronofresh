<?php
namespace Chronopost\Chronorelais\Controller\Adminhtml\Sales\Storelocator;
use Magento\Backend\App\Action;
use Chronopost\Chronorelais\Model\ChronordvStorelocator as chronordvStorelocator;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Add extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Chronopost_Chronorelais::sales');
        $resultPage->getConfig()->getTitle()->prepend(__('New Storelocator'));

        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Chronopost_Chronorelais::sales');
    }
} 
