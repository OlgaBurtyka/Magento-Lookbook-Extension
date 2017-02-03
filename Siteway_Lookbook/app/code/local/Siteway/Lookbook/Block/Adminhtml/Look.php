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
 * Look admin block
 *
 * @category    Siteway
 * @package     Siteway_Lookbook
 * @author      Ultimate Module Creator
 */
class Siteway_Lookbook_Block_Adminhtml_Look extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_look';
        $this->_blockGroup         = 'siteway_lookbook';
        parent::__construct();
        $this->_headerText         = Mage::helper('siteway_lookbook')->__('Look');
        $this->_updateButton('add', 'label', Mage::helper('siteway_lookbook')->__('Add Look'));

    }
}
