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
 * Look admin edit form
 *
 * @category    Siteway
 * @package     Siteway_Lookbook
 * @author      Ultimate Module Creator
 */
class Siteway_Lookbook_Block_Adminhtml_Look_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'siteway_lookbook';
        $this->_controller = 'adminhtml_look';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('siteway_lookbook')->__('Save Look')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('siteway_lookbook')->__('Delete Look')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('siteway_lookbook')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_look') && Mage::registry('current_look')->getId()) {
            return Mage::helper('siteway_lookbook')->__(
                "Edit Look '%s'",
                $this->escapeHtml(Mage::registry('current_look')->getDescription())
            );
        } else {
            return Mage::helper('siteway_lookbook')->__('Add Look');
        }
    }
}
