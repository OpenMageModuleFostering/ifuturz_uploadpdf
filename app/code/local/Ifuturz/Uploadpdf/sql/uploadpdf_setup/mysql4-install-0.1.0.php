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

$installer->endSetup(); 

