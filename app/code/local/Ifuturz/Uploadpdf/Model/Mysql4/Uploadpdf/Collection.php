<?php
class Ifuturz_Uploadpdf_Model_Mysql4_Uploadpdf_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('uploadpdf/uploadpdf');
	}
	/*public function getPublishernameArray()
    {
        $options = array();
		$this->addOrder('publisher_name', 'asc');
        foreach ($this as $item) {
            $options[] = array(
               'value' => $item->getPublisherId(),
               'label' => $item->getPublisherName()
            );
        }
        if (count($options)>0) {
            array_unshift($options, array('title'=>null, 'value'=>'0', 'label'=>Mage::helper('publisher')->__('-- Please select --')));
        }
        return $options;
    }*/
	
}