<?php
 
class Ifuturz_Uploadpdf_Model_Observer
{
    /**
     * Flag to stop observer executing more than once
     *
     * @var static bool
     */
    static protected $_singletonFlag = false;
 
    /**
     * This method will run when the product is saved from the Magento Admin
     * Use this function to update the product model, process the
     * data or anything you like
     *
     * @param Varien_Event_Observer $observer
     */
    public function saveProductTabData(Varien_Event_Observer $observer)
    {
		//echo "hello";die;
        if (!self::$_singletonFlag) {
            self::$_singletonFlag = true;
 
            $product = $observer->getEvent()->getProduct();
			//echo "<pre>"; print_r($product);die;
 			$productid = $product->getId();
			
            try {
                /**
                 * Perform any actions you want here
                 *
                 */
				 //$id = $this->_getRequest()->getParam('id');	
				 //$countcolorid = $this->_getRequest()->getParam('hidden');
				 
				 $write = Mage::getSingleton('core/resource')->getConnection('core_write');
				 $collection = array();
				 $data1 = Mage::getModel('uploadpdf/uploadpdf')->getCollection();
				 foreach($data1 as $cd)
				 {
					 $collection[] = $cd['pdf_file'];
				 }
				 //echo "<pre>";print_r($collection);die; 
				$det_edit_id = $this->_getRequest()->getParam('editid');
				//unlink("/pdfs/test3.pdf");die;
				
				if ($data = $this->_getRequest()->getPost())
				{
					//echo "<pre>"; print_r($data);die;
					$pdfname = $data['pdf_name'];
					if(isset($_FILES['upload_pdf']['name']) && $_FILES['upload_pdf']['name'] != '') {
						
					//this way the name is saved in DB
					 $data['upload_pdf'] = $_FILES['upload_pdf']['name'];
					 $front = $data['upload_pdf'];
					 
					 //$imgPath = Mage::getBaseUrl('media')."pdffiles/uploadedfiles/".$front;
					 $imgPath1 = Mage::getBaseUrl()."pdfs/".$front;
					 $imgPath = str_replace("/index.php","",$imgPath1);//die;
					 $data['pdf_path'] = '<img src="'.$imgPath.'" border="0" />';
				    
					}
					}
								
				 $collection = array();
				 $data1 = Mage::getModel('uploadpdf/uploadpdf')->getCollection();
				 foreach($data1 as $cd)
				 {
					 $collection[] = $cd['pdf_file'];
				 } 
				 //echo "<pre>";print_r($collection);die;
				 $cpid=Mage::registry('current_product')->getId(); 
				//$uploadpdf = "media/pdffiles/uploadedfiles/";
				$uploadpdf = "pdfs/";
				
			/*------------------------------ Multiple DELETE PDF CODE -------------------------------------*/	
					
			if(!empty($_POST['chkdelete'])) { 	
			foreach($_POST['chkdelete'] as $checkdel) {
					//echo $checkdel;
					
					$readresult = $write->query("SELECT pdf_file FROM ifuturz_uploadpdf WHERE pdf_id ='$checkdel'");
					$entityid 	= $readresult->fetch();
					$delfilef 	= $entityid['pdf_file'];
					
					unlink("pdfs/".$delfilef);
					
					$sql1del = "DELETE FROM `ifuturz_uploadpdf` WHERE pdf_id = '$checkdel'";
					$write->query($sql1del);
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('uploadpdf')->__('PDF(s) deleted successfully.'));
			}
			//die;
					
			if($det_edit_id == '')
			{
				
			/*------------------------------ ADD PDF CODE -------------------------------------*/	
					
				if($front != "")
				{
					
					if(file_exists($uploadpdf.$front))
						{
							Mage::getSingleton('adminhtml/session')->addError(Mage::helper('uploadpdf')->__('PDF file already exists!!'));
						}
						else
						{
							$ext = end(explode(".", $front));
							
							if(($ext == "pdf") || ($ext == "PDF"))
							{
								$pdftempfile = $_FILES['upload_pdf']['tmp_name'];
								//echo $pdftempfile."<br>".$uploadpdf.$front;
								move_uploaded_file($pdftempfile,$uploadpdf.$front);
								
								if(!in_array($front,$collection))
								{
									$sql1 = "INSERT INTO `ifuturz_uploadpdf` (`product_id`,`pdf_file`,`pdfname`,`pdf_path`) VALUES ('$productid','$front','$pdfname','$imgPath' )";
									$write->query($sql1);	
									Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('uploadpdf')->__('PDF uploaded successfully.'));
								}
							}
							else
							{
								Mage::getSingleton('adminhtml/session')->addError(Mage::helper('uploadpdf')->__('Only PDF file is allowed.!'));
							}
						}
					}
                $product->save();
			}
			else
			{
				//die('hello');
			/*------------------------------ UPDATE PDF CODE -------------------------------------*/	
			
				$readresultn = $write->query("SELECT pdf_file FROM ifuturz_uploadpdf WHERE pdf_id ='$det_edit_id'");
				$entityidn 	= $readresultn->fetch();
				$delfilefn 	= $entityidn['pdf_file'];
				
				if($front == '')
				{
					$front = $delfilefn;
				}
				
				if($front != $delfilefn)
				{
					unlink("pdfs/".$delfilefn);
				}
				
				if((file_exists($uploadpdf.$front)) && ($front != $delfilefn))
					{
						Mage::getSingleton('adminhtml/session')->addError(Mage::helper('uploadpdf')->__('PDF file already exists!!'));
					}
					else
					{
						if($front == '')
						{
							$front = $delfilefn;
						}
						
						$ext = end(explode(".", $front));
						
						if(($ext == "pdf") || ($ext == "PDF"))
						{
							$pdftempfile = $_FILES['upload_pdf']['tmp_name'];
							//echo $pdftempfile."<br>".$uploadpdf.$front;die;
							move_uploaded_file($pdftempfile,$uploadpdf.$front);
							
							$imgUpPath1 = Mage::getBaseUrl()."pdfs/".$front;
							$imgUpPath = str_replace("/index.php","",$imgUpPath1);
														
							$sql1_edit = "Update ifuturz_uploadpdf SET pdf_file = '$front',pdfname = '$pdfname',pdf_path = '$imgUpPath' WHERE pdf_id='$det_edit_id'"; 
							$write->query($sql1_edit);	
							Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('uploadpdf')->__('PDF updated successfully.'));
						}
						else
						{
							Mage::getSingleton('adminhtml/session')->addError(Mage::helper('uploadpdf')->__('Only PDF file is allowed.!'));
						}
					}
                $product->save();
				
			}
			
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
    }
 
