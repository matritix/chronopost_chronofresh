<?php
 
namespace Chronopost\Chronorelais\Model\Config\Source;
 
class Thedate extends \Magento\Config\Block\System\Config\Form\Field
{
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->setDateFormat(\Magento\Framework\Stdlib\DateTime::DATE_INTERNAL_FORMAT);
        // $elementâ†’setTimeFormat("HH:mm:ss");  
        return parent::render($element);
    }
}