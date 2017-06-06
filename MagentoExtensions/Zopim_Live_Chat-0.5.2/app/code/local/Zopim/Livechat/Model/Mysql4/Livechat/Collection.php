<?php
class Zopim_Livechat_Model_Mysql4_Livechat_Collection extends Varien_Data_Collection_Db
{
    protected $_livechatTable;
 
    public function __construct()
    {
        $resources = Mage::getSingleton('core/resource');
        parent::__construct($resources->getConnection('livechat_read'));
        $this->_livechatTable = $resources->getTableName('livechat/livechat');
 
        $this->_select->from(
        		array('livechat'=>$this->_livechatTable),
 		       	array('*')
        		);
        $this->setItemObjectClass(Mage::getConfig()->getModelClassName('livechat/livechat'));
    }
}
