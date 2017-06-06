<?php
class EM_Megamenupro_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/megamenupro?id=15 
    	 *  or
    	 * http://site.com/megamenupro/id/15 	
    	 */
    	/* 
		$megamenupro_id = $this->getRequest()->getParam('id');

  		if($megamenupro_id != null && $megamenupro_id != '')	{
			$megamenupro = Mage::getModel('megamenupro/megamenupro')->load($megamenupro_id)->getData();
		} else {
			$megamenupro = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($megamenupro == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$megamenuproTable = $resource->getTableName('megamenupro');
			
			$select = $read->select()
			   ->from($megamenuproTable,array('megamenupro_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$megamenupro = $read->fetchRow($select);
		}
		Mage::register('megamenupro', $megamenupro);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}