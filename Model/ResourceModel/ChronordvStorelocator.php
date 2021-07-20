<?php
namespace Chronopost\Chronorelais\Model\ResourceModel;;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class ChronordvStorelocator
 * @package Chronopost\Chronorelais\Model\ResourceModel
 */
class ChronordvStorelocator extends AbstractDb
{

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        // Table Name and Primary Key column
        $this->_init('ml_chronordv_relaislocator', 'id');
    }

}