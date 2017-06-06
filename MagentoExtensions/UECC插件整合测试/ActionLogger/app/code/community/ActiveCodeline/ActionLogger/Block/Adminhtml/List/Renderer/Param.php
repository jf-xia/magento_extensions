<?php
/**
 * ActiveCodeline_ActionLogger_Block_Adminhtml_List_Renderer_Param
 *
 * @category    ActiveCodeline
 * @package     ActiveCodeline_ActionLogger
 * @author      Branko Ajzele (http://activecodeline.net)
 * @copyright   Copyright (c) Branko Ajzele
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ActiveCodeline_ActionLogger_Block_Adminhtml_List_Renderer_Param extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
    {
        $params = array();
        $p = '';

        if ($_params = $row->getParams()) {
            $params = unserialize(Mage::helper('core')->decrypt($_params));
        }

        $total = count($params); $i = 0;

        foreach ($params as $k => $v) {
            $i++;
            //Simple array to string, no fancy sub-array output
            $p .= $k.': '.((is_array($v) ? implode(' ', $v) : $v)).(($i != $total) ? ', ' : '');
        }

        $params = implode(', ', $params);

        if ($p) {
            return '
                <a href="#" onclick="alert(\''.$p.'\');">'.Mage::helper('activecodeline_actionlogger')->__('Check values').'</a>
            ';
        } else {
            return Mage::helper('activecodeline_actionlogger')->__('N/A');
        }
    }
}