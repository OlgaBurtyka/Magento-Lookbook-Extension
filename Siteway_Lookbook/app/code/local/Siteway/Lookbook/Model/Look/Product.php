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
 * Look product model
 *
 * @category    Siteway
 * @package     Siteway_Lookbook
 * @author      Ultimate Module Creator
 */
class Siteway_Lookbook_Model_Look_Product extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource
     *
     * @access protected
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        $this->_init('siteway_lookbook/look_product');
    }

    /**
     * Save data for look-product relation
     * @access public
     * @param  Siteway_Lookbook_Model_Look $look
     * @return Siteway_Lookbook_Model_Look_Product
     * @author Ultimate Module Creator
     */
    public function saveLookRelation($look)
    {
        $data = $look->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveLookRelation($look, $data);
        }
        return $this;
    }

    /**
     * get products for look
     *
     * @access public
     * @param Siteway_Lookbook_Model_Look $look
     * @return Siteway_Lookbook_Model_Resource_Look_Product_Collection
     * @author Ultimate Module Creator
     */
    public function getProductCollection($look)
    {
        $collection = Mage::getResourceModel('siteway_lookbook/look_product_collection')
            ->addLookFilter($look);
        return $collection;
    }
}