    /**
     * Retrieve the product model
     *
     * @return Mage_Catalog_Model_Product $product
     */
    public function getProduct()
    {
        return Mage::registry('product');
    }
 
    /**
     * Shortcut to getRequest
     *
     */
    protected function _getRequest()
    {
        return Mage::app()->getRequest();
    }
	
	public function checkInstallation($observer)
    {		
		$read = Mage::getSingleton('core/resource')->getConnection('core_read');
		$sql ="SELECT * FROM `uploadpdf_lck` WHERE flag='LCK' AND value='1'";
		$data = $read->fetchAll($sql);
		if(count($data)==1)
		{
		
			$admindata = $read->fetchAll("SELECT email FROM admin_user WHERE username='admin'");
	
			$storename = Mage::getStoreConfig('general/store_information/name');
			$storephone = Mage::getStoreConfig('general/store_information/phone');
			$store_address = Mage::getStoreConfig('general/store_information/address');
			$secureurl = Mage::getStoreConfig('web/unsecure/base_url');
			$unsecureurl = Mage::getStoreConfig('web/secure/base_url');			
			$sendername = Mage::getStoreConfig('trans_email/ident_general/name');
			$general_email = Mage::getStoreConfig('trans_email/ident_general/email');
			$admin_email = $admindata[0]['email'];
			
			$body = "Extension <b>'Uploadpdf'</b> is installed to the following detail: <br/><br/> STORE NAME: ".$storename."<br/>STORE PHONE: ".$storephone."<br/>STORE ADDRESS: ".$store_address."<br/>SECURE URL: ".$secureurl."<br/>UNSECURE URL: ".$unsecureurl."<br/>ADMIN EMAIL ADDRESS: ".$admin_email."<br/>GENERAL EMAIL ADDRESS: ".$general_email."";
			
			$mail = Mage::getModel('core/email');
			$mail->setToName('Extension Geek');
			$mail->setToEmail('extension.geek@ifuturz.com');			
			$mail->setBody($body);
			$mail->setSubject('Uploadpdf Extension is installed!!!');
			$mail->setFromEmail($general_email);
			$mail->setFromName($sendername);
			$mail->setType('html');
			try{
				$mail->send();
				$write = Mage::getSingleton('core/resource')->getConnection('core_write');			
				$write->query("update uploadpdf_lck set value='0' where flag='LCK'");
			}
			catch(Exception $e)
			{		
			}
			
		
		} 

    }
}