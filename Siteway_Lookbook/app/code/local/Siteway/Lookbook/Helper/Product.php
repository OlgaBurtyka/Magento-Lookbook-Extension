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
 * Product helper
 *
 * @category    Siteway
 * @package     Siteway_Lookbook
 * @author      Ultimate Module Creator
 */
class Siteway_Lookbook_Helper_Product extends Siteway_Lookbook_Helper_Data
{

    /**
     * get the selected looks for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author Ultimate Module Creator
     */
    public function getSelectedLooks(Mage_Catalog_Model_Product $product)
    {
        if (!$product->hasSelectedLooks()) {
            $looks = array();
            foreach ($this->getSelectedLooksCollection($product) as $look) {
                $looks[] = $look;
            }
            $product->setSelectedLooks($looks);
        }
        return $product->getData('selected_looks');
    }

    /**
     * get look collection for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return Siteway_Lookbook_Model_Resource_Look_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedLooksCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('siteway_lookbook/look_collection')
            ->addProductFilter($product);
        return $collection;
    }
}
