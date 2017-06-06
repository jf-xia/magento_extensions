<?php
/**
 * J2T RewardsPoint2
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@j2t-design.com so we can send you a copy immediately.
 *
 * @category   Magento extension
 * @package    RewardsPoint2
 * @copyright  Copyright (c) 2011 J2T DESIGN. (http://www.j2t-design.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Rewardpoints_Model_Mysql4_Stats_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected $_countAttribute = 'main_table.customer_id';
    //main_table.rewardpoints_account_id
    protected $_allowDisableGrouping = true;
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('rewardpoints/stats');
    }
    
    public function setCountAttribute($value)
    {
        $this->_countAttribute = $value;
        return $this;
    }
    
   
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();

        if ($this->_allowDisableGrouping) {
            $countSelect->reset(Zend_Db_Select::COLUMNS);
            $countSelect->reset(Zend_Db_Select::GROUP);
            $countSelect->columns('COUNT(DISTINCT ' . $this->getCountAttribute() . ')');
        }
        return $countSelect;
    }
    
    public function getCountAttribute()
    {
        return $this->_countAttribute;
    }
    
    
    
    public function addListRestriction()
    {
        $statuses = Mage::getStoreConfig('rewardpoints/default/valid_statuses', Mage::app()->getStore()->getId());
        $status_field = Mage::getStoreConfig('rewardpoints/default/status_used', Mage::app()->getStore()->getId());

        $order_states = explode(",", $statuses);
        
        parent::_initSelect();
        $select = $this->getSelect();
        
        
        $select
            ->from($this->getTable('rewardpoints/rewardpoints_account'),array(new Zend_Db_Expr('SUM('.$this->getTable('rewardpoints/rewardpoints_account').'.points_current) AS all_points_accumulated'),new Zend_Db_Expr('SUM('.$this->getTable('rewardpoints/rewardpoints_account').'.points_spent) AS all_points_spent')))
            ->where($this->getTable('rewardpoints/rewardpoints_account').'.customer_id = e.entity_id');


        if (version_compare(Mage::getVersion(), '1.4.0', '>=')){
            $select->where(" (".$this->getTable('rewardpoints/rewardpoints_account').".order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_REVIEW."' or '".Rewardpoints_Model_Stats::TYPE_POINTS_ADMIN."' or ".$this->getTable('rewardpoints/rewardpoints_account').".order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_REGISTRATION."'
                   or ".$this->getTable('rewardpoints/rewardpoints_account').".order_id in  (SELECT increment_id
                       FROM ".$this->getTable('sales/order')." AS orders
                       WHERE orders.$status_field IN (?))
                 ) ", $order_states);
        } else {
            $table_sales_order = $this->getTable('sales/order').'_varchar';
            $select->where(" (".$this->getTable('rewardpoints/rewardpoints_account').".order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_REVIEW."' or '".Rewardpoints_Model_Stats::TYPE_POINTS_ADMIN."' or ".$this->getTable('rewardpoints/rewardpoints_account').".order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_REGISTRATION."'
                   or ".$this->getTable('rewardpoints/rewardpoints_account').".order_id in (SELECT increment_id
                                       FROM ".$this->getTable('sales/order')." AS orders
                                       WHERE orders.entity_id IN (
                                           SELECT order_state.entity_id
                                           FROM ".$table_sales_order." AS order_state
                                           WHERE order_state.value <> 'canceled'
                                           AND order_state.value in (?))
                                        ) ) ", $order_states);   
        }


        //v.2.0.0
        if (Mage::getStoreConfig('rewardpoints/default/points_delay', Mage::app()->getStore()->getId())){
            $this->getSelect()->where('( NOW() >= '.$this->getTable('rewardpoints/rewardpoints_account').'.date_start OR '.$this->getTable('rewardpoints/rewardpoints_account').'.date_start IS NULL)');
        }
        
        if (Mage::getStoreConfig('rewardpoints/default/points_duration', Mage::app()->getStore()->getId())){
            $select->where('( '.$this->getTable('rewardpoints/rewardpoints_account').'.date_end >= NOW() OR '.$this->getTable('rewardpoints/rewardpoints_account').'.date_end IS NULL)');
        }

        $select->group($this->getTable('rewardpoints/rewardpoints_account').'.customer_id');

        
        return $this;
    }
    
    
    

    public function setPriorityOrder($dir = 'ASC')
    {
        $this->setOrder('main_table.priority', $dir);
        return $this;
    }

    public function addClientFilter($id)
    {
        $this->_countAttribute = 'main_table.rewardpoints_account_id';
        $this->getSelect()->where('customer_id = ?', $id);
        return $this;
    }

    
    public function groupByCustomer()
    {
        //$this->groupByAttribute('customer_id');
        $this->getSelect()->group('main_table.customer_id');
        $this->_allowDisableGrouping = false;

        return $this;
    }
    
    public function addFinishFilter($days)
    {
        //for example, DATEDIFF('1997-12-30','1997-12-25') returns 5
        $this->getSelect()->where('( DATEDIFF(main_table.date_end, NOW()) = ? AND main_table.date_end IS NOT NULL)', $days);
        return $this;
    }
    
    
    public function showCustomerInfo()
    {
        $customer = Mage::getModel('customer/customer');
        /* @var $customer Mage_Customer_Model_Customer */
        $firstname  = $customer->getAttribute('firstname');
        $lastname   = $customer->getAttribute('lastname');

