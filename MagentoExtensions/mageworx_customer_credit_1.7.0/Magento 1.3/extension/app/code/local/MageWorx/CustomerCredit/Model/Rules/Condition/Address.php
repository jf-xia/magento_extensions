<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@mageworx.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 * or send an email to sales@mageworx.com
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2010 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */

class MageWorx_CustomerCredit_Model_Rules_Condition_Address extends Mage_Rule_Model_Condition_Abstract
{
    public function loadAttributeOptions()
    {
        $attributes = array(
            'total_amount' => Mage::helper('salesrule')->__('Purchased amount'),
            'registration' => Mage::helper('salesrule')->__('Registration Date'),
        );

        $this->setAttributeOption($attributes);

        return $this;
    }

    public function loadOperatorOptions()
    {
        $this->setOperatorOption(array(
            '=='  => Mage::helper('rule')->__('is'),
            '!='  => Mage::helper('rule')->__('is not'),
            '>='  => Mage::helper('rule')->__('equals or greater than'),
            '<='  => Mage::helper('rule')->__('equals or less than'),
            '>'   => Mage::helper('rule')->__('greater than'),
            '<'   => Mage::helper('rule')->__('less than'),
            '{}'  => Mage::helper('rule')->__('contains'),
            '!{}' => Mage::helper('rule')->__('does not contain'),
            '()'  => Mage::helper('rule')->__('is one of'),
            '!()' => Mage::helper('rule')->__('is not one of'),
        ));
        $this->setOperatorByInputType(array(
            'string' => array('==', '!=', '>=', '>', '<=', '<', '{}', '!{}', '()', '!()'),
            'numeric' => array('==', '!=', '>=', '>', '<=', '<'),
            'date' => array('==', '>=', '<='),
            'select' => array('==', '!='),
            'multiselect' => array('==', '!=', '{}', '!{}'),
            'grid' => array('()', '!()'),
        ));
        return $this;
    }

    public function getAttributeObject()
    {
        try {
            $obj = Mage::getSingleton('eav/config')
                ->getAttribute('catalog_product', $this->getAttribute());
        }
        catch (Exception $e) {
            $obj = new Varien_Object();
            $obj->setEntity(Mage::getResourceSingleton('catalog/product'))
                ->setFrontendInput('text');
        }
        return $obj;
    }

    public function getValueElement()
    {
        $element = parent::getValueElement();
        switch ($this->getInputType()) {
            case 'date':
                $element->setImage(Mage::getDesign()->getSkinUrl('images/grid-cal.gif'));
                break;
        }

        return $element;
    }

    public function getExplicitApply()
    {
        switch ($this->getInputType()) {
            case 'date':
                return true;
        }
        return false;
    }

//    public function getDefaultOperatorInputByType()
//    {
//        if (null === $this->_defaultOperatorInputByType) {
//            $this->_defaultOperatorInputByType = array(
//                'string'      => array('==', '!=', '>=', '>', '<=', '<', '{}', '!{}', '()', '!()'),
//                'numeric'     => array('==', '!=', '>=', '>', '<=', '<'),
//                'date'        => array('==', '>=', '<='),
//                'select'      => array('==', '!='),
//                'multiselect' => array('==', '!=', '{}', '!{}'),
//                'grid'        => array('()', '!()'),
//            );
//        }
//        return $this->_defaultOperatorInputByType;
//    }

    public function getAttributeElement()
    {
        $element = parent::getAttributeElement();
        $element->setShowAsText(true);
        return $element;
    }

    public function asHtml()
    {
        $html = $this->getTypeElementHtml()
           .$this->getAttributeElementHtml()
           .$this->getOperatorElementHtml()
           .$this->getValueElementHtml()
           .$this->getRemoveLinkHtml()
           .$this->getChooserContainerHtml();
        return $html;
    }

    public function getInputType()
    {
        switch ($this->getAttribute()) {
            case 'total_amount':
                return 'numeric';

            case 'registration':
                return 'date';
        }
        return 'string';
    }

    public function getValueElementType()
    {
        switch ($this->getAttribute()) {
            case 'registration':
                return 'date';
        }
        return 'text';
    }

    public function getOperatorSelectOptions()
    {
        $type = $this->getInputType();
        $opt = array();
        if($type == 'date'){
    		$opt[] = array('value'=>'==', 'label'=> Mage::helper('customercredit')->__('is'));
    		$opt[] = array('value'=>'<=', 'label'=> Mage::helper('customercredit')->__('is or earlier than'));
    		$opt[] = array('value'=>'>=', 'label'=> Mage::helper('customercredit')->__('is or later than'));
    	} else {
    		$operatorByType = $this->getOperatorByInputType();
	        foreach ($this->getOperatorOption() as $k=>$v) {
	            if (!$operatorByType || in_array($k, $operatorByType[$type])) {
            		$opt[] = array('value'=>$k, 'label'=>$v);
            	}
            }
        }
        return $opt;
    }

    public function getOperatorElement()
    {
        if (is_null($this->getOperator())) {
            foreach ($this->getOperatorOption() as $k=>$v) {
                $this->setOperator($k);
                break;
            }
        }

        $operatorName = $this->getOperatorName();

        if($this->getInputType() == 'date'){
            switch ($this->getOperator()){
                case '<=':
                    $operatorName = 'is or earlier than'; break;
                case '>=':
                    $operatorName = 'is or later than'; break;
            }
        }

        return $this->getForm()->addField($this->getPrefix().'__'.$this->getId().'__operator', 'select', array(
            'name'=>'rule['.$this->getPrefix().']['.$this->getId().'][operator]',
            'values'=>$this->getOperatorSelectOptions(),
            'value'=>$this->getOperator(),
            'value_name'=>$operatorName,
        ))->setRenderer(Mage::getBlockSingleton('rule/editable'));
    }

    /**
     * Validate Address Rule Condition
     *
     * @param Varien_Object $object
     * @return bool
     */
    public function validate(Varien_Object $object)
    {
        $address = $object;
        if (!$address instanceof Mage_Sales_Model_Quote_Address) {
            if ($object->getQuote()->isVirtual()) {
                $address = $object->getQuote()->getBillingAddress();
            }
            else {
                $address = $object->getQuote()->getShippingAddress();
            }
        }
        return parent::validate($address);
    }
}
