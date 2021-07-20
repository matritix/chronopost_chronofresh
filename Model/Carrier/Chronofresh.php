<?php
namespace Chronopost\Chronorelais\Model\Carrier;

class Chronofresh extends AbstractChronopost
{
    /**
     * @var string
     */
    protected $_code = 'chronofresh';

    const PRODUCT_CODE = '2R';
    const PRODUCT_CODE_STR = '13H';

    /* option boite au lettre disponible pour ce mode */
    const OPTION_BAL_ENABLE = true;
    const PRODUCT_CODE_BAL = '58';
    const PRODUCT_CODE_BAL_STR = '13H BAL';

    /* autoriser la livraison le samedi */
    const DELIVER_ON_SATURDAY = true;
}