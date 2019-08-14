<?php

class Ifuturz_Uploadpdf_Adminhtml_UploadpdfController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction()
	{
		$this->loadLayout()
			->_setActiveMenu('uploadpdf')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Add uploadpdf Category Management'), Mage::helper('adminhtml')->__('Add uploadpdf category Management'));
		
		return $this;
	}
	
	public function indexAction() 
	{
		$this->_initAction()
			->renderLayout();
	}
	
	
	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('uploadpdf/uploadpdf')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}
/*			if($id!=0)
			{
				Mage::register('region_reload', $model);	
			}
*/
			Mage::register('uploadpdf_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('uploadpdf');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Add uploadpdf Management'), Mage::helper('adminhtml')->__('Add uploadpdf Management'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Rule News'), Mage::helper('adminhtml')->__('Rule News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('uploadpdf/adminhtml_uploadpdf_edit'))
				->_addLeft($this->getLayout()->createBlock('uploadpdf/adminhtml_uploadpdf_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('uploadpdf')->__('uploadpdf does not exist'));
			$this->_redirect('*/*/');
		}
	}
	
	public function newAction() 
	{
		$this->_forward('edit');
	}
		
		public function saveAction() 
	{
		
	}
 
	
}