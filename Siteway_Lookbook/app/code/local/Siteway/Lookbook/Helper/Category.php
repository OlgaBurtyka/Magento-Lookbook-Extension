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
 * Category helper
 *
 * @category    Siteway
 * @package     Siteway_Lookbook
 * @author      Ultimate Module Creator
 */
class Siteway_Lookbook_Helper_Category extends Siteway_Lookbook_Helper_Data
{

    /**
     * get the selected looks for a category
     *
     * @access public
     * @param Mage_Catalog_Model_Category $category
     * @return array()
     * @author Ultimate Module Creator
     */
    public function getSelectedLooks(Mage_Catalog_Model_Category $category)
    {
        if (!$category->hasSelectedLooks()) {
            $looks = array();
            foreach ($this->getSelectedLooksCollection($category) as $look) {
                $looks[] = $look;
            }
            $category->setSelectedLooks($looks);
        }
        return $category->getData('selected_looks');
    }

    /**
     * get look collection for a category
     *
     * @access public
     * @param Mage_Catalog_Model_Category $category
     * @return Siteway_Lookbook_Model_Resource_Look_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedLooksCollection(Mage_Catalog_Model_Category $category)
    {
        $collection = Mage::getResourceSingleton('siteway_lookbook/look_collection')
            ->addCategoryFilter($category);
        return $collection;
    }
}
