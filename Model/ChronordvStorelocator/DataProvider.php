<?php
namespace Chronopost\Chronorelais\Model\ChronordvStorelocator;

use Chronopost\Chronorelais\Model\ResourceModel\ChronordvStorelocator\CollectionFactory;
use Chronopost\Chronorelais\Model\ChronordvStorelocator;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $contactCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $contactCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $contactCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        $this->loadedData = array();
        /** @var Contact $contact */
        foreach ($items as $contact) {
 
            $this->loadedData[$contact->getId()]['storelocator'] = $contact->getData();
        }

        return $this->loadedData;

    }
	
	

 /**
     * @inheritdoc
     */
    // public function addFilter($condition, $field = null, $type = 'regular')
    // {
        // $this->filterPool->registerNewFilter($condition, $field, $type);
    // }

    /**
     * Get data
     *
     * @return array
     */
    // public function getData()
    // {
        // $this->filterPool->applyFilters($this->collection);
        // return $this->collection->toArray();
    // }

    /**
     * Retrieve count of loaded items
     *
     * @return int
     */
    // public function count()
    // {
        // $this->filterPool->applyFilters($this->collection);
        // return $this->collection->count();
    // }
    // public function addFieldToSearchFilter($field, $condition = null)
    // {
        // $field = $this->_getMappedField($field);
        // $this->_select->orWhere($this->_getConditionSql($field, $condition));
        // return $this;
    // }
	
}