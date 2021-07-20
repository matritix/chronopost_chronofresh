<?php
namespace Chronopost\Chronorelais\Ui\Component\Listing\Column;

use Magento\Ui\DataProvider\AbstractDataProvider; 

/**
 * Class ProductDataProvider
 */
class StorelocatorDataProvider extends AbstractDataProvider
{
    /**
     * foobar collection
     *
     * @var \[Namespace]\[Module]\Model\FooBar\Collection
     */
    protected $collection;

    /**
     * @var \Magento\Ui\DataProvider\AddFieldToCollectionInterface[]
     */
    protected $addFieldStrategies;

    /**
     * @var \Magento\Ui\DataProvider\AddFilterToCollectionInterface[]
     */
    protected $addFilterStrategies;

    /**
     * Construct
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Chronopost\Chronorelais\Model\ResourceModel\ChronordvStorelocator\CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }

    /**
     * Get collection
     *
     * @return \[Namespace]\[Module]\Model\FooBar\Collection
     */
    public function getCollection()
    {
        return $this->collection;

    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (!$this->getCollection()->isLoaded()) {
            $this->getCollection()->load();
        }
        return $this->getCollection()->toArray();
    }
}