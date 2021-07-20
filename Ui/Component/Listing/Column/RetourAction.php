<?php
/**
 * Chronopost
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category  Chronopost
 * @package   Chronopost_Chronorelais
 * @copyright Copyright (c) 2021 Chronopost
 */
declare(strict_types=1);

namespace Chronopost\Chronorelais\Ui\Component\Listing\Column;

use Chronopost\Chronorelais\Helper\Data as HelperData;
use Chronopost\Chronorelais\Helper\Shipment;
use Chronopost\Chronorelais\Model\Config\Source\Retour;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Sales\Model\OrderFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class RetourAction
 *
 * @package Chronopost\Chronorelais\Ui\Component\Listing\Column
 */
class RetourAction extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var OrderFactory
     */
    protected $_orderFactory;

    /**
     * @var Retour
     */
    protected $_retourSource;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var Shipment
     */
    private $helperShipment;

    /**
     * RetourAction constructor.
     *
     * @param ContextInterface     $context
     * @param UiComponentFactory   $uiComponentFactory
     * @param UrlInterface         $urlBuilder
     * @param OrderFactory         $orderFactory
     * @param Retour               $retour
     * @param ScopeConfigInterface $scope
     * @param Shipment             $helperShipment
     * @param array                $components
     * @param array                $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        OrderFactory $orderFactory,
        Retour $retour,
        ScopeConfigInterface $scope,
        Shipment $helperShipment,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->_orderFactory = $orderFactory;
        $this->_retourSource = $retour;
        $this->_scopeConfig = $scope;
        $this->helperShipment = $helperShipment;
        $data['config']['label'] = $this->getLabelWithDropdown($data['config']['label']);
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Get label with dropdown
     *
     * @param string $label
     *
     * @return string
     */
    protected function getLabelWithDropdown(string $label)
    {
        $defaultAddress = $this->_scopeConfig->getValue('chronorelais/retour/defaultadress');
        $select = "<br /><select id='etiquette_retour_adresse' style='font-size: 12px;' name='etiquette_retour_adresse'>";

        $options = $this->_retourSource->toOptionArray();
        foreach ($options as $value => $option) {
            $selected = ($defaultAddress && $value == $defaultAddress) ? ' selected="selected"' : '';
            $select .= "<option value='" . $value . "'" . $selected . ">" . $option . "</option>";
        }

        $select .= "</select><input type='hidden' id='etiquette_retour_adresse_value' " .
            "name='etiquette_retour_adresse_value' value='" . $defaultAddress . "'/>";

        return $label . $select;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     *
     * @return array
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$this->getData('name')] = '';

                $shippingMethod = explode('_', $item['shipping_method']);
                $shippingMethod = isset($shippingMethod[1]) ? $shippingMethod[1] : $shippingMethod[0];

                $shippingMethodsAllowed = HelperData::SHIPPING_METHODS_RETURN_ALLOWED;
                if (!in_array($shippingMethod, $shippingMethodsAllowed)) {
                    $item[$this->getData('name')] = __('Returns are not available for this shipping method');
                    continue;
                }

                // If no shipment and tracking, no return possible
                if (!isset($item['shipment_id']) || empty($item['shipment_id']) || $item['shipment_id'] == '--'
                    || !isset($item['track_number']) || empty($item['track_number']) || $item['track_number'] == '--') {
                    continue;
                }

                $viewUrlPath = $this->getData('config/viewUrlPath') ?: 'javascript:void(0);';
                $deleteUrlPath = $this->getData('config/deleteUrlPath') ?: '#';
                $viewGeneratedUrlPath = $this->getData('config/viewGeneratedUrlPath') ?: 'javascript:void(0);';

                $shipmentIds = explode(',', $item['shipment_id']);
                foreach ($shipmentIds as $shipmentId) {
                    $id = $this->helperShipment->getShipmentIdFromIncrementId($shipmentId);
                    $shipmentReturns = $this->helperShipment->getReturnsForShipment($id);
                    foreach ($shipmentReturns as $shipmentReturn) {
                        $ltNumber = trim($shipmentReturn->getLtNumber());

                        $urlGenerated = $this->urlBuilder->getUrl(
                            $viewGeneratedUrlPath,
                            [
                                'track_number' => $ltNumber,
                                'shipment_id'  => $shipmentId
                            ]
                        );

                        $deleteUrl = $this->urlBuilder->getUrl(
                            $deleteUrlPath,
                            [
                                'order_id'     => (string)$item['entity_id'],
                                'track_number' => $ltNumber,
                                'shipment_id'  => $shipmentId
                            ]
                        );

                        $confirmMsg = __('Are you sure you want to cancel this return label?');

                        $item[$this->getData('name')] .= '<a class="printlink" href="' . $urlGenerated . '">' . $ltNumber . '</a>';
                        $item[$this->getData('name')] .= '<a onclick="return confirm(\'' . $confirmMsg . '\');" class="printlink" href="' . $deleteUrl . '"> ' . __('(Cancel)') . '</a><br />';
                    }

                    $printLabel = __('Printing return labels');
                    if (count($shipmentReturns) >= 1) {
                        $printLabel = __('Print a new return label');
                    }

                    $defaultAddress = $this->_scopeConfig->getValue('chronorelais/retour/defaultadress');
                    $url = $this->urlBuilder->getUrl($viewUrlPath, ['shipment_increment_id' => $shipmentId]);
                    if (count($shipmentIds) === 1) {
                        $item[$this->getData('name')] .= '<a href="' . $url . '?recipient_address_type=' . $defaultAddress . '"
                                class="etiquette_retour_link">' . $printLabel . '</a><br />';
                    } else {
                        $item[$this->getData('name')] .= '<a href="' . $url . '?recipient_address_type=' . $defaultAddress . '"
                                class="etiquette_retour_link">' . $printLabel . ' ' . $shipmentIds
                            . '</a><br />';
                    }
                }
            }
        }

        return $dataSource;
    }
}
