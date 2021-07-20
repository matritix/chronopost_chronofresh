<?php
namespace Chronopost\Chronorelais\Controller\Adminhtml\Sales\Storelocator;
use Magento\Backend\App\Action;  

class Save extends Action
{
    /**
     * @var \Maxime\Jobs\Model\Department
     */
    protected $_model;
 
    /**
     * @param Action\Context $context
     * @param \Maxime\Jobs\Model\Department $model
     */
    public function __construct(
        Action\Context $context,
        \Chronopost\Chronorelais\Model\ChronordvStorelocator $model
    ) {
        parent::__construct($context);
        $this->_model = $model;
    }
 

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Chronopost_Chronorelais::sales');
    }
 
    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
		// exit();
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            /** @var \Maxime\Jobs\Model\storelocator $model */
            $model = $this->_model;
 
            $data = $this->getRequest()->getParam('storelocator');
 
            if ($data["id"]) {
                $model->load($data["id"]);
            }
// var_dump($model->getData());

 // e
            $model->setData($data);
 
            $this->_eventManager->dispatch(
                'chronopost_storelocator_prepare_save',
                ['storelocator' => $model, 'request' => $this->getRequest()]
            );
 
            try {
				// var_dump($data);
                $model->save();
				// exit();
                $this->messageManager->addSuccess(__('storelocator saved'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('chronorelais/sales/storelocator_edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('chronorelais/sales/storelocator');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the storelocator'));
            }
 
            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('chronorelais/sales/storelocator', ['entity_id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('chronorelais/sales/storelocator');
    }
}