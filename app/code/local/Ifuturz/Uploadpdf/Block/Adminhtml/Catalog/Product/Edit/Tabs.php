<?php
 require_once 'Mage/Adminhtml/Block/Catalog/Product/Edit/Tabs.php';
class Ifuturz_Uploadpdf_Block_Adminhtml_Catalog_Product_Edit_Tabs
extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs
{
    protected function _prepareLayout()
    {
        $product = $this->getProduct();

        if (!($setId = $product->getAttributeSetId())) {
            $setId = $this->getRequest()->getParam('set', null);
        }

        if ($setId) {
            $groupCollection = Mage::getResourceModel('eav/entity_attribute_group_collection')
                ->setAttributeSetFilter($setId)
                ->setSortOrder()
                ->load();
			 /*$groupCollection = Mage::getResourceModel('eav/entity_attribute_group_collection')
			->setAttributeSetFilter($setId)
			->load();*/

            foreach ($groupCollection as $group) {
                $attributes = $product->getAttributes($group->getId(), true);
                // do not add groups without attributes

                foreach ($attributes as $key => $attribute) {
                    if( !$attribute->getIsVisible() ) {
                        unset($attributes[$key]);
                    }
                }

                if (count($attributes)==0) {
                    continue;
                }

                $this->addTab('group_'.$group->getId(), array(
                    'label'     => Mage::helper('catalog')->__($group->getAttributeGroupName()),
                    'content'   => $this->_translateHtml($this->getLayout()->createBlock($this->getAttributeTabBlock(),
                        'adminhtml.catalog.product.edit.tab.attributes')->setGroup($group)
                            ->setGroupAttributes($attributes)
                            ->toHtml()),
                ));
            }
			/* ------------------- Added code by R ----------------------- */
			
			$geteditid = $_GET['editid'];
			
			if (Mage::helper('core')->isModuleEnabled('Mage_CatalogInventory')) 
			{
				$this->addTab('inventory', array(
					'label'     => Mage::helper('catalog')->__('Inventory'),
					'content'   => $this->_translateHtml($this->getLayout()
						->createBlock('adminhtml/catalog_product_edit_tab_inventory')->toHtml()),
				));
				
				/* ------------------- START Added code by R ----------------------- */
				if($geteditid != '')
				{
					$this->addTab('Upload PDF', array(
						'label'     => Mage::helper('catalog')->__('Upload PDF'),
						'active'	=> true,
						'content'   => $this->_translateHtml($this->getLayout()
							->createBlock('uploadpdf/adminhtml_catalog_product_edit_tab_uploadpdf')->toHtml()),
					));
				}
				else
				{
					$this->addTab('Upload PDF', array(
						'label'     => Mage::helper('catalog')->__('Upload PDF'),
						'content'   => $this->_translateHtml($this->getLayout()
							->createBlock('uploadpdf/adminhtml_catalog_product_edit_tab_uploadpdf')->toHtml()),
					));
						
				}
				/* ------------------- END Added code by R ----------------------- */	
			}

            /**
             * Don't display website tab for single mode
             */
            if (!Mage::app()->isSingleStoreMode()) {
                $this->addTab('websites', array(
                    'label'     => Mage::helper('catalog')->__('Websites'),
                    'content'   => $this->_translateHtml($this->getLayout()
                        ->createBlock('adminhtml/catalog_product_edit_tab_websites')->toHtml()),
                ));
            }

            $this->addTab('categories', array(
                'label'     => Mage::helper('catalog')->__('Categories'),
                'url'       => $this->getUrl('*/*/categories', array('_current' => true)),
                'class'     => 'ajax',
            ));

            $this->addTab('related', array(
                'label'     => Mage::helper('catalog')->__('Related Products'),
                'url'       => $this->getUrl('*/*/related', array('_current' => true)),
                'class'     => 'ajax',
            ));

            $this->addTab('upsell', array(
                'label'     => Mage::helper('catalog')->__('Up-sells'),
                'url'       => $this->getUrl('*/*/upsell', array('_current' => true)),
                'class'     => 'ajax',
            ));

            $this->addTab('crosssell', array(
                'label'     => Mage::helper('catalog')->__('Cross-sells'),
                'url'       => $this->getUrl('*/*/crosssell', array('_current' => true)),
                'class'     => 'ajax',
            ));
			
			
            $storeId = 0;
            if ($this->getRequest()->getParam('store')) {
                $storeId = Mage::app()->getStore($this->getRequest()->getParam('store'))->getId();
            }

            $alertPriceAllow = Mage::getStoreConfig('catalog/productalert/allow_price');
            $alertStockAllow = Mage::getStoreConfig('catalog/productalert/allow_stock');

            if (($alertPriceAllow || $alertStockAllow) && !$product->isGrouped()) {
                $this->addTab('productalert', array(
                    'label'     => Mage::helper('catalog')->__('Product Alerts'),
                    'content'   => $this->_translateHtml($this->getLayout()
                        ->createBlock('adminhtml/catalog_product_edit_tab_alerts', 'admin.alerts.products')->toHtml())
                ));
            }

            if( $this->getRequest()->getParam('id', false) ) {
                if (Mage::helper('catalog')->isModuleEnabled('Mage_Review')) {
                    if (Mage::getSingleton('admin/session')->isAllowed('admin/catalog/reviews_ratings')){
                        $this->addTab('reviews', array(
                            'label' => Mage::helper('catalog')->__('Product Reviews'),
                            'url'   => $this->getUrl('*/*/reviews', array('_current' => true)),
                            'class' => 'ajax',
                        ));
                    }
                }
                if (Mage::helper('catalog')->isModuleEnabled('Mage_Tag')) {
                    if (Mage::getSingleton('admin/session')->isAllowed('admin/catalog/tag')){
                        $this->addTab('tags', array(
                         'label'     => Mage::helper('catalog')->__('Product Tags'),
                         'url'   => $this->getUrl('*/*/tagGrid', array('_current' => true)),
                         'class' => 'ajax',
                        ));

                        $this->addTab('customers_tags', array(
                            'label'     => Mage::helper('catalog')->__('Customers Tagged Product'),
                            'url'   => $this->getUrl('*/*/tagCustomerGrid', array('_current' => true)),
                            'class' => 'ajax',
                        ));
                    }
                }
				

            }

            /**
             * Do not change this tab id
             * @see Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs_Configurable
             * @see Mage_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tabs
             */
            if (!$product->isGrouped()) {
                $this->addTab('customer_options', array(
                    'label' => Mage::helper('catalog')->__('Custom Options'),
                    'url'   => $this->getUrl('*/*/options', array('_current' => true)),
                    'class' => 'ajax',
                ));
            }

        }
        else {
            $this->addTab('set', array(
                'label'     => Mage::helper('catalog')->__('Settings'),
                'content'   => $this->_translateHtml($this->getLayout()
                    ->createBlock('adminhtml/catalog_product_edit_tab_settings')->toHtml()),
                'active'    => true
            ));
        }
        return parent::_prepareLayout();
    }

    
}