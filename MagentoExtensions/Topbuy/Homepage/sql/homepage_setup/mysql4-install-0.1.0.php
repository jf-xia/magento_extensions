<?php
$installer = $this;
$installer->startSetup();
$installer->run("
                DROP TABLE IF EXISTS {$this->getTable('tb_frontbanner')};
		CREATE TABLE {$this->getTable('tb_frontbanner')} (
                    rowid int(11)  unsigned NOT NULL,
                    imageurl varchar(500) NULL,
                    linkurl varchar(500) NULL,
                    alttext varchar(300) NULL,
                    displayfrom datetime NULL,
                    displayto datetime NULL,
                    positiontype int(11) NULL,
                    displaytitle varchar(50) NULL,
                    displaycontent varchar(500) NULL,
                    PRIMARY KEY  (`rowid`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;
                
                DROP TABLE IF EXISTS {$this->getTable('tb_testimonial')};
                CREATE TABLE {$this->getTable('tb_testimonial')} (
                        idtestimonial int(11) unsigned NOT NULL, 
                        subject varchar(500) NULL, 
                        contentbody varchar(2000) NULL, 
                        senddate datetime NULL, 
                        staffid int(11) NULL, 
                        fromstate varchar(10) NULL, 
                        fromname varchar(100) NULL, 
                        idstore int(11) NULL,
                        PRIMARY KEY  (`idtestimonial`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                
                DROP TABLE IF EXISTS {$this->getTable('tb_categoryspecial')};
                CREATE TABLE {$this->getTable('tb_categoryspecial')} (
                    rowid int(11) unsigned NOT NULL, 
                    idparentcategory int(11) NULL, 
                    linkname varchar(500) NULL, 
                    linkhref varchar(500) NULL, 
                    linkflag int(11) NULL, 
                    linkstatus int(11) NULL, 
                    sortby int(11) NULL, 
                    linenumber int(11) NULL, 
                    PRIMARY KEY  (`rowid`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                
                DROP TABLE IF EXISTS {$this->getTable('tb_promotionrecordmag')};
		CREATE TABLE {$this->getTable('tb_promotionrecordmag')} (
                    rowid int(11) unsigned NOT NULL, 
                    sku varchar(30) NULL, 
                    fromdate datetime NULL, 
                    todate datetime NULL, 
                    productdescription varchar(250) NULL, 
                    description varchar(250) NULL, 
                    saleprice decimal(10,2) NULL, 
                    promotionprice decimal(10,2) NULL, 
                    sodStock int(11) NULL, 
                    smallstod int(11) NULL, 
                    displayorder int(11) NULL, 
                    bannerimageurl varchar (300) Null, 
                    bannerlinkurl varchar (300) Null,
                    PRIMARY KEY  (`rowid`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;
                
                DROP TABLE IF EXISTS {$this->getTable('tb_stealoftheday')};
                CREATE TABLE {$this->getTable('tb_stealoftheday')} (
                    rowid int(11) unsigned NOT NULL auto_increment, 
                    idproduct int(11) NULL,
                    promotion_desc varchar(50) NULL,
                    promotion_price decimal(10,2) NULL, 
                    fromdate datetime NULL, 
                    todate datetime NULL,
                    line_order int(11) NULL,
                    max_qty int(11) NULL,
                    PRIMARY KEY  (`rowid`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;
                
                DROP TABLE IF EXISTS {$this->getTable('tb_customervisithistoryrecord')};
                CREATE TABLE {$this->getTable('tb_customervisithistoryrecord')} (
                    rowid int(11) unsigned NOT NULL auto_increment, 
                    idcustomer int(11) NULL,
                    idproduct int(11) NULL,
                    entrydate datetime NULL,
                    sourcetype varchar(550) NULL,
                    updateflag int(11) NULL,
                    topbuy_utm_source varchar(500) NULL,
                    topbuy_utm_medium varchar(500) NULL,
                    topbuy_utm_campaign varchar(500) NULL,
                    topbuy_utm_content varchar(500) NULL,
                    idstore int(11) NULL,
                    tempidcustomer int(11) NULL,
                    PRIMARY KEY  (`rowid`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                
                DROP TABLE IF EXISTS {$this->getTable('tb_categoryfilter')};
                CREATE TABLE {$this->getTable('tb_categoryfilter')} (
                    rowid int(11) unsigned NOT NULL auto_increment, 
                    cf_idcategory int(11) NULL,
                    cf_filtername varchar(50) NULL,
                    PRIMARY KEY  (`rowid`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;
                
                DROP TABLE IF EXISTS {$this->getTable('tb_category_map_type')};
                CREATE TABLE {$this->getTable('tb_category_map_type')} (
                    rowid int(11) unsigned NOT NULL, 
                    id_tbcategory int(11) NULL,
                    id_magcategory int(11) NULL,
                    PRIMARY KEY  (`rowid`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;
                DROP TABLE IF EXISTS {$this->getTable('tb_customer_map_type')};
                CREATE TABLE {$this->getTable('tb_customer_map_type')} (
                    id_tbcustomer int(11) unsigned NOT NULL, 
                    id_magcustomer int(11) NULL,
                    PRIMARY KEY  (`id_tbcustomer`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;
                DROP TABLE IF EXISTS {$this->getTable('tb_order_map_type')};
                CREATE TABLE {$this->getTable('tb_order_map_type')} (
                    id_tborder int(11) unsigned NOT NULL, 
                    id_magorder int(11) NULL,
                    PRIMARY KEY  (`id_tborder`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;

                DROP TABLE IF EXISTS {$this->getTable('tb_supplier')};
                CREATE TABLE {$this->getTable('tb_supplier')} (
                    rowid           int(11) unsigned NOT NULL auto_increment, 
                    customerid      int(11) NULL, 
                    company         varchar(50) NULL,
                    contactname     varchar(50) NULL,
                    email           varchar(50) NULL,
                    phone           varchar(50) NULL,
                    fax             varchar(50) NULL,
                    website         varchar(50) NULL,
                    products        varchar(50) NULL,
                    iscredit        tinyint(1) NULL,
                    iselectronicinf tinyint(1) NULL,
                    isdatafeed      tinyint(1) NULL,
                    isdropship      tinyint(1) NULL,
                    iselectronic    tinyint(1) NULL,
                    comments        varchar(200) NULL,
                    street          varchar(50) NULL,
                    city            varchar(50) NULL,
                    state           varchar(50) NULL,
                    postcode        varchar(50) NULL,
                    country         varchar(50) NULL,
                    PRIMARY KEY  (`rowid`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;
                
                DROP TABLE IF EXISTS {$this->getTable('tb_reseller')};
                CREATE TABLE {$this->getTable('tb_reseller')} (
                    rowid           int(11) unsigned NOT NULL auto_increment, 
                    customerid      int(11) NULL, 
                    company         varchar(50) NULL,
                    abn             varchar(50) NULL,
                    website         varchar(50) NULL,
                    ebayid          varchar(50) NULL,
                    store           varchar(50) NULL,
                    monthlysales    varchar(50) NULL,
                    iproducts       varchar(50) NULL,
                    wherehear       varchar(50) NULL,
                    PRIMARY KEY  (`rowid`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;
                
                DROP TABLE IF EXISTS {$this->getTable('tb_orders_history')};
                CREATE TABLE {$this->getTable('tb_orders_history')} (
                    rowid           int(11) unsigned NOT NULL auto_increment, 
                    id_tborder      int(11) NULL, 
                    orderdate       datetime NULL,
                    id_magcustomer  int(11) NULL, 
                    id_tbcustomer   int(11) NULL, 
                    token_key       varchar(200) NULL, 
                    PRIMARY KEY  (`rowid`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;
                
                DROP TABLE IF EXISTS {$this->getTable('tb_just_bought')};
                CREATE TABLE {$this->getTable('tb_just_bought')} (
                    rowid           int(11) unsigned NOT NULL auto_increment, 
                    product_id      int(11) NULL, 
                    orderdate       datetime NULL,
                    firstname       varchar(200) NULL, 
                    lastname        varchar(200) NULL, 
                    postcode        varchar(200) NULL, 
                    city            varchar(200) NULL, 
                    PRIMARY KEY  (`rowid`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;
                
                DROP TABLE IF EXISTS {$this->getTable('tb_dailyproductupdate')};
                CREATE TABLE {$this->getTable('tb_dailyproductupdate')} (
                    rowid int(11) unsigned NOT NULL auto_increment,
                    idtbproduct int(11) NULL,
                    idmagproduct int(11) NULL,
                    stock int(11) NULL,
                    price decimal(10,2) NULL,
                    productname varchar(300) NULL,
                    active int(11) NULL,
                    weight decimal(10,2) NULL,
                    fixshippingfee decimal(10,2) NULL,
                    capshippingfee decimal(10,2) NULL,
                    shippingtype int(11) NULL,
                    is_freeshipping int(11) NULL,
                    PRIMARY KEY  (`rowid`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;

                DROP TABLE IF EXISTS {$this->getTable('tb_dailyproductupdate')};
                CREATE TABLE {$this->getTable('tb_dailyproductupdate')} (
                    rowid int(11) unsigned NOT NULL auto_increment,
                    idtbproduct int(11) NULL,
                    idmagproduct int(11) NULL,
                    stock int(11) NULL,
                    price decimal(10,2) NULL,
                    listprice decimal(10,2) NULL,
                    productname varchar(300) NULL,
                    active int(11) NULL,
                    weight decimal(10,2) NULL,
                    fixshippingfee decimal(10,2) NULL,
                    capshippingfee decimal(10,2) NULL,
                    freeshipping int(1) NULL,
                    shippingtype int(1) NULL,
                    PRIMARY KEY  (`rowid`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;


");

$installer->endSetup();
                
/*
 * 
        $id_tborder = $orders_history->id_tborder;
        $orderdate = $orders_history->orderdate;
        $id_magcustomer = $orders_history->id_magcustomer;
        $id_tbcustomer = $orders_history->id_tbcustomer;
        $token_key = $orders_history->token_key;

                DROP TABLE IF EXISTS {$this->getTable('tb_promotionbatch')};
                CREATE TABLE {$this->getTable('tb_promotionbatch')} (
                    rowid int(11) unsigned NOT NULL auto_increment,
                    idproduct int(11) NULL,
                    fromdate datetime NULL,
                    todate datetime NULL,
                    promotionvalue decimal NULL,
                    promotiondesc varchar(150) NULL,
                    promotiontype int(11) NULL,
                    updateflag int(11) NULL,
                    staffid int(11) NULL,
                    entrydate datetime NULL,
                    PRIMARY KEY  (`rowid`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;

                DROP TABLE IF EXISTS {$this->getTable('tb_promotion_cross_sale')};
                CREATE TABLE {$this->getTable('tb_promotion_cross_sale')} (
                    rowid int(11) unsigned NOT NULL,
                    idtbproduct_primary int(11) NULL,
                    idmagproduct_primary int(11) NULL,
                    idtbproduct_discount int(11) NULL,
                    idmagproduct_discount int(11) NULL,
                    fromdate datetime NULL,
                    todate datetime NULL,
                    qty_limit int(11) NULL,
                    discount_desc varchar(300) NULL,
                    discount_type varchar(100) NULL,
                    discount_value decimal(10,2) NULL,
                    discount_flag int(1) NULL,
                    PRIMARY KEY  (`rowid`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;

 * 
 * 
 */