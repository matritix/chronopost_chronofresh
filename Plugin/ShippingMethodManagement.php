<?php
namespace Chronopost\Chronorelais\Plugin;
 
class ShippingMethodManagement {
 
 protected $logger;
public function __construct(\Psr\Log\LoggerInterface $logger)
{
    $this->logger = $logger;
}


    public function afterEstimateByExtendedAddress($shippingMethodManagement, $output)
    {
 
        return $this->filterOutput($output);
    }
    private function filterOutput($output)
    {
        $free = [];
        foreach ($output as $shippingMethod) {
$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/titidebug.log');
$logger = new \Zend\Log\Logger();
$logger->addWriter($writer);
 
$logger->info('afterEstimateByExtendedAddress : '.print_r($shippingMethod, true)); // Array Log

        }
 
        return $output;
    }
}