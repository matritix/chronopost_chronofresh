<?php
namespace Chronopost\Chronorelais\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class addQuotasShipping implements ObserverInterface
{
	public function __construct(  
        \Magento\Backend\App\Action\Context $context,   
		\Psr\Log\LoggerInterface $logger,
		\Chronopost\Chronorelais\Model\ChronordvFactory $Chronordv,
		\Chronopost\Chronorelais\Model\ResourceModel\Chronordv\CollectionFactory $Chronordvcollec,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
	){ 
		$this->scopeConfig = $scopeConfig;
		$this->_logger = $logger;	
		$this->chronordv = $Chronordv;
		$this->chronordvcollec = $Chronordvcollec; 
	}

	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		// checkout_onepage_controller_success_action
		$order = $observer->getOrder();
		 $this->_logger->debug("coucou"); 
		 $this->_logger->debug($order->getData('chronopostsrdv_creneaux_info')); 
		$chronopostsrdv_creneaux_info = $order->getData('chronopostsrdv_creneaux_info');
		if($chronopostsrdv_creneaux_info) {
			$chronopostsrdv_creneaux_info = json_decode($chronopostsrdv_creneaux_info,true);
			$_dateRdvStart = new \DateTime($chronopostsrdv_creneaux_info['deliveryDate']);
			$_dateRdvStart = $_dateRdvStart->format("Y-m-d 00:00:00");
					 $this->_logger->debug($_dateRdvStart); 
			$collectioncheck = $this->chronordvcollec->create();
			$collectioncheck->addFieldToFilter('created_at',array("eq"=>$_dateRdvStart));
			if($collectioncheck->getSize()){
				$infoCollFirstItem = $collectioncheck->getFirstItem();
				$infoCollFirstItemData =  $infoCollFirstItem->getId();
				$quotasactuel = $infoCollFirstItem->getQuotasactuel(); 
				$chronordv = $this->chronordv->create();
				$chronordv = $chronordv->load($infoCollFirstItem->getId()); 
				$quotasactuel = $quotasactuel+1;
				$chronordv->setQuotasactuel($quotasactuel); 
				
					$timestamp = strtotime($_dateRdvStart);
					$day = date('D', $timestamp); 
					//si le quota actuel est atteint selon le jour
					switch ($day) {
						case "Tue":
							$quotas = $this->scopeConfig->getValue('chronorelais/quotalivraison/quotasmardi', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
							break;
						case "Wed":
							$quotas = $this->scopeConfig->getValue('chronorelais/quotalivraison/quotasmercredi', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
							break;
						case "Thu":
							$quotas = $this->scopeConfig->getValue('chronorelais/quotalivraison/quotasjeudi', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
							break;		 
						case "Fri":
							$quotas = $this->scopeConfig->getValue('chronorelais/quotalivraison/quotasvendredi', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
							break; 
						case "Sat":
							$quotas = $this->scopeConfig->getValue('chronorelais/quotalivraison/quotassamedi', \Magento\Store\Model\ScopeInterface::SCOPE_STORE); 
							break;
						default:
						   $quotas = $this->scopeConfig->getValue('chronorelais/quotalivraison/quotas', \Magento\Store\Model\ScopeInterface::SCOPE_STORE); 
					}
					if(!$quotas || !is_numeric($quotas)){
						$quotas = $this->scopeConfig->getValue('chronorelais/quotalivraison/quotas', \Magento\Store\Model\ScopeInterface::SCOPE_STORE); 
					}
				 
					if($quotasactuel == $quotas || $quotasactuel > $quotas){  
						$chronordv->setQuotaslimit(1);
					} 				
			
			}
			else{
				$chronordv = $this->chronordv->create();
				$chronordv->setQuotasactuel(1);
				$chronordv->setCreatedAt($_dateRdvStart);  
			}
			$chronordv->save(); 
		} 
		
		return $this;
	}
}