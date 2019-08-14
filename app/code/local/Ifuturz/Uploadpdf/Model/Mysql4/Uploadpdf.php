<?php
class Ifuturz_Uploadpdf_Model_Mysql4_Uploadpdf extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the register_id refers to the key field in your database table.
        $this->_init('uploadpdf/uploadpdf', 'pdf_id');
    }
	
}