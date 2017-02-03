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
 * Look list block
 *
 * @category    Siteway
 * @package     Siteway_Lookbook
 * @author Ultimate Module Creator
 */
class Siteway_Lookbook_Block_Look_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $looks = Mage::getResourceModel('siteway_lookbook/look_collection')
                         ->addFieldToFilter('status', 1);
        $looks->setOrder('description', 'asc');
        $this->setLooks($looks);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Siteway_Lookbook_Block_Look_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'siteway_lookbook.look.html.pager'
        )
        ->setCollection($this->getLooks());
        $this->setChild('pager', $pager);
        $this->getLooks()->load();
        return $this;
    }

    /**
     * get the pager html
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
