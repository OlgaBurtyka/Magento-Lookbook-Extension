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
 * Look - Category relation resource model collection
 *
 * @category    Siteway
 * @package     Siteway_Lookbook
 * @author      Ultimate Module Creator
 */
class Siteway_Lookbook_Model_Resource_Look_Category_Collection extends Mage_Catalog_Model_Resource_Category_Collection
{
    /**
     * remember if fields have been joined
     *
     * @var bool
     */
    protected $_joinedFields = false;

    /**
     * join the link table
     *
     * @access public
     * @return Siteway_Lookbook_Model_Resource_Look_Category_Collection
     * @author Ultimate Module Creator
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('siteway_lookbook/look_category')),
                'related.category_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add look filter
     *
     * @access public
     * @param Siteway_Lookbook_Model_Look | int $look
     * @return Siteway_Lookbook_Model_Resource_Look_Category_Collection
     * @author Ultimate Module Creator
     */
    public function addLookFilter($look)
    {
        if ($look instanceof Siteway_Lookbook_Model_Look) {
            $look = $look->getId();
        }
        if (!$this->_joinedFields) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.look_id = ?', $look);
        return $this;
    }
}
