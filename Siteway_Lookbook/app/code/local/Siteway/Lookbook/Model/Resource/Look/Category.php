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
 * Look - Categories relation model
 *
 * @category    Siteway
 * @package     Siteway_Lookbook
 * @author      Ultimate Module Creator
 */
class Siteway_Lookbook_Model_Resource_Look_Category extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * initialize resource model
     *
     * @access protected
     * @return void
     * @see Mage_Core_Model_Resource_Abstract::_construct()
     * @author Ultimate Module Creator
     */
    protected function  _construct()
    {
        $this->_init('siteway_lookbook/look_category', 'rel_id');
    }

    /**
     * Save look - category relations
     *
     * @access public
     * @param Siteway_Lookbook_Model_Look $look
     * @param array $data
     * @return Siteway_Lookbook_Model_Resource_Look_Category
     * @author Ultimate Module Creator
     */
    public function saveLookRelation($look, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('look_id=?', $look->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $categoryId) {
            if (!empty($categoryId)) {
                $insert = array(
                    'look_id' => $look->getId(),
                    'category_id'   => $categoryId,
                    'position'      => 1
                );
                $this->_getWriteAdapter()->insertOnDuplicate($this->getMainTable(), $insert, array_keys($insert));
            }
        }
        return $this;
    }

    /**
     * Save  category - look relations
     *
     * @access public
     * @param Mage_Catalog_Model_Category $category
     * @param array $data
     * @return Siteway_Lookbook_Model_Resource_Look_Category
     * @author Ultimate Module Creator
     */
    public function saveCategoryRelation($category, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('category_id=?', $category->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $lookId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'look_id' => $lookId,
                    'category_id'   => $category->getId(),
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }
}
