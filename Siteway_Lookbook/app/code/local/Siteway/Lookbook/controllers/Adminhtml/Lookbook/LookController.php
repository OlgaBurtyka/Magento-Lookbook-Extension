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
 * Look admin controller
 *
 * @category    Siteway
 * @package     Siteway_Lookbook
 * @author      Ultimate Module Creator
 */
class Siteway_Lookbook_Adminhtml_Lookbook_LookController extends Siteway_Lookbook_Controller_Adminhtml_Lookbook
{
    /**
     * init the look
     *
     * @access protected
     * @return Siteway_Lookbook_Model_Look
     */
    protected function _initLook()
    {
        $lookId  = (int) $this->getRequest()->getParam('id');
        $look    = Mage::getModel('siteway_lookbook/look');
        if ($lookId) {
            $look->load($lookId);
        }
        Mage::register('current_look', $look);
        return $look;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('siteway_lookbook')->__('LookBook'))
             ->_title(Mage::helper('siteway_lookbook')->__('Looks'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit look - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $lookId    = $this->getRequest()->getParam('id');
        $look      = $this->_initLook();
        if ($lookId && !$look->getId()) {
            $this->_getSession()->addError(
                Mage::helper('siteway_lookbook')->__('This look no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getLookData(true);
        if (!empty($data)) {
            $look->setData($data);
        }
        Mage::register('look_data', $look);
        $this->loadLayout();
        $this->_title(Mage::helper('siteway_lookbook')->__('LookBook'))
             ->_title(Mage::helper('siteway_lookbook')->__('Looks'));
        if ($look->getId()) {
            $this->_title($look->getDescription());
        } else {
            $this->_title(Mage::helper('siteway_lookbook')->__('Add look'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new look action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save look - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('look')) {
            try {
                $look = $this->_initLook();
                $look->addData($data);
                $imageName = $this->_uploadAndGetName(
                    'image',
                    Mage::helper('siteway_lookbook/look_image')->getImageBaseDir(),
                    $data
                );
                $look->setData('image', $imageName);
                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $look->setProductsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($products));
                }
                $categories = $this->getRequest()->getPost('category_ids', -1);
                if ($categories != -1) {
                    $categories = explode(',', $categories);
                    $categories = array_unique($categories);
                    $look->setCategoriesData($categories);
                }
                $look->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('siteway_lookbook')->__('Look was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $look->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                if (isset($data['image']['value'])) {
                    $data['image'] = $data['image']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setLookData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['image']['value'])) {
                    $data['image'] = $data['image']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('siteway_lookbook')->__('There was a problem saving the look.')
                );
                Mage::getSingleton('adminhtml/session')->setLookData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('siteway_lookbook')->__('Unable to find look to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete look - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $look = Mage::getModel('siteway_lookbook/look');
                $look->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('siteway_lookbook')->__('Look was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('siteway_lookbook')->__('There was an error deleting look.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('siteway_lookbook')->__('Could not find look to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete look - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $lookIds = $this->getRequest()->getParam('look');
        if (!is_array($lookIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('siteway_lookbook')->__('Please select looks to delete.')
            );
        } else {
            try {
                foreach ($lookIds as $lookId) {
                    $look = Mage::getModel('siteway_lookbook/look');
                    $look->setId($lookId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('siteway_lookbook')->__('Total of %d looks were successfully deleted.', count($lookIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('siteway_lookbook')->__('There was an error deleting looks.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massStatusAction()
    {
        $lookIds = $this->getRequest()->getParam('look');
        if (!is_array($lookIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('siteway_lookbook')->__('Please select looks.')
            );
        } else {
            try {
                foreach ($lookIds as $lookId) {
                $look = Mage::getSingleton('siteway_lookbook/look')->load($lookId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d looks were successfully updated.', count($lookIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('siteway_lookbook')->__('There was an error updating looks.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function productsAction()
    {
        $this->_initLook();
        $this->loadLayout();
        $this->getLayout()->getBlock('look.edit.tab.product')
            ->setLookProducts($this->getRequest()->getPost('look_products', null));
        $this->renderLayout();
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function productsgridAction()
    {
        $this->_initLook();
        $this->loadLayout();
        $this->getLayout()->getBlock('look.edit.tab.product')
            ->setLookProducts($this->getRequest()->getPost('look_products', null));
        $this->renderLayout();
    }

    /**
     * get categories action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function categoriesAction()
    {
        $this->_initLook();
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * get child categories action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function categoriesJsonAction()
    {
        $this->_initLook();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('siteway_lookbook/adminhtml_look_edit_tab_categories')
                ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportCsvAction()
    {
        $fileName   = 'look.csv';
        $content    = $this->getLayout()->createBlock('siteway_lookbook/adminhtml_look_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportExcelAction()
    {
        $fileName   = 'look.xls';
        $content    = $this->getLayout()->createBlock('siteway_lookbook/adminhtml_look_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportXmlAction()
    {
        $fileName   = 'look.xml';
        $content    = $this->getLayout()->createBlock('siteway_lookbook/adminhtml_look_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Ultimate Module Creator
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('siteway_lookbook/look');
    }
}
