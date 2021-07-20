<?php
namespace Chronopost\Chronorelais\Controller\Adminhtml\Sales\Storelocator;
use Magento\Backend\App\Action;
use Chronopost\Chronorelais\Model\ChronordvStorelocator as chronordvStorelocator;

class Edit extends \Magento\Backend\App\Action
{
	
	    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;
 
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;
 
    /**
     * @var \Maxime\Jobs\Model\Department
     */
    protected $_model;
 
    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Maxime\Jobs\Model\Department $model
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Chronopost\Chronorelais\Model\ChronordvStorelocator $Chronordv
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->_model = $Chronordv;
        parent::__construct($context);
    }
	
    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function _initAction()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Chronopost_Chronorelais::sales');
        $resultPage->getConfig()->getTitle()->prepend(__('Edit Storelocator'));

        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Chronopost_Chronorelais::sales');
    }
  
    /**
     * Edit Department
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_model;
		 
        // If you have got an id, it's edition
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This storelocator not exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
 
                return $resultRedirect->setPath('chronorelais/sales/storelocator');
            }
        }
 
        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
 
        $this->_coreRegistry->register('storelocator', $model);
 
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
 
        // $resultPage->getConfig()->getTitle()->prepend(__('Departments'));
        // $resultPage->getConfig()->getTitle()
            // ->prepend($model->getId() ? $model->getName() : __('New Department'));
 
        return $resultPage;
    }
}