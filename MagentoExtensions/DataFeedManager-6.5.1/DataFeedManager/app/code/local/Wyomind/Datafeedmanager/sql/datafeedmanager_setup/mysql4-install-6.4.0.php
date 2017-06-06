<?php

$installer = $this;

$installer->startSetup();

$installer->run('DROP TABLE IF EXISTS ' . $this->getTable('datafeedmanager_configurations'));


$installer->run('

CREATE TABLE IF NOT EXISTS `' . $this->getTable('datafeedmanager_configurations') . '` (
  `feed_id` int(11) NOT NULL auto_increment,
  `feed_name` varchar(20) NOT NULL,
  `feed_type` tinyint(3) NOT NULL,
  `feed_path` varchar(255) NOT NULL default \'/\',
  `feed_status` int(1) NOT NULL default \'0\',
  `feed_updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `store_id` int(2) NOT NULL default \'1\',
  `feed_include_header` int(1) NOT NULL default \'0\',
  `feed_header` text,
  `feed_product` text,
  `feed_footer` text,
  `feed_separator` char(3) default NULL,
  `feed_protector` char(1) default NULL,
  `feed_escape` char(3) default NULL,
  `feed_encoding` varchar(40) NOT NULL default \'UTF-8\',
  `feed_required_fields` text,
  `feed_enclose_data` int(1) NOT NULL default \'1\',
  `feed_clean_data` int(1) NOT NULL default \'1\',
  `datafeedmanager_category_filter` INT(1) DEFAULT 1,
  `datafeedmanager_categories` longtext,
  `datafeedmanager_type_ids` varchar(150) default NULL,
  `datafeedmanager_visibility` varchar(10) default NULL,
  `datafeedmanager_attributes` text,
  `cron_expr` varchar(900) NOT NULL DEFAULT \'{"days":["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],"hours":["00:00","04:00","08:00","12:00","16:00","20:00"]}\',
  `feed_extraheader` text,
  `ftp_enabled` INT(1) DEFAULT \'0\',
  `ftp_host` VARCHAR(300) DEFAULT NULL,
  `ftp_login` VARCHAR(300) DEFAULT NULL,
  `ftp_password` VARCHAR(300) DEFAULT NULL,
  `ftp_active` INT(1) DEFAULT \'0\',
  `ftp_dir` VARCHAR(300) DEFAULT NULL,
  PRIMARY KEY  (`feed_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
');

$installer->run('DROP TABLE IF EXISTS ' . $this->getTable('datafeedmanager_attributes'));
$installer->run('

CREATE TABLE IF NOT EXISTS `' . $this->getTable('datafeedmanager_attributes') . '` (
    `attribute_id` int(11) NOT NULL auto_increment,
    `attribute_name` varchar(100) NOT NULL,
    `attribute_script` text,
    PRIMARY KEY  (`attribute_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
');


$installer->run('
insert into `' . $this->getTable('datafeedmanager_attributes') . '`(`attribute_id`,`attribute_name`,`attribute_script`) values 
(NULL,\'configurable_sizes\',\' if ($product->type_id == \'\'configurable\'\') {
      $childProducts = Mage::getModel(\'\'catalog/product_type_configurable\'\')->getUsedProducts(null, $product);
      $sizes = array();
      foreach ($childProducts as $child)
          $sizes[] = $child->getAttributeText(\'\'size\'\');

      return implode(\'\',\'\', $sizes);
  }
 \');
 ');

$installer->run('DROP TABLE IF EXISTS ' . $this->getTable('datafeedmanager_options'));

$installer->run('

CREATE TABLE IF NOT EXISTS `' . $this->getTable('datafeedmanager_options') . '` (
    `option_id` int(11) NOT NULL auto_increment,
    `option_name` varchar(100) NOT NULL,
    `option_script` text,
    `option_param` int(1),
    PRIMARY KEY  (`option_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
');

$installer->run('
insert into `' . $this->getTable('datafeedmanager_options') . '` (`option_id`,`option_name`,`option_script`,`option_param`) values 
(NULL,\'number_format\',\'$value =number_format($value,$param[1],$param[2],$param[3]);\',3)
,(NULL,\'str_pad_left\',\'$value=str_pad($value,$param[1],$param[2],STR_PAD_LEFT);\',2);

');

$installer->run('
    INSERT INTO `' . $this->getTable('datafeedmanager_configurations') . '` (`feed_id`, `feed_name`, `feed_type`, `feed_path`, `feed_status`, `feed_updated_at`, `store_id`, `feed_include_header`, `feed_header`, `feed_product`, `feed_footer`, `feed_separator`, `feed_protector`, `feed_required_fields`, `feed_enclose_data`, `datafeedmanager_categories`, `datafeedmanager_type_ids`, `datafeedmanager_visibility`, `datafeedmanager_attributes`) VALUES
(NULL, \'GoogleShopping\', 1, \'/feeds/\', 1, NULL, 1, 0, \'<?xml version="1.0" encoding="utf-8" ?>\r\n<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">  \r\n<channel>  \r\n<title>Data feed Title</title>\r\n<link>http://www.website.com</link>\r\n<description>Data feed description.</description>\', \'<item>
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
</item>\', \'</channel>\r\n</rss>\', \';\', \'\', \'\', 1, \'*\', \'simple,configurable,bundle,grouped,virtual,downloadable\', \'1,2,3,4\', \'[{"line": "0", "checked": true, "code": "price", "condition": "gt", "value": "0"}, {"line": "1", "checked": true, "code": "name", "condition": "notnull", "value": ""}, {"line": "2", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "3", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "4", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "5", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "6", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "7", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "8", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "9", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "10", "checked": false, "code": "cost", "condition": "eq", "value": ""}]\'),
(NULL, \'LeGuide\', 1, \'/feeds/\', 1, NULL, 1, 0, \'<catalogue lang="FR" >\', \'<product place="{inc}">\r\n   <categorie>{categories}</categorie> \r\n   <identifiant_unique>{sku}</identifiant_unique>\r\n   <titre>{meta_title}</titre>\r\n   <prix currency="EUR">{price}</prix>\r\n   <url_produit>{url}</url_produit>\r\n   <url_image>{image}</url_image>\r\n   <description>{short_description}</description>\r\n   <frais_de_livraison>90</frais_de_livraison>\r\n   <D3E>0</D3E>\r\n   <disponibilite>{is_in_stock?[0]:[1]} </disponibilite>\r\n   <delai_de_livraison>5 jours</delai_de_livraison>\r\n   <? if({is_special_price}) return \'\'<prix_barre currency="EUR">{normal_price}</prix_barre>\'\';?>\r\n   <type_promotion>{is_special_price?[1]:[0]}</type_promotion>\r\n   <occasion>0</occasion>\r\n   <devise>EUR</devise>\r\n</product>\r\n\', \'</catalogue>\', \';\', \'\', \'\', 1, \'*\', \'simple,configurable,bundle,virtual,downloadable\', \'1,2,3,4\', \'[{"line": "0", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "1", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "2", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "3", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "4", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "5", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "6", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "7", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "8", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "9", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "10", "checked": false, "code": "cost", "condition": "eq", "value": ""}]\'),
(NULL, \'Twenga\', 3, \'/feeds/\', 1, NULL, 1, 1, \'{"header":["price", "product_url", "designation", "category", "image_url", "description", "merchant_id", "in_stock", "Stock_detail", "product_type", "condition"]}\', \'{"product":["{price}", "{url}", "{meta_title}", "{categories}", "{image}", "{short_description}", "{sku}", "{is_in_stock?[Y]:[N]}", "{qty}", "1", "0"]}\', \'\', \';\', \'"\', \'\', 1, \'*\', \'simple,configurable,bundle,virtual,downloadable\', \'1,2,3,4\', \'[{"line": "0", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "1", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "2", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "3", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "4", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "5", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "6", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "7", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "8", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "9", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "10", "checked": false, "code": "cost", "condition": "eq", "value": ""}]\'),
(NULL, \'Kelkoo\', 1, \'/feeds/\', 1, NULL, 1, 1, \'<?xml version="1.0" encoding="ISO-8859-1"?>\r\n<products>\', \'<product>\r\n   <id>{sku}</id>\r\n   <model>{meta_title}</model>\r\n   <description>{short_description,[substr],[180]}</description>\r\n   <price>{price}</price>\r\n   <url>{url}</url>\r\n   <merchantcat>{categories}</merchantcat>\r\n   <image>{image}</image>\r\n   <used>neuf</used>\r\n   <availability>{is_in_stock?[1]:[4]}</availability>\r\n   <deliveryprice>90.00</deliveryprice>\r\n   <deliverytime>Sous 5 jours</deliverytime>\r\n   <pricenorebate>{normal_price}</pricenorebate>\r\n   <percentagepromo><? return round(100-({special_price}*100/{normal_price}) ); ?></percentagepromo>\r\n   <promostart><? return date("Y-m-d",time()); ?></promostart>\r\n   <promoend><? return date("Y-m-d",time()+604800); ?></promoend>\r\n</product>\r\n\', \'</products>\', \';\', \'\', \'\', 1, \'*\', \'simple,configurable,bundle,virtual,downloadable\', \'1,2,3,4\', \'[{"line": "0", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "1", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "2", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "3", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "4", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "5", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "6", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "7", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "8", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "9", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "10", "checked": false, "code": "cost", "condition": "eq", "value": ""}]\'),
(NULL, \'Shopping.com\', 1, \'/feeds/\', 1, NULL, 1, 0, \'<?xml version="1.0" encoding="ISO-8859-1"?>\r\n<Products>\', \'	<Product>			\r\n		<Merchant_SKU>{sku}</Merchant_SKU>		\r\n		<MPN></MPN>		\r\n		<UPC></UPC>		\r\n		<EAN></EAN>		\r\n		<ISBN></ISBN>		\r\n		<Manufacturer>{manufacturer}</Manufacturer>		\r\n		<Product_Name>{name}</Product_Name>		\r\n		<Product_URL>{url}</Product_URL> 		\r\n		<Mobile_URL></Mobile_URL> 		\r\n		<Current_Price>{price}</Current_Price> 		\r\n		<Original_Price>{normal_price}</Original_Price> 		\r\n		<Category_ID></Category_ID>		\r\n		<Category_Name>{categories}</Category_Name>		\r\n		<Sub-category_Name></Sub-category_Name>		\r\n		<Parent_SKU></Parent_SKU>		\r\n		<Parent_Name></Parent_Name>		\r\n		<Product_Description>{short_description}</Product_Description>		\r\n		<Stock_Description></Stock_Description>		\r\n		<Product_Bullet_Point_1></Product_Bullet_Point_1>		\r\n		<Product_Bullet_Point_2></Product_Bullet_Point_2>		\r\n		<Product_Bullet_Point_3></Product_Bullet_Point_3>		\r\n		<Product_Bullet_Point_4></Product_Bullet_Point_4>		\r\n		<Product_Bullet_Point_5></Product_Bullet_Point_5>		\r\n		<Image_URL>{image}</Image_URL>		\r\n		<Alternative_Image_URL_1>{image}</Alternative_Image_URL_1>		\r\n	\r\n		<Product_Type></Product_Type>		\r\n		<Style></Style>		\r\n		<Condition>Neuf</Condition>		\r\n		<Gender></Gender>		\r\n		<Department></Department>		\r\n		<Age_Range></Age_Range>		\r\n		<Color>Noir/Blanc</Color>		\r\n		<Material></Material>		\r\n		<Format></Format>		\r\n		<Team></Team>		\r\n		<League></League>		\r\n		<Fan_Gear_Type></Fan_Gear_Type>		\r\n		<Software_Platform></Software_Platform>		\r\n		<Software_Type></Software_Type>		\r\n		<Watch_Display_Type></Watch_Display_Type>		\r\n		<Cell_Phone_Type></Cell_Phone_Type>		\r\n		<Cell_Phone_Service_Provider></Cell_Phone_Service_Provider>		\r\n		<Cell_Phone_Plan_Type></Cell_Phone_Plan_Type>		\r\n		<Usage_Profile></Usage_Profile>		\r\n		<Size></Size>		\r\n		<Size_Unit_of_Measure></Size_Unit_of_Measure>		\r\n		<Product_Length></Product_Length>		\r\n		<Length_Unit_of_Measure></Length_Unit_of_Measure>		\r\n		<Product_Width></Product_Width >		\r\n		<Width_Unit_of_Measure></Width_Unit_of_Measure>		\r\n		<Product_Height></Product_Height>		\r\n		<Height_Unit_of_Measure></Height_Unit_of_Measure>		\r\n		<Product_Weight></Product_Weight>		\r\n		<Weight_Unit_of_Measure></Weight_Unit_of_Measure>		\r\n		<Unit_Price></Unit_Price>		\r\n		<Top_Seller_Rank></Top_Seller_Rank>		\r\n		<Product_Launch_Date></Product_Launch_Date>		\r\n		<Stock_Availability></Stock_Availability>		\r\n		<Shipping_Rate></Shipping_Rate>		\r\n		<Shipping_Weight></Shipping_Weight>		\r\n		<Estimated_Ship_Date></Estimated_Ship_Date>		\r\n		<Coupon_Code></Coupon_Code>		\r\n		<Coupon_Code_Description></Coupon_Code_Description>		\r\n		<Merchandising_Type></Merchandising_Type>		\r\n		<Bundle>Non</Bundle>		\r\n		<Related_Products></Related_Products>		\r\n	</Product>				\', \'</Products>	\', \';\', \'\', \'\', 1, \'*\', \'simple,configurable,bundle,virtual,downloadable\', \'1,2,3,4\', \'[{"line": "0", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "1", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "2", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "3", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "4", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "5", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "6", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "7", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "8", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "9", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "10", "checked": false, "code": "cost", "condition": "eq", "value": ""}]\'),
(NULL, \'BingShopping\', 2, \'/feeds/\', 1, NULL, 1, 1, \'{"header":["MerchantProductID","Title","ProductURL","Price","Description","ImageURL","Brand","MPN","SKU","Availability","MerchantCategory","ShippingWeight","Condition","B_Category "]}\', \'{"product":["{id}","{name}","{url parent,[substr],[100]}","{price} USD","{description parent,[html_entity_decode],[strip_tags]} ","{image}","{manufacturer}","{sku}","{sku}","in stock","{categories,[1]}","{weight,[float],[2]} kilograms","new","{category_mapping}"]}\', NULL, \'\\t\', \'\', NULL, 0, NULL, \'simple,configurable\', \'1,2,3,4\', \'[{"line":"0","checked":true,"code":"price","condition":"gt","value":"0"},{"line":"1","checked":true,"code":"name","condition":"notnull","value":""},{"line":"2","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"3","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"4","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"5","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"6","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"7","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"8","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"9","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"10","checked":false,"code":"activation_information","condition":"eq","value":""}]\'),
(NULL, \'Shopzilla\', 2, \'/feeds/\', 1, NULL, 1, 1, \'{"header":["Category ID", "Manufacturer", "Title", "Description", "Product URL", "Image URL", "SKU", "Availability", "Condition", "Ship Weight", "Ship Cost","Bid", "Promotional Code", "UPC", "Price"]}\', \'{"product":["{category_mapping,[0]}","{manufacturer}","{name}","{description,[html_entity_decode],[strip_tags]}","{url}","{image,[0]}","{sku}","{is_in_stock?[in stock]:[out of stock]}","new","{weight}","0","","","{ean}","{price}"]}\', NULL, \'\\\t\', \'\', NULL, 0, NULL, \'simple\', \'2,3,4\', \'[{"line":"0","checked":true,"code":"sku","condition":"notnull","value":""},{"line":"1","checked":true,"code":"name","condition":"notnull","value":""},{"line":"2","checked":true,"code":"description","condition":"notnull","value":""},{"line":"3","checked":true,"code":"price","condition":"gt","value":"0"},{"line":"4","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"5","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"6","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"7","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"8","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"9","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"10","checked":false,"code":"activation_information","condition":"eq","value":""}]\'),
(NULL, \'PriceGrabber\', 2, \'/feeds/\', 1, NULL, 1, 1, \'{"header":["Product Name", "Manufacturer Part Number (MPN)", "UPC", "Unique Retailer SKU", "Categorization", "Detailed Description", "Selling Price", "Availability", "Product URL", "Image URL", "Product Condition", "Shipping Costs", "Weight"]}\', \'{"product":["{name}", "{mpn}", "{upc}", "{sku}", "{category_mapping}", "{name}", "{price}", "{is_in_stock?[Yes]:[No]}", "{url}", "{image}", "new", "0", "{weight}"]}\', NULL, \'|\', \'\', NULL, 0, NULL, \'simple\', \'2,3,4\', \'[{"line":"0","checked":true,"code":"sku","condition":"notnull","value":""},{"line":"1","checked":true,"code":"name","condition":"notnull","value":""},{"line":"2","checked":true,"code":"description","condition":"notnull","value":""},{"line":"3","checked":true,"code":"price","condition":"gt","value":"0"},{"line":"4","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"5","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"6","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"7","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"8","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"9","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"10","checked":false,"code":"activation_information","condition":"eq","value":""}]\'),
(NULL, \'Nextag\', 2, \'/feeds/\', 1, NULL, 1, 1, \'{"header":["Product Name", "Manufacturer", "Manufacturer Part #", "UPC", "Seller Part #", "Category", "Description", "Price", "Stock Status", "Click-Out URL", "Image URL", "Condition", "Ground Shipping", "Weight"]}\', \'{"product":["{name}", "{manufacturer}", "{mpn}", "{upc}", "{sku}", "{categories}", "{short_description}", "{price}", "{is_in_stock?[Yes]:[No]}", "{url}", "{image}", "new", "0.00", "{weight}"]}\', NULL, \'|\', \'\', NULL, 0, NULL, \'simple\', \'2,3,4\', \'[{"line":"0","checked":true,"code":"sku","condition":"notnull","value":""},{"line":"1","checked":true,"code":"name","condition":"notnull","value":""},{"line":"2","checked":true,"code":"description","condition":"notnull","value":""},{"line":"3","checked":true,"code":"price","condition":"gt","value":"0"},{"line":"4","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"5","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"6","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"7","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"8","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"9","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"10","checked":false,"code":"activation_information","condition":"eq","value":""}]\'),
(NULL, \'AmazonProducts\', 1, \'/feeds/\', 1, NULL, 1, 0, \'<?xml version="1.0" encoding="utf-8"?>\r\n<AmazonEnvelope xsi:noNamespaceSchemaLocation="amzn-envelope.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">\r\n<Header>\r\n    <DocumentVersion>1.01</DocumentVersion>\r\n    <MerchantIdentifier>YOUR_MERCHANT_ID_HERE</MerchantIdentifier>\r\n</Header>\r\n<MessageType>Product</MessageType>\r\n<PurgeAndReplace>false</PurgeAndReplace>\', \'<Message>\r\n    <MessageID>{inc}</MessageID>\r\n    <OperationType>Update</OperationType>\r\n    <Product>\r\n        <SKU>{sku}</SKU>\r\n        <StandardProductID>\r\n            <Type>ASIN</Type>\r\n            <Value>{ean}</Value>\r\n        </StandardProductID>\r\n        <ProductTaxCode>A_GEN_NOTAX</ProductTaxCode>\r\n        <DescriptionData>\r\n            <Title>{name}</Title>\r\n            <Brand>{manufacturer}</Brand>\r\n            <Description>{description}</Description>\r\n            <ShippingWeight unitOfMeasure="KG">{weight}</ShippingWeight>\r\n        </DescriptionData>\r\n        <ProductData>\r\n            <Home>\r\n                <ProductType>\r\n                    <Home>\r\n                        <VariationData>\r\n                            <VariationTheme>Size</VariationTheme>\r\n                        </VariationData>\r\n                    </Home>\r\n                </ProductType>\r\n                <Parentage>child</Parentage>\r\n            </Home>\r\n        </ProductData>\r\n    </Product>\r\n</Message>\', \'</AmazonEnvelope>\', \';\', \'\', NULL, 1, NULL, \'simple,configurable\', \'2,3,4\', \'[{"line":"0","checked":true,"code":"sku","condition":"notnull","value":""},{"line":"1","checked":true,"code":"name","condition":"notnull","value":""},{"line":"2","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"3","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"4","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"5","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"6","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"7","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"8","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"9","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"10","checked":false,"code":"activation_information","condition":"eq","value":""}]\'),
(NULL, \'AmazonPrice\', 1, \'/feeds/\', 1, NULL, 1, 0, \'<?xml version="1.0" encoding="utf-8"?>\r\n<AmazonEnvelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="amzn-envelope.xsd">\r\n<Header>\r\n    <DocumentVersion>1.01</DocumentVersion>\r\n    <MerchantIdentifier>YOUR_MERCHANT_ID_HERE</MerchantIdentifier>\r\n</Header>\r\n<MessageType>Price</MessageType>\', \'<Message>\r\n    <MessageID>{inc}</MessageID>\r\n    <Price>\r\n        <SKU>{sku}</SKU>\r\n        <StandardPrice currency="USD">{price,[USD]}</StandardPrice>\r\n    </Price>\r\n</Message>\', \'</AmazonEnvelope>\', \';\', \'\', NULL, 1, NULL, \'simple,configurable\', \'2,3,4\', \'[{"line":"0","checked":true,"code":"sku","condition":"notnull","value":""},{"line":"1","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"2","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"3","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"4","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"5","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"6","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"7","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"8","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"9","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"10","checked":false,"code":"activation_information","condition":"eq","value":""}]\'),
(NULL, \'AmazonInventory\', 1, \'/feeds/\', 1, NULL, 1, 0, \'<?xml version="1.0" encoding="utf-8"?>\r\n<AmazonEnvelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="amzn-envelope.xsd">\r\n    <Header>\r\n        <DocumentVersion>1.01</DocumentVersion>\r\n        <MerchantIdentifier>YOUR_MERCHANT_ID_HERE</MerchantIdentifier>\r\n    </Header>\r\n    <MessageType>Inventory</MessageType>\', \' <Message>\r\n        <MessageID>{inc}</MessageID>\r\n        <Inventory>\r\n            <SKU>{sku}</SKU>\r\n            <Quantity>{qty}</Quantity>\r\n        </Inventory>\r\n    </Message>\', \'</AmazonEnvelope>\', \';\', \'\', NULL, 1, NULL, \'simple,configurable\', \'2,3,4\', \'[{"line":"0","checked":true,"code":"sku","condition":"notnull","value":""},{"line":"1","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"2","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"3","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"4","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"5","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"6","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"7","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"8","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"9","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"10","checked":false,"code":"activation_information","condition":"eq","value":""}]\'),
(NULL, \'AmazonImage\', 1, \'/feeds/\', 1, NULL, 1, 0, \'<?xml version="1.0" encoding="utf-8"?>\r\n<AmazonEnvelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="amzn-envelope.xsd">\r\n       <Header>\r\n        <DocumentVersion>1.01</DocumentVersion>\r\n        <MerchantIdentifier>YOUR_MERCHANT_ID_HERE</MerchantIdentifier>\r\n    </Header>\r\n    <MessageType>ProductImage</MessageType>\', \' <Message>\r\n            <MessageID>{inc}</MessageID>\r\n            <ProductImage>\r\n                <SKU>{sku}</SKU>\r\n                <ImageType>Main</ImageType>\r\n                <ImageLocation>{image,[0]}</ImageLocation>\r\n            </ProductImage>\r\n        </Message>\', \'</AmazonEnvelope>\', \';\', \'\', NULL, 0, \'[{"line":"1","checked":false,"mapping":""},{"line":"1/3","checked":false,"mapping":""},{"line":"1/3/10","checked":false,"mapping":""},{"line":"1/3/10/22","checked":false,"mapping":""},{"line":"1/3/10/23","checked":false,"mapping":""},{"line":"1/3/13","checked":false,"mapping":""},{"line":"1/3/13/12","checked":false,"mapping":""},{"line":"1/3/13/12/25","checked":false,"mapping":""},{"line":"1/3/13/12/26","checked":false,"mapping":""},{"line":"1/3/13/15","checked":false,"mapping":""},{"line":"1/3/13/15/27","checked":false,"mapping":""},{"line":"1/3/13/15/28","checked":false,"mapping":""},{"line":"1/3/13/15/29","checked":false,"mapping":""},{"line":"1/3/13/15/30","checked":false,"mapping":""},{"line":"1/3/13/15/31","checked":false,"mapping":""},{"line":"1/3/13/15/32","checked":false,"mapping":""},{"line":"1/3/13/15/33","checked":false,"mapping":""},{"line":"1/3/13/15/34","checked":false,"mapping":""},{"line":"1/3/13/8","checked":false,"mapping":""},{"line":"1/3/18","checked":false,"mapping":""},{"line":"1/3/18/19","checked":false,"mapping":""},{"line":"1/3/18/24","checked":false,"mapping":""},{"line":"1/3/18/4","checked":false,"mapping":""},{"line":"1/3/18/5","checked":false,"mapping":""},{"line":"1/3/18/5/16","checked":false,"mapping":""},{"line":"1/3/18/5/17","checked":false,"mapping":""},{"line":"1/3/20","checked":false,"mapping":""}]\', \'simple,configurable\', \'2,3,4\', \'[{"line":"0","checked":true,"code":"image","condition":"notnull","value":""},{"line":"1","checked":true,"code":"sku","condition":"notnull","value":""},{"line":"2","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"3","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"4","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"5","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"6","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"7","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"8","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"9","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"10","checked":false,"code":"activation_information","condition":"eq","value":""}]\'),
(NULL, \'amazonAds\', 2, \'/feeds/\', 1, NULL, 1, 1, \'{"header":["Category","Title","Link","SKU","Price","Brand","Department","UPC","Image","Description","Manufacturer","Mfr part number","Age","Color","Shipping Weight","Size"]}\', \'{"product":["{mapping}","{name}","{url parent}","{sku}","{price}","{brand}","men, women","{upc}","{image}","{description,[html_entity_decode],[strip_tags],[inline]}","{manufacturer}","{sku}","","{color}","{weight}","{size}"]}\', NULL, \'\\t\', \'\', NULL, 0, NULL, \'simple,configurable\', \'2,3,4\', \'[{"line":"0","checked":true,"code":"name","condition":"notnull","value":""},{"line":"1","checked":true,"code":"price","condition":"gt","value":"0"},{"line":"2","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"3","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"4","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"5","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"6","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"7","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"8","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"9","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"10","checked":false,"code":"activation_information","condition":"eq","value":""}]\'),
(NULL, \'Ebay\', 2, \'/feeds/\', 1, NULL, 1, 1, \'{"header":["*Action(SiteID=France|Country=FR|Currency=EUR|Version=403|CC=ISO-8859-1)","*Category","*Title","Description","*ConditionID","PicURL","*Quantity","*Format","*StartPrice","BuyItNowPrice","*Duration","ImmediatePayRequired","*Location"," GalleryType","PayPalAccepted","PayPalEmailAddress","PaymentInstructions","DomesticInsuranceOption","DomesticInsuranceFee","InternationalInsuranceOption","InternationalInsuranceFee","StoreCategory","ShippingDiscountProfileID"," ShippingService-1:Option","ShippingService-1:Cost","ShippingService-1:Priority","ShippingService-2:Option","ShippingService-2:Cost","ShippingService-2:Priority"," *DispatchTimeMax","CustomLabel"," *ReturnsAcceptedOption","AdditionalDetails"]}\', \'{"product":["Add","103440","{name}","{description,[strip_tags],[html_entity_decode],[inline]}","1000","{image}","{qty}","FixedPrice","{price}","{price}","GTC","1","USA KOi, http://yourwebsite.com","None","1","contact@website.com","","","","","","","","","","","","","","5","","ReturnAccepted",""]}\', NULL, \'\\t\', \'\', NULL, 0, NULL, \'simple,configurable\', \'2,3,4\', \'[{"line":"0","checked":true,"code":"name","condition":"notnull","value":""},{"line":"1","checked":true,"code":"price","condition":"gt","value":"0"},{"line":"2","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"3","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"4","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"5","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"6","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"7","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"8","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"9","checked":false,"code":"activation_information","condition":"eq","value":""},{"line":"10","checked":false,"code":"activation_information","condition":"eq","value":""}]\'),
(NULL, \'Bestlist\', 1, \'/feeds/\', 1, NULL, 1, 0, \'<?xml version="1.0" encoding="UTF-8"?>
<feed>\', \'<item>
    <titel>{name}</titel>
    <prijs>{price}</prijs>
    <url>{url}</url>
    <url_productplaatje>{image,[0]}</url_productplaatje>
    <sku>{sku}</sku>
    <beschrijving>{description}</beschrijving>
</item>\', \'</feed>\', \';\', \'\', \'\', 1, \'*\', \'simple,configurable,bundle,virtual,downloadable\', \'1,2,3,4\', \'[{"line": "0", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "1", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "2", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "3", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "4", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "5", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "6", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "7", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "8", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "9", "checked": false, "code": "cost", "condition": "eq", "value": ""}, {"line": "10", "checked": false, "code": "cost", "condition": "eq", "value": ""}]\'),
(NULL, \'Idealo\', 3, \'/feeds/\', 1, NULL, 1, 1, \'{"header":["Article number","EAN (European article number)","Manufacturers code / number","Manufacturer","Product Name","Description","Product Group","Price GBP","Delivery status","Product URL","Picture URL","Delivery Costs"]}\', \'{"product":["{sku}","{ean}","{mpn}","{manufacturer}","{name}","{description,[strip_tags],[html_entity_decode],[inline],[cleaner]}","{categories}","{price,[GBP],[GB]}","Available Immediatly","{url parent}","{image parent}",""]}\', NULL, \';\', \'\', NULL, 0, NULL, \'simple,configurable,bundle,grouped,virtual,downloadable\', \'1,2,3,4\', \'[]\');
');

if (strpos($_SERVER['HTTP_HOST'], "wyomind.com"))
    $installer->run('UPDATE `' . $this->getTable('datafeedmanager_configurations') . '` SET datafeedmanager_categories =\'[{"line": "1/3", "checked": false, "mapping": ""}, {"line": "1/3/10", "checked": false, "mapping": ""}, {"line": "1/3/10/22", "checked": false, "mapping": "Furniture > Living Room Furniture"}, {"line": "1/3/10/23", "checked": false, "mapping": "Furniture > Bedroom Furniture"}, {"line": "1/3/13", "checked": false, "mapping": ""}, {"line": "1/3/13/12", "checked": false, "mapping": "Cameras & Optics"}, {"line": "1/3/13/12/25", "checked": false, "mapping": "Cameras & Optics > Camera & Optic Accessories"}, {"line": "1/3/13/12/26", "checked": false, "mapping": "Cameras & Optics > Cameras > Digital Cameras"}, {"line": "1/3/13/15", "checked": false, "mapping": ""}, {"line": "1/3/13/15/27", "checked": false, "mapping": "Electronics > Computers > Desktop Computers"}, {"line": "1/3/13/15/28", "checked": false, "mapping": "Electronics > Computers > Desktop Computers"}, {"line": "1/3/13/15/29", "checked": false, "mapping": "Electronics > Computers > Computer Accessorie"}, {"line": "1/3/13/15/30", "checked": false, "mapping": "Electronics > Computers > Computer Accessorie"}, {"line": "1/3/13/15/31", "checked": false, "mapping": "Electronics > Computers > Computer Accessorie"}, {"line": "1/3/13/15/32", "checked": false, "mapping": "Electronics > Computers > Computer Accessorie"}, {"line": "1/3/13/15/33", "checked": false, "mapping": "Electronics > Computers > Computer Accessorie"}, {"line": "1/3/13/15/34", "checked": false, "mapping": "Electronics > Computers > Computer Accessorie"}, {"line": "1/3/13/8", "checked": false, "mapping": "Electronics > Communications > Telephony > Mobile Phones"}, {"line": "1/3/18", "checked": false, "mapping": ""}, {"line": "1/3/18/19", "checked": false, "mapping": "Apparel & Accessories > Clothing > Activewear > Sweatshirts"}, {"line": "1/3/18/24", "checked": false, "mapping": "Apparel & Accessories > Clothing > Pants"}, {"line": "1/3/18/4", "checked": false, "mapping": "Apparel & Accessories > Clothing > Tops > Shirts"}, {"line": "1/3/18/5", "checked": false, "mapping": "Apparel & Accessories > Shoes"}, {"line": "1/3/18/5/16", "checked": false, "mapping": "Apparel & Accessories > Shoes"}, {"line": "1/3/18/5/17", "checked": false, "mapping": "Apparel & Accessories > Shoes"}, {"line": "1/3/20", "checked": false, "mapping": ""}]\'');


$installer->endSetup();

