<?php
/**
 * Siteway_Lookbook extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Siteway
 * @package        Siteway_Lookbook
 * @copyright      Copyright (c) 2017
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Adminhtml observer
 *
 * @category    Siteway
 * @package     Siteway_Lookbook
 * @author      Ultimate Module Creator
 */
class Siteway_Lookbook_Model_Adminhtml_Observer
{
    /**
     * check if tab can be added
     *
     * @access protected
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     * @author Ultimate Module Creator
     */
    protected function _canAddTab($product)
    {
        if ($product->getId()) {
            return true;
        }
        if (!$product->getAttributeSetId()) {
            return false;
        }
        $request = Mage::app()->getRequest();
        if ($request->getParam('type') == 'configurable') {
            if ($request->getParam('attributes')) {
                return true;
            }
        }
        return false;
    }

    /**
     * add the look tab to products
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Siteway_Lookbook_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function addProductLookBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                'looks',
                array(
                    'label' => Mage::helper('siteway_lookbook')->__('Looks'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/lookbook_look_catalog_product/looks',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save look - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Siteway_Lookbook_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function saveProductLookData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('looks', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $product = Mage::registry('product');
            $lookProduct = Mage::getResourceSingleton('siteway_lookbook/look_product')
                ->saveProductRelation($product, $post);
        }
        return $this;
    }
    /**
     * add the look tab to categories
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Siteway_Lookbook_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function addCategoryLookBlock($observer)
    {
        $tabs = $observer->getEvent()->getTabs();
        $content = $tabs->getLayout()->createBlock(
            'siteway_lookbook/adminhtml_catalog_category_tab_look',
            'category.look.grid'
        )->toHtml();
        $serializer = $tabs->getLayout()->createBlock(
            'adminhtml/widget_grid_serializer',
            'category.look.grid.serializer'
        );
        $serializer->initSerializerBlock(
            'category.look.grid',
            'getSelectedLooks',
            'looks',
            'category_looks'
        );
        $serializer->addColumnInputName('position');
        $content .= $serializer->toHtml();
        $tabs->addTab(
            'look',
            array(
                'label'   => Mage::helper('siteway_lookbook')->__('Looks'),
                'content' => $content,
            )
        );
        return $this;
    }

    /**
     * save look - category relation
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Siteway_Lookbook_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function saveCategoryLookData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('looks', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $category = Mage::registry('category');
            $lookCategory = Mage::getResourceSingleton('siteway_lookbook/look_category')
                ->saveCategoryRelation($category, $post);
        }
        return $this;
    }
}
