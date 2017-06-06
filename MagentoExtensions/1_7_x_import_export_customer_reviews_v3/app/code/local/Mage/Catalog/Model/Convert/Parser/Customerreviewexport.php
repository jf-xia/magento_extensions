<?php
/**
 * Customerreviewexport.php
 * CommerceExtensions @ InterSEC Solutions LLC.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.commerceextensions.com/LICENSE-M1.txt
 *
 * @category   Review
 * @package    Customerreviewexport
 * @copyright  Copyright (c) 2003-2010 CommerceExtensions @ InterSEC Solutions LLC. (http://www.commerceextensions.com)
 * @license    http://www.commerceextensions.com/LICENSE-M1.txt
 */ 
 
class Mage_Catalog_Model_Convert_Parser_Customerreviewexport extends Mage_Eav_Model_Convert_Parser_Abstract
{
/**
     * @deprecated not used anymore
     */
    public function parse()
    {
			return $this;
		}
 /**
     * Unparse (prepare data) loaded categories
     *
     * @return Mage_Catalog_Model_Convert_Adapter_Customerreviewexport
     */
    public function unparse()
    {
				 $ByStoreID = $this->getVar('store');
				 $recordlimit = $this->getVar('recordlimit');
				 $resource = Mage::getSingleton('core/resource');
				 $prefix = Mage::getConfig()->getNode('global/resources/db/table_prefix');
				 $read = $resource->getConnection('core_read');
				 $row = array();
				 $finalratingoptions="";
				 
				 /* LAST ON 1.4.0.1
				 SELECT review.review_id, review.created_at, review.entity_id, review.entity_pk_value, review.status_id, review_detail.store_id, review_detail.title, review_detail.detail, review_detail.nickname, review_detail.customer_id, review_entity.entity_code, review_status.status_code, review_entity_summary.reviews_count, review_entity_summary.rating_summary FROM review INNER JOIN review_detail ON review_detail.review_id = review.review_id INNER JOIN review_entity_summary ON review_entity_summary.entity_pk_value = review.entity_pk_value LEFT JOIN review_entity ON review_entity.entity_id = review.entity_id LEFT JOIN review_status ON review_status.status_id = review.status_id GROUP BY review.review_id 
					*/
				 $select_qry = "SELECT ".$prefix."review.review_id, ".$prefix."review.created_at, ".$prefix."review.entity_id, ".$prefix."review.entity_pk_value, ".$prefix."review.status_id, ".$prefix."review_detail.store_id, ".$prefix."review_detail.title, ".$prefix."review_detail.detail, ".$prefix."review_detail.nickname, ".$prefix."review_detail.customer_id, ".$prefix."review_entity.entity_code, ".$prefix."review_status.status_code, ".$prefix."review_entity_summary.reviews_count, ".$prefix."review_entity_summary.rating_summary FROM ".$prefix."review INNER JOIN ".$prefix."review_detail ON ".$prefix."review_detail.review_id = ".$prefix."review.review_id INNER JOIN ".$prefix."review_entity_summary ON ".$prefix."review_entity_summary.entity_pk_value = ".$prefix."review.entity_pk_value LEFT JOIN ".$prefix."review_entity ON ".$prefix."review_entity.entity_id = ".$prefix."review.entity_id LEFT JOIN ".$prefix."review_status ON ".$prefix."review_status.status_id = ".$prefix."review.status_id GROUP BY ".$prefix."review.review_id";
				 
					$rows = $read->fetchAll($select_qry);
					foreach($rows as $data)
					 { 
					 
					 			$row["created_at"] = $data['created_at'];
					 			$row["review_title"] = $data['title'];
					 			$row["review_detail"] = $data['detail'];
					 			$row["nickname"] = $data['nickname'];
					 			$row["customer_id"] = $data['customer_id'];
					 			$row["store_id_review_is_from"] = $data['store_id'];
								if($data['entity_pk_value'] != "") {
					 				$row["product_id"] = $data['entity_pk_value'];
								}
					 			$row["entity_type"] = $data['entity_code'];
					 			$row["status_code"] = $data['status_code'];
					 			$row["reviews_count"] = $data['reviews_count'];
					 			$row["rating_summary"] = $data['rating_summary'];
					 			
								$finalratingoptions="";
								$select_qry2 = "SELECT ".$prefix."rating.rating_id, ".$prefix."rating_option_vote.value FROM ".$prefix."rating INNER JOIN ".$prefix."rating_option_vote ON ".$prefix."rating_option_vote.rating_id = ".$prefix."rating.rating_id WHERE review_id = '".$data['review_id']."'";
								$rows2 = $read->fetchAll($select_qry2);
								foreach($rows2 as $data2)
								 { 
					 				$finalratingoptions .= $data2['rating_id'] . ":" . $data2['value'] . ",";
								 }
								 
								$row["rating_options"] = $finalratingoptions;
								if($ByStoreID != "") {
									$select__store_ids_qry = "SELECT store_id FROM ".$prefix."review_store WHERE review_id = '".$data['review_id']."' AND store_id = '".$ByStoreID."'";
								} else {
									$select__store_ids_qry = "SELECT store_id FROM ".$prefix."review_store WHERE review_id = '".$data['review_id']."'";
								}
								$storeidrows = $read->fetchAll($select__store_ids_qry);
								$finalstoreidsforexport="";
								foreach($storeidrows as $datastoreid)
								{ 
								 $finalstoreidsforexport .= $datastoreid['store_id'] . ",";
								}
								$finalstoreidsforexport = substr_replace($finalstoreidsforexport,"",-1);
					 			$row["store_ids"] = $finalstoreidsforexport;
								
					 			$batchExport = $this->getBatchExportModel()
                ->setId(null)
                ->setBatchId($this->getBatchModel()->getId())
                ->setBatchData($row)
                ->setStatus(1)
                ->save();
					 }
					 
					 
        return $this;
		}
}

?>