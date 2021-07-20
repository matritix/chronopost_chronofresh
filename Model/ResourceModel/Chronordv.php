<?php
namespace Chronopost\Chronorelais\Model\ResourceModel;;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class OrderStatusModel
 * @package Chronopost\Chronorelais\Model\ResourceModel
 */
class Chronordv extends AbstractDb
{

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        // Table Name and Primary Key column
        $this->_init('ml_chronordv', 'id');
    }

}