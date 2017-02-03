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
 * Look admin edit tabs
 *
 * @category    Siteway
 * @package     Siteway_Lookbook
 * @author      Ultimate Module Creator
 */
class Siteway_Lookbook_Block_Adminhtml_Look_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('look_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('siteway_lookbook')->__('Look'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Siteway_Lookbook_Block_Adminhtml_Look_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_look',
            array(
                'label'   => Mage::helper('siteway_lookbook')->__('Look'),
                'title'   => Mage::helper('siteway_lookbook')->__('Look'),
                'content' => $this->getLayout()->createBlock(
                    'siteway_lookbook/adminhtml_look_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'products',
            array(
                'label' => Mage::helper('siteway_lookbook')->__('Associated products'),
                'url'   => $this->getUrl('*/*/products', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        $this->addTab(
            'categories',
            array(
                'label' => Mage::helper('siteway_lookbook')->__('Associated categories'),
                'url'   => $this->getUrl('*/*/categories', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve look entity
     *
     * @access public
     * @return Siteway_Lookbook_Model_Look
     * @author Ultimate Module Creator
     */
    public function getLook()
    {
        return Mage::registry('current_look');
    }
}
