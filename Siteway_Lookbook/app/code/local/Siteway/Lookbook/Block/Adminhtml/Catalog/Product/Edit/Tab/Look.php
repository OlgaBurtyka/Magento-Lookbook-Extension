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
 * Look tab on product edit form
 *
 * @category    Siteway
 * @package     Siteway_Lookbook
 * @author      Ultimate Module Creator
 */
class Siteway_Lookbook_Block_Adminhtml_Catalog_Product_Edit_Tab_Look extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     * @access public
     * @author Ultimate Module Creator
     */

    public function __construct()
    {
        parent::__construct();
        $this->setId('look_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getProduct()->getId()) {
            $this->setDefaultFilter(array('in_looks'=>1));
        }
    }

    /**
     * prepare the look collection
     *
     * @access protected
     * @return Siteway_Lookbook_Block_Adminhtml_Catalog_Product_Edit_Tab_Look
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('siteway_lookbook/look_collection');
        if ($this->getProduct()->getId()) {
            $constraint = 'related.product_id='.$this->getProduct()->getId();
        } else {
            $constraint = 'related.product_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('siteway_lookbook/look_product')),
            'related.look_id=main_table.entity_id AND '.$constraint,
            array('position')
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return Siteway_Lookbook_Block_Adminhtml_Catalog_Product_Edit_Tab_Look
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * prepare the grid columns
     *
     * @access protected
     * @return Siteway_Lookbook_Block_Adminhtml_Catalog_Product_Edit_Tab_Look
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_looks',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_looks',
                'values'=> $this->_getSelectedLooks(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'description',
            array(
                'header' => Mage::helper('siteway_lookbook')->__('Description'),
                'align'  => 'left',
                'index'  => 'description',
                'renderer' => 'siteway_lookbook/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/lookbook_look/edit',
            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('siteway_lookbook')->__('Position'),
                'name'           => 'position',
                'width'          => 60,
                'type'           => 'number',
                'validate_class' => 'validate-number',
                'index'          => 'position',
                'editable'       => true,
            )
        );
        return parent::_prepareColumns();
    }

    /**
     * Retrieve selected looks
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _getSelectedLooks()
    {
        $looks = $this->getProductLooks();
        if (!is_array($looks)) {
            $looks = array_keys($this->getSelectedLooks());
        }
        return $looks;
    }

    /**
     * Retrieve selected looks
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedLooks()
    {
        $looks = array();
        //used helper here in order not to override the product model
        $selected = Mage::helper('siteway_lookbook/product')->getSelectedLooks(Mage::registry('current_product'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $look) {
            $looks[$look->getId()] = array('position' => $look->getPosition());
        }
        return $looks;
    }

    /**
     * get row url
     *
     * @access public
     * @param Siteway_Lookbook_Model_Look
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($item)
    {
        return '#';
    }

    /**
     * get grid url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            '*/*/looksGrid',
            array(
                'id'=>$this->getProduct()->getId()
            )
        );
    }

    /**
     * get the current product
     *
     * @access public
     * @return Mage_Catalog_Model_Product
     * @author Ultimate Module Creator
     */
    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return Siteway_Lookbook_Block_Adminhtml_Catalog_Product_Edit_Tab_Look
     * @author Ultimate Module Creator
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_looks') {
            $lookIds = $this->_getSelectedLooks();
            if (empty($lookIds)) {
                $lookIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$lookIds));
            } else {
                if ($lookIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$lookIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
