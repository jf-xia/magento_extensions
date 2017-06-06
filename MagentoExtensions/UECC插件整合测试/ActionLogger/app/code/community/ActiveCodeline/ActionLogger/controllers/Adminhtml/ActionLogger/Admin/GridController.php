<?php
/**
 * ActiveCodeline_ActionLogger_Adminhtml_Admin_GridController
 *
 * @category    ActiveCodeline
 * @package     ActiveCodeline_ActionLogger
 * @author      Branko Ajzele (http://activecodeline.net)
 * @copyright   Copyright (c) Branko Ajzele
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ActiveCodeline_ActionLogger_Adminhtml_ActionLogger_Admin_GridController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('activecodeline_actionlogger/adminhtml_list_admin_grid'));
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('activecodeline_actionlogger/adminhtml_list_admin_grid')->toHtml());
    }
}
