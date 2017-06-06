<?php

$installer = $this;

$installer->startSetup();

$installer->run('
    INSERT INTO `' . $this->getTable('datafeedmanager_configurations') . '` (`feed_id`, `feed_name`, `feed_type`, `feed_path`, `feed_status`, `feed_updated_at`, `store_id`, `feed_include_header`, `feed_header`, `feed_product`, `feed_footer`, `feed_separator`, `feed_protector`, `feed_required_fields`, `feed_enclose_data`, `datafeedmanager_categories`, `datafeedmanager_type_ids`, `datafeedmanager_visibility`, `datafeedmanager_attributes`) VALUES
(NULL, \'GoogleShopping NEW !\', 1, \'/feeds/\', 1, NULL, 1, 0, \'<?xml version="1.0" encoding="utf-8" ?>\r\n<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">  \r\n<channel>  \r\n<title>Data feed Title</title>\r\n<link>http://www.website.com</link>\r\n<description>Data feed description.</description>\', \'<item>
<!-- Basic Product Information -->
<g:id>{sku}</g:id>
<title>{name,[substr],[70],[...]}</title>
<description>{description,[html_entity_decode],[strip_tags]}</description>
{G:GOOGLE_PRODUCT_CATEGORY}
{G:PRODUCT_TYPE,[10]}
<link>{url parent}</link>
{G:IMAGE_LINK}
<g:condition>new</g:condition>

<!-- Availability & Price -->
<g:availability>{is_in_stock?[in stock]:[out of stock]:[available for order]}</g:availability>
<g:price>{normal_price,[USD],[0]}USD</g:price>
{G:SALE_PRICE,[USD],[0]}

<!-- Unique Product Identifiers-->
<g:brand>{manufacturer}</g:brand>
<g:gtin>{upc}</g:gtin>
<g:mpn>{sku}</g:mpn>
<g:identifier_exists>TRUE</g:identifier_exists>

<!-- Apparel Products -->
<g:gender>{gender}</g:gender>
<g:age_group>{age_group}</g:age_group>
<g:color>{color}</g:color>
<g:size>{size}</g:size>

<!-- Product Variants -->
{G:ITEM_GROUP_ID}
<g:material>{material}</g:material>
<g:pattern>{pattern}</g:pattern>

<!-- Shipping -->
<g:shipping_weight>{weight,[float],[2]}kg</g:shipping_weight>

<!-- AdWords attributes -->
<g:adwords_grouping>{adwords_grouping}</g:adwords_grouping>
<g:adwords_labels>{adwords_labels}</g:adwords_labels>
</item>\', \'</channel>\r\n</rss>\', \';\', \'\', \'\', 1, \'*\', \'simple,configurable,bundle,grouped,virtual,downloadable\', \'1,2,3,4\', \'[{"line": "0", "checked": true, "code": "price", "condition": "gt", "value": "0"}, {"line": "1", "checked": true, "code": "name", "condition": "notnull", "value": ""}, {"line": "2", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "3", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "4", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "5", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "6", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "7", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "8", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "9", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "10", "checked": false, "code": "cost", "condition": "eq", "value": ""}]\')
;');

$installer->endSetup();