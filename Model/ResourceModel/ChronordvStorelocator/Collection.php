<?php
namespace Chronopost\Chronorelais\Model\ResourceModel\ChronordvStorelocator;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    protected $_idFieldName = 'id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Chronopost\Chronorelais\Model\ChronordvStorelocator', 'Chronopost\Chronorelais\Model\ResourceModel\ChronordvStorelocator');
    }

}