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
 * Look edit form tab
 *
 * @category    Siteway
 * @package     Siteway_Lookbook
 * @author      Ultimate Module Creator
 */
class Siteway_Lookbook_Block_Adminhtml_Look_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Siteway_Lookbook_Block_Adminhtml_Look_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('look_');
        $form->setFieldNameSuffix('look');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'look_form',
            array('legend' => Mage::helper('siteway_lookbook')->__('Look'))
        );
        $fieldset->addType(
            'image',
            Mage::getConfig()->getBlockClassName('siteway_lookbook/adminhtml_look_helper_image')
        );

        $fieldset->addField(
            'image',
            'image',
            array(
                'label' => Mage::helper('siteway_lookbook')->__('Image'),
                'name'  => 'image',
                'note'	=> $this->__('Please Select Image'),

           )
        );

        $fieldset->addField(
            'description',
            'text',
            array(
                'label' => Mage::helper('siteway_lookbook')->__('Description'),
                'name'  => 'description',
                'note'	=> $this->__('Please Enter Short Description of Image'),
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'url',
            'text',
            array(
                'label' => Mage::helper('siteway_lookbook')->__('Link'),
                'name'  => 'url',
                'note'	=> $this->__('Custom or External Link'),

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('siteway_lookbook')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('siteway_lookbook')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('siteway_lookbook')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_look')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getLookData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getLookData());
            Mage::getSingleton('adminhtml/session')->setLookData(null);
        } elseif (Mage::registry('current_look')) {
            $formValues = array_merge($formValues, Mage::registry('current_look')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
