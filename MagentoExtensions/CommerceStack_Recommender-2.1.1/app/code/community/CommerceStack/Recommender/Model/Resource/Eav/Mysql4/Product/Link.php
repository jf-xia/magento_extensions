<?php

class CommerceStack_Recommender_Model_Resource_Eav_Mysql4_Product_Link extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Link
{
    public function saveByRef(Mage_Core_Model_Abstract &$object) // Pass by reference to save memory
    {
        $xml = $object->toXml();
        
        $zendConnection = Mage::getSingleton('core/resource')->getConnection('core_write');
        
        // Bypass Magento/Zend connection to save memory on prepare statement
        $connection = $zendConnection->getConnection();
        
        // We typically add entities in bulk so we achieve efficiency gains by manually creating
    	// the SQL here instead of using Varien's slower ORM classes.
    	$columnSql = "INSERT INTO " . Mage::getSingleton('core/resource')->getTableName('recommender_product_link') . " (link_type_id";
    	$valuesSql = ") VALUES (";
    	$updateSql = " ON DUPLICATE KEY UPDATE ";
    	$params = array();
    	$columnSqlComplete = false;
    	$firstOrder = true;
    	$i = 0;
    	
    	foreach($xml->{$object->getRootName()} as $entity)
    	{
    		$firstColumn = true;
    		if(!$firstOrder)
    		{
    			$valuesSql .= ", (";
    		}
    		
    		foreach($entity->children() as $child) 
        	{
        	    if(!$columnSqlComplete)
        		{	
            		// Build INSERT INTO and ON DUPLICATE KEY UPDATE once
            	    if(!$firstColumn) 
        			{
        				$updateSql .= ", ";
        			}	
        			
        			$columnSql .= ", {$child->getName()}";
        			$updateSql .= "{$child->getName()} = VALUES({$child->getName()})";
        		}
        		
        		// Build VALUES
        		if($firstColumn) 
    			{
    				$valuesSql .= $object->getLinkType();
    			}
        			
    			$valuesSql .= ', :' . $child->getName() . $i;
    			$params[':' . $child->getName() . $i] = (string)$child;
    			
        		$firstColumn = false;
      		}
      		
      		$columnSqlComplete = true;
      		$firstOrder = false;
      		$valuesSql .= ")";
      		$i++; 
    	}
        
    	unset($object); // No longer need the object and its potentially large SimpleXml object
        
    	$sql = $columnSql . $valuesSql . $updateSql;

        try 
    	{
    	    $stmt = $connection->prepare($sql);
    	    $result = $stmt->execute($params);
    	}
    	catch(Exception $e)
    	{
    	    throw new Exception($e->getMessage() . ": $sql", $e->getCode(), $e);
    	}
    }
}