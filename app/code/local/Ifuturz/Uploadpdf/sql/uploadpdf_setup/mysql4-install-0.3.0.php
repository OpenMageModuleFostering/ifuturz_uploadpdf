<?php 
$installer = $this;

$installer->startSetup();


$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('ifuturz_uploadpdf')};
CREATE TABLE {$this->getTable('ifuturz_uploadpdf')} (
  `pdf_id` int(11) unsigned NOT NULL auto_increment,
  `product_id` int(11) NULL,
	`pdf_file` varchar(500) NULL,
	`pdfname` varchar(500) NULL,
	`pdf_path` varchar(500) NULL,
  PRIMARY KEY (`pdf_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('uploadpdf_lck')};
CREATE TABLE {$this->getTable('uploadpdf_lck')} ( 	
	`flag` varchar(4),
	`value` ENUM('0','1')  DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `{$installer->getTable('uploadpdf_lck')}` VALUES ('LCK','1');
");

$installer->endSetup(); 

