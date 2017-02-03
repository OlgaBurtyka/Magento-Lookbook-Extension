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
 * Admin search model
 *
 * @category    Siteway
 * @package     Siteway_Lookbook
 * @author      Ultimate Module Creator
 */
class Siteway_Lookbook_Model_Adminhtml_Search_Look extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return Siteway_Lookbook_Model_Adminhtml_Search_Look
     * @author Ultimate Module Creator
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('siteway_lookbook/look_collection')
            ->addFieldToFilter('description', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $look) {
            $arr[] = array(
                'id'          => 'look/1/'.$look->getId(),
                'type'        => Mage::helper('siteway_lookbook')->__('Look'),
                'name'        => $look->getDescription(),
                'description' => $look->getDescription(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/lookbook_look/edit',
                    array('id'=>$look->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