//        $customersCollection = Mage::getModel('customer/customer')->getCollection();
//        /* @var $customersCollection Mage_Customer_Model_Entity_Customer_Collection */
//        $firstname = $customersCollection->getAttribute('firstname');
//        $lastname  = $customersCollection->getAttribute('lastname');

        $this->getSelect()
            ->joinLeft(
                array('customer_lastname_table'=>$lastname->getBackend()->getTable()),
                'customer_lastname_table.entity_id=main_table.customer_id
                 AND customer_lastname_table.attribute_id = '.(int) $lastname->getAttributeId() . '
                 ',
                array('customer_lastname'=>'value')
             )
             ->joinLeft(
                array('customer_firstname_table'=>$firstname->getBackend()->getTable()),
                'customer_firstname_table.entity_id=main_table.customer_id
                 AND customer_firstname_table.attribute_id = '.(int) $firstname->getAttributeId() . '
                 ',
                array('customer_firstname'=>'value')
             );
        
        

        return $this;
    }
    
    
    
    public function joinEavTablesIntoCollection($mainTableForeignKey, $eavType){
 
        
        $entityType = Mage::getModel('eav/entity_type')->loadByCode($eavType);
        $attributes = $entityType->getAttributeCollection();
        $entityTable = $this->getTable($entityType->getEntityTable());
 
        //Use an incremented index to make sure all of the aliases for the eav attribute tables are unique.
        $index = 1;
        
        
        $fields = array();
        foreach (Mage::getConfig()->getFieldset('customer_account') as $code=>$node) {
            if ($node->is('name')) {
                //$this->addAttributeToSelect($code);
                $fields[$code] = $code;
            }
        }
        
        $expr = 'CONCAT('
            .(isset($fields['prefix']) ? 'IF({{prefix}} IS NOT NULL AND {{prefix}} != "", CONCAT({{prefix}}," "), ""),' : '')
            .'{{firstname}}'.(isset($fields['middlename']) ?  ',IF({{middlename}} IS NOT NULL AND {{middlename}} != "", CONCAT(" ",{{middlename}}), "")' : '').'," ",{{lastname}}'
            .(isset($fields['suffix']) ? ',IF({{suffix}} IS NOT NULL AND {{suffix}} != "", CONCAT(" ",{{suffix}}), "")' : '')
        .')';
        
        
        foreach ($attributes->getItems() as $attribute){
            $alias = 'table'.$index;
            if ($attribute->getBackendType() != 'static'){
                $table = $entityTable. '_'.$attribute->getBackendType();
                $field = $alias.'.value';
                $this->getSelect()
                ->joinLeft(array($alias => $table),
                       'main_table.'.$mainTableForeignKey.' = '.$alias.'.entity_id and '.$alias.'.attribute_id = '.$attribute->getAttributeId(),
                array($attribute->getAttributeCode() => $field)
                );
                $expr = str_replace('{{'.$attribute->getAttributeCode().'}}', $field, $expr);
                
            }
            $index++;
        }
        
        
        
        //Join in all of the static attributes by joining the base entity table.
        $this->getSelect()->joinLeft($entityTable, 'main_table.'.$mainTableForeignKey.' = '.$entityTable.'.entity_id');
        
        $this->getSelect()->columns(array('name' => $expr));
        
        
        
        return $this;
    }
    
    
    public function addClientEntries()
    {
        $this->getSelect()->joinLeft(
            array('cust' => $this->getTable('customer/entity')),
            'main_table.customer_id = cust.entity_id'
        );
        
        return $this;
    }
    
    
    /*public function addpointsbydate($store_id, $customer_id, $date){
        $this->getSelect()->where("customer_id = ?", $customer_id);
        
        $this->getSelect()->where("( ? >= main_table.date_start )", $date);
        $this->getSelect()->where("( main_table.date_end >= ? )", $date);
        
        $this->addValidPoints($store_id, true);
        return $this;
    }*/
    
    public function addUsedpointsbydate($store_id, $customer_id){
        $statuses = Mage::getStoreConfig('rewardpoints/default/valid_statuses', $store_id);
        $status_field = Mage::getStoreConfig('rewardpoints/default/status_used', $store_id);
        
        $order_states = explode(",", $statuses);
        
        $cols['points_spent'] = 'SUM(main_table.points_spent) as nb_credit_spent';
        
        
        //selection de tous les points utilisés à x date
        $this->getSelect()->from($this->getResource()->getMainTable().' as child_table', $cols);
        $cols_order['date_order'] = 'DATE_FORMAT(orders.created_at, "%Y-%m-%d") as date_order';
        
        $this->getSelect()->from($this->getTable('sales/order').' as orders', $cols_order);
        
        
        if (version_compare(Mage::getVersion(), '1.4.0', '>=')){
            $this->getSelect()->where(" main_table.customer_id = $customer_id 
                                        AND main_table.points_spent > 0 
                                        AND main_table.order_id > 0 
                                        AND main_table.order_id = orders.increment_id"
                                     );
            
            
            $this->getSelect()->where("orders.customer_id = main_table.customer_id");
            $this->getSelect()->where("orders.$status_field IN (?) OR (orders.$status_field = 'new' AND orders.customer_id = main_table.customer_id)",$order_states);
            
        } else {
            
            //J2T magento 1.3.x fix
            
            $table_sales_order = $this->getTable('sales/order').'_varchar';
            
            $this->getSelect()->where("main_table.customer_id = $customer_id 
                                       AND main_table.points_spent > 0 
                                       AND main_table.order_id > 0
                                       AND main_table.order_id = orders.increment_id
                    
                                       AND main_table.order_id IN  (
                                                                       SELECT increment_id
                                                                       FROM ".$this->getTable('sales/order')." AS orders_checked
                                                                       WHERE orders_checked.entity_id IN (
                                                                                   SELECT order_state.entity_id
                                                                                   FROM ".$table_sales_order." AS order_state
                                                                                   WHERE  order_state.value <> 'canceled'
                                                                                   AND (order_state.value in (?)
                                                                                   OR (order_state.value = 'new'))
                                                                               )
                                                                               AND orders_checked.customer_id = main_table.customer_id
                                                                   )
                                                                
                    
                                       
                                       ", $order_states);
            
        }
        
        $this->getSelect()->where('main_table.rewardpoints_account_id = child_table.rewardpoints_account_id');

        if (Mage::getStoreConfig('rewardpoints/default/store_scope', $store_id)){
            $this->getSelect()->where('find_in_set(?, main_table.store_id)', $store_id);
        }

        
        //$this->getSelect()->group('orders.created_at');
        $this->getSelect()->group('date_order');
        
        
        /*echo $this->getSelect()->__toString();
        die;*/
        
        return $this;
        
    }
    
    public function addValidPoints($store_id, $unset_date_limits = false)
    {
        $statuses = Mage::getStoreConfig('rewardpoints/default/valid_statuses', $store_id);
        $status_field = Mage::getStoreConfig('rewardpoints/default/status_used', $store_id);
        
        $order_states = explode(",", $statuses);
        $order_states_str = "'".implode("',",$order_states)."'";
        

        $cols['points_current'] = 'SUM(main_table.points_current) as nb_credit';
        $cols['points_spent'] = 'SUM(main_table.points_spent) as nb_credit_spent';
        
        $cols['points_available'] = '(SUM(main_table.points_current) - SUM(main_table.points_spent)) as nb_credit_available';
        

        $this->getSelect()->from($this->getResource()->getMainTable().' as child_table', $cols);

        // checking if module rewardshare is available
        $sql_share = "";
        $sql_required = "";
        //J2T magento 1.3.x fix
        if (class_exists('J2t_Rewardshare_Model_Stats', false)){
            $sql_share = "main_table.order_id = '".J2t_Rewardshare_Model_Stats::TYPE_POINTS_SHARE."' or";
        }
        
        if (Mage::getConfig()->getModuleConfig('J2t_Rewardproductvalue')->is('active', 'true')){
            if (version_compare(Mage::getVersion(), '1.4.0', '>=')){
                $sql_required = "(  
                                    main_table.order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_REQUIRED ."'
                                    AND main_table.quote_id IN 
                                        (
                                            SELECT quote_id FROM ".$this->getTable('sales/order')." AS orders_quote
                                            WHERE orders_quote.quote_id = main_table.quote_id
                                            AND orders_quote.customer_id = main_table.customer_id
                                            AND orders_quote.$status_field IN (?,'new')
                                        ) 
                                        
                                 ) or ";
            }
        }
        
        
        if (version_compare(Mage::getVersion(), '1.4.0', '>=')){
            //main_table.order_id = '".J2t_Rewardshare_Model_Stats::TYPE_POINTS_SHARE."' or
            $this->getSelect()->where("($sql_required $sql_share main_table.order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_REVIEW."' 
                    OR main_table.order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_ADMIN."'
                    OR main_table.order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_REGISTRATION."' 
                        
                        OR (main_table.order_id in (
                                    SELECT increment_id FROM ".$this->getTable('sales/order')." AS orders_new 
                                    WHERE orders_new.$status_field = 'new' 
                                    AND orders_new.customer_id = main_table.customer_id
                                ) 
                            AND main_table.points_spent > 0
                            )
                        
                        OR main_table.order_id in (
                        SELECT increment_id FROM ".$this->getTable('sales/order')." AS orders 
                            WHERE (orders.customer_id = main_table.customer_id 
                            OR orders.customer_id IN (
                                    SELECT referral_table.rewardpoints_referral_child_id 
                                    FROM ".$this->getTable('rewardpoints/referral')." AS referral_table 
                                    WHERE main_table.rewardpoints_referral_id = referral_table.rewardpoints_referral_id)) 
                                    AND orders.$status_field IN (?)
                            )
                        )", $order_states);
        } else {
            
            //J2T magento 1.3.x fix
            
            $table_sales_order = $this->getTable('sales/order').'_varchar';
            $this->getSelect()->where(" ($sql_required $sql_share main_table.order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_REVIEW."' 
                            OR main_table.order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_ADMIN."'
                            OR main_table.order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_REGISTRATION."' 

                            OR (
                                main_table.order_id in (
                                       SELECT increment_id
                                       FROM ".$this->getTable('sales/order')." AS orders_new
                                       WHERE orders_new.entity_id IN (
                                               SELECT order_state_new.entity_id
                                               FROM ".$table_sales_order." AS order_state_new
                                               WHERE order_state_new.value in ('new')
                                               AND orders_new.customer_id = main_table.customer_id
                                           )
                                       )
                                AND main_table.points_spent > 0
                               )
                                       
                            OR main_table.order_id in (SELECT increment_id
                                       FROM ".$this->getTable('sales/order')." AS orders
                                       WHERE orders.entity_id IN (
                                           SELECT order_state.entity_id
                                           FROM ".$table_sales_order." AS order_state
                                           WHERE order_state.value <> 'canceled'
                                           AND (order_state.value in (?))
                                           )
                                        ) ) ", $order_states);
            
            /*
             * or (orders.entity_id in (
                                SELECT orders_new.entity_id FROM ".$table_sales_order." AS orders_new 
                                WHERE orders_new.order_state = 'new') 
                                AND main_table.points_spent > 0)
             */
            
        }
        
        
           
        //$this->getSelect()->where('main_table.customer_id IS NOT NULL');
        $this->getSelect()->where('main_table.rewardpoints_account_id = child_table.rewardpoints_account_id');

        if (Mage::getStoreConfig('rewardpoints/default/store_scope', $store_id)){
            $this->getSelect()->where('find_in_set(?, main_table.store_id)', $store_id);
        }

        //v.2.0.0
        if (Mage::getStoreConfig('rewardpoints/default/points_delay', $store_id) && !$unset_date_limits){
            $this->getSelect()->where('( NOW() >= main_table.date_start OR main_table.date_start IS NULL)');
        }

        if (Mage::getStoreConfig('rewardpoints/default/points_duration', $store_id) && !$unset_date_limits){
            $this->getSelect()->where('( main_table.date_end >= NOW() or main_table.date_end IS NULL)');
        }
        $this->getSelect()->group('main_table.customer_id');
        
        /*echo $this->getSelect()->__toString();
        die;*/
        
        return $this;
    }


    public function joinValidOrders($customer_id, $order_states)
    {

        $status_field = Mage::getStoreConfig('rewardpoints/default/status_used', Mage::app()->getStore()->getId());
        
        $this->getSelect()->joinLeft(
            array('ord' => $this->getTable('sales/order')),
            'main_table.order_id = ord.entity_id'
        );
        $this->getSelect()->where('ord.customer_id = ?', $customer_id);
        $this->getSelect()->where($status_field.' in (?)', $order_states);


        return $this;
    }

    public function joinFullCustomerPoints($customer_id, $store_id){

        $cols['points_current'] = 'SUM(main_table.points_current) as nb_credit';

        $this->getSelect()->from($this->getResource()->getMainTable().' as child_table', $cols)
                ->where('main_table.customer_id=?', $customer_id)
                ->where('main_table.rewardpoints_account_id = child_table.rewardpoints_account_id');

        if (Mage::getStoreConfig('rewardpoints/default/store_scope', $store_id) == 1){
            $this->getSelect()->where('find_in_set(?, main_table.store_id)', $store_id);
        }
        
        
        //v.2.0.0
        /*if (Mage::getStoreConfig('rewardpoints/default/points_delay', $store_id)){
            $this->getSelect()->where('( NOW() >= main_table.date_start OR main_table.date_start IS NULL)');
        }*/

        if (Mage::getStoreConfig('rewardpoints/default/points_duration', $store_id)){
            $this->getSelect()->where('( main_table.date_end >= NOW() OR main_table.date_end IS NULL)');
        }
        $this->getSelect()->group('main_table.customer_id');

        return $this;
    }

    public function joinValidPointsOrder($customer_id, $store_id, $order_states, $spent = false, $remove_end = false)
    {
        $statuses = Mage::getStoreConfig('rewardpoints/default/valid_statuses', Mage::app()->getStore()->getId());
        $status_field = Mage::getStoreConfig('rewardpoints/default/status_used', Mage::app()->getStore()->getId());
        
        $order_states_str = "'".implode("',",$order_states)."'";
        
        $sql_required = "";
        
        if (Mage::getConfig()->getModuleConfig('J2t_Rewardproductvalue')->is('active', 'true')){
            if (version_compare(Mage::getVersion(), '1.4.0', '>=')){
                $sql_required = "(  
                                    main_table.order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_REQUIRED ."'
                                    AND main_table.quote_id IN 
                                        (
                                            SELECT quote_id FROM ".$this->getTable('sales/order')." AS orders_quote
                                            WHERE orders_quote.quote_id = main_table.quote_id
                                            AND orders_quote.customer_id = main_table.customer_id
                                            AND orders_quote.$status_field IN (?)
                                        ) 
                                        
                                 ) or";
            }
        }


        if ($spent){
            $cols['points_spent'] = 'SUM(main_table.points_spent) as nb_credit';
        } else {
            $cols['points_current'] = 'SUM(main_table.points_current) as nb_credit';
            $cols['points_spent'] = 'SUM(main_table.points_spent)';
        }

        $this->getSelect()->from($this->getResource()->getMainTable().' as child_table', $cols);

        if (version_compare(Mage::getVersion(), '1.4.0', '>=')){
            //(orders.customer_id = main_table.customer_id or orders.customer_id = main_table.rewardpoints_referral_id)
            /*$this->getSelect()->where("( main_table.order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_REVIEW."' or main_table.order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_ADMIN."'
                    or main_table.order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_REGISTRATION."' or main_table.order_id in (
                        SELECT increment_id FROM ".$this->getTable('sales/order')." AS orders WHERE orders.customer_id = '".$customer_id."' AND orders.state IN (".implode(',',$order_states)."))
                        )");*/
            $this->getSelect()->where("( $sql_required main_table.order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_REVIEW."' or main_table.order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_ADMIN."'
                    or main_table.order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_REGISTRATION."' or main_table.order_id in (
                        SELECT increment_id FROM ".$this->getTable('sales/order')." AS orders WHERE (orders.customer_id = main_table.customer_id OR orders.customer_id IN (SELECT referral_table.rewardpoints_referral_child_id FROM ".$this->getTable('rewardpoints/referral')." AS referral_table WHERE main_table.rewardpoints_referral_id = referral_table.rewardpoints_referral_id)) AND orders.$status_field IN (?))
                        )", $order_states);
        } else {
            $table_sales_order = $this->getTable('sales/order').'_varchar';
            $this->getSelect()->where(" ( $sql_required main_table.order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_REVIEW."' or main_table.order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_ADMIN."'
                            or main_table.order_id = '".Rewardpoints_Model_Stats::TYPE_POINTS_REGISTRATION."' or main_table.order_id in (SELECT increment_id
                                       FROM ".$this->getTable('sales/order')." AS orders
                                       WHERE orders.entity_id IN (
                                           SELECT order_state.entity_id
                                           FROM ".$table_sales_order." AS order_state
                                           WHERE order_state.value <> 'canceled'
                                           AND order_state.value in (?))
                                        ) ) ", $order_states);
        }
           
        $this->getSelect()->where('main_table.customer_id = ?', $customer_id)
                          ->where('main_table.rewardpoints_account_id = child_table.rewardpoints_account_id');

        if (Mage::getStoreConfig('rewardpoints/default/store_scope', Mage::app()->getStore()->getId()) == 1){
            $this->getSelect()->where('find_in_set(?, main_table.store_id)', $store_id);
        }

        //v.2.0.0
        if ((Mage::getStoreConfig('rewardpoints/default/points_delay', $store_id) && !$spent)){
            $this->getSelect()->where('( NOW() >= main_table.date_start OR main_table.date_start IS NULL)');
        }

        if ((Mage::getStoreConfig('rewardpoints/default/points_duration', $store_id) && !$spent) && !$remove_end){
            $this->getSelect()->where('( main_table.date_end >= NOW() or main_table.date_end IS NULL)');
        }
        $this->getSelect()->group('main_table.customer_id');

        //echo $this->getSelect()->__toString();
        //die;
        
        return $this;
    }


}