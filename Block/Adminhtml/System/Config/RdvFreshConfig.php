<?php
namespace Chronopost\Chronorelais\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class RdvFreshConfig extends Field
{
    protected $_template = 'Chronopost_Chronorelais::system/config/rdvfreshconfig.phtml';

    /**
     * Date constructor.
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }
	
	public function getHtmlId()
    {
        return  'chronofreshsrdv_rdv_config';
    }

    /**
     * Return element html
     *
     * @param  AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $html = parent::_getElementHtml($element);
        $this->addData(
            array(
                'element' => $element
            )
        );
        return $html.$this->_toHtml();
    }

}