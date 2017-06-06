
<?php

$installer = $this;

$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('datafeedmanager')};
 ");


$installer->run("

CREATE TABLE {$this->getTable('datafeedmanager')} (
  `feed_id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_name` varchar(20) NOT NULL,
  `feed_type` tinyint(3) NOT NULL,
  `feed_path` varchar(255) NOT NULL DEFAULT '/',
  `feed_status` int(1) NOT NULL DEFAULT '0',
  `feed_updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `store_id` int(2) NOT NULL DEFAULT '1',
  `feed_include_header` int(1) NOT NULL DEFAULT '0',
  `feed_header` text,
  `feed_product` text,
  `feed_footer` text,
  `feed_separator` char(2) DEFAULT NULL,
  `feed_protector` char(1) DEFAULT NULL,
  `feed_required_fields` text,
  `feed_enclose_data` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`feed_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

");

$installer->run("

insert into `{$this->getTable('datafeedmanager')}`(`feed_id`,`feed_name`,`feed_type`,`feed_path`,`feed_status`,`feed_updated_at`,`store_id`,`feed_include_header`,`feed_header`,`feed_product`,`feed_footer`,`feed_separator`,`feed_protector`,`feed_required_fields`,`feed_enclose_data`) values (28,'google_shopping',1,'/feeds/',1,'2011-02-04 17:12:03',1,0,'<?xml version=\"1.0\" encoding=\"utf-8\" ?>
<rss version=\"2.0\" xmlns:g=\"http://base.google.com/ns/1.0\">  
<channel>  
<titre>Data feed Title</titre>
<lien>http://www.comptoirdoutremer.fr</lien>
<descriptif>Data feed description.</descriptif>','<item>
<g:id>{sku}</g:id>
<title>{meta_title}</title>
<link>{url}</link>
<g:price>{price} EUR</g:price>
<description>{short_description}</description>
<g:condition>new</g:condition>
<g:product_type>{categories}</g:product_type>
<g:image_link>{image}</g:image_link>
<g:availability>{stock_status}</g:availability>
<g:quantity>{qty}</g:quantity>
<g:genre>{__category}</g:genre>
<g:featured_product>{is_special_price ? [y] : [n]}</g:featured_product>
<g:color>{__color}</g:color>
<g:size>{__dimensions}</g:size>
</item>

','</channel>
</rss>',';','','',1)
, (29,'le_guide',1,'/feeds/',1,'2011-02-07 10:29:49',1,0,'<catalogue lang=\"FR\" date=\"<? return (date(\"Y-m-d h:i\")) ; ?>\" GMT=\"+1\">','<product place=\"{inc}\">
   <categorie>{categories}</categorie> 
   <identifiant_unique>{sku}</identifiant_unique>
   <titre>{meta_title}</titre>
   <prix currency=\"EUR\">{price}</prix>
   <url_produit>{url}</url_produit>
   <url_image>{image}</url_image>
   <description>{short_description}</description>
   <frais_de_livraison>90</frais_de_livraison>
   <D3E>0</D3E>
   <disponibilite>{is_in_stock?[0]:[1]} </disponibilite>
   <delai_de_livraison>5 jours</delai_de_livraison>
   <? if({is_special_price?[1]:[0]}) return \'<prix_barre currency=\"EUR\">{normal_price}</prix_barre>\';?>
   <type_promotion>{is_special_price?[1]:[0]}</type_promotion>
   <occasion>0</occasion>
   <devise>EUR</devise>
</product>
','</catalogue>',';','','',1)
, (30,'twenga',3,'/feeds/',1,'2011-02-04 17:21:22',1,1,'{\"header\":[\"product_url\", \"designation\", \"price\", \"category\", \"image_url\", \"description\", \"merchant_id\", \"in_stock\", \"Stock_detail\", \"condition\", \"product_type\"]}','{\"product\":[\"{url}\", \"{meta_title}\", \"{price}\", \"{categories}\", \"{image}\", \"{short_description}\", \"{sku}\", \"{is_in_stock ? [Y]:[N]}\", \"{qty}\", \"0\", \"1\"]}','',';','\"','',1)
, (31,'kelkoo',1,'/feeds/',1,'2011-02-04 17:22:01',1,1,'<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
<products>','<product>
   <id>{sku}</id>
   <model>{meta_title}</model>
   <description>{short_description,[180]}</description>
   <price>{price}</price>
   <url>{url}</url>
   <merchantcat>{categories}</merchantcat>
   <image>{image}</image>
   <used>neuf</used>
   <availability>{is_in_stock?[1]:[4]}</availability>
   <deliveryprice>90.00</deliveryprice>
   <deliverytime>Sous 5 jours</deliverytime>
   <pricenorebate>{normal_price}</pricenorebate>
   <percentagepromo><? return round(100-({special_price}*100/{normal_price}) ); ?></percentagepromo>
   <promostart><? return date(\"Y-m-d\",time()); ?></promostart>
   <promoend><? return date(\"Y-m-d\",time()+604800); ?></promoend>
</product>
','</products>','\t','','',0)
, (33,'shopping_com',1,'/feeds/',1,'2011-02-07 09:05:42',1,0,'<Products>','	<Product>			
		<Merchant_SKU>{sku}</Merchant_SKU>		
		<MPN></MPN>		
		<UPC></UPC>		
		<EAN></EAN>		
		<ISBN></ISBN>		
		<Manufacturer>{manufacturer}</Manufacturer>		
		<Product_Name>{name}</Product_Name>		
		<Product_URL>{url}</Product_URL> 		
		<Mobile_URL></Mobile_URL> 		
		<Current_Price>{price}</Current_Price> 		
		<Original_Price>{normal_price}</Original_Price> 		
		<Category_ID></Category_ID>		
		<Category_Name>{categories}</Category_Name>		
		<Sub-category_Name></Sub-category_Name>		
		<Parent_SKU></Parent_SKU>		
		<Parent_Name></Parent_Name>		
		<Product_Description>{short_description}</Product_Description>		
		<Stock_Description></Stock_Description>		
		<Product_Bullet_Point_1></Product_Bullet_Point_1>		
		<Product_Bullet_Point_2></Product_Bullet_Point_2>		
		<Product_Bullet_Point_3></Product_Bullet_Point_3>		
		<Product_Bullet_Point_4></Product_Bullet_Point_4>		
		<Product_Bullet_Point_5></Product_Bullet_Point_5>		
		<Image_URL>{image}</Image_URL>		
		<Alternative_Image_URL_1>{image,[1]}</Alternative_Image_URL_1>		
		<Alternative_Image_URL_2>{image,[2]}</Alternative_Image_URL_2>		
		<Alternative_Image_URL_3>{image,[3]}</Alternative_Image_URL_3>		
		<Alternative_Image_URL_4>{image,[4]}</Alternative_Image_URL_4>		
		<Alternative_Image_URL_5>{image,[5]}</Alternative_Image_URL_5>		
		<Product_Type></Product_Type>		
		<Style></Style>		
		<Condition>Neuf</Condition>		
		<Gender></Gender>		
		<Department></Department>		
		<Age_Range></Age_Range>		
		<Color>Noir/Blanc</Color>		
		<Material></Material>		
		<Format></Format>		
		<Team></Team>		
		<League></League>		
		<Fan_Gear_Type></Fan_Gear_Type>		
		<Software_Platform></Software_Platform>		
		<Software_Type></Software_Type>		
		<Watch_Display_Type></Watch_Display_Type>		
		<Cell_Phone_Type></Cell_Phone_Type>		
		<Cell_Phone_Service_Provider></Cell_Phone_Service_Provider>		
		<Cell_Phone_Plan_Type></Cell_Phone_Plan_Type>		
		<Usage_Profile></Usage_Profile>		
		<Size>{dimension}</Size>		
		<Size_Unit_of_Measure></Size_Unit_of_Measure>		
		<Product_Length></Product_Length>		
		<Length_Unit_of_Measure></Length_Unit_of_Measure>		
		<Product_Width></Product_Width >		
		<Width_Unit_of_Measure></Width_Unit_of_Measure>		
		<Product_Height></Product_Height>		
		<Height_Unit_of_Measure></Height_Unit_of_Measure>		
		<Product_Weight></Product_Weight>		
		<Weight_Unit_of_Measure></Weight_Unit_of_Measure>		
		<Unit_Price></Unit_Price>		
		<Top_Seller_Rank></Top_Seller_Rank>		
		<Product_Launch_Date></Product_Launch_Date>		
		<Stock_Availability></Stock_Availability>		
		<Shipping_Rate></Shipping_Rate>		
		<Shipping_Weight></Shipping_Weight>		
		<Estimated_Ship_Date></Estimated_Ship_Date>		
		<Coupon_Code></Coupon_Code>		
		<Coupon_Code_Description></Coupon_Code_Description>		
		<Merchandising_Type></Merchandising_Type>		
		<Bundle>Non</Bundle>		
		<Related_Products></Related_Products>		
	</Product>				','</Products>	',';','','',1);



");

$installer->endSetup();