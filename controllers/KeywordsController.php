<?php
/**
 * Simple Pages
 *
 * @copyright Copyright 2008-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * The Simple Pages index controller class.
 *
 * @package SimplePages
 */
class SimplePages_KeywordsController extends Omeka_Controller_AbstractActionController
{    
    public function init()
    {
        $this->_helper->db->setDefaultModelName('SimplePagesKeyword');
    }
    
        
    public function addAction()
    {
        // Create a new keyword.
        $keyword = new SimplePagesKeyword;
        $form = $this->_getForm($keyword);
        $this->view->form = $form;
        $this->_processPageForm($keyword, $form, 'add');
    }
    
    public function editAction()
    {
        // Get the requested page.
        $category = $this->_helper->db->findById();
        $form = $this->_getForm($category);
        $this->view->form = $form;
        $this->_processPageForm($category, $form, 'edit');        
    }
    
    protected function _getForm($keyword = null)
    { 
        $formOptions = array('type' => 'simple_pages_keywords', 'hasPublicPage' => true);

      
        $form = new Omeka_Form_Admin($formOptions);

        $form->addElementToEditGroup(
            'text', 'name',
            array(
                'id' => 'simple-pages-title',
                'value' => $keyword->name,
                'label' => __('Name'),
                'description' => __('Name of the tag (required)'),
                'required' => true
            )
        );
        

        if (class_exists('Omeka_Form_Element_SessionCsrfToken')) {
            $form->addElement('sessionCsrfToken', 'csrf_token');
        }
        
        return $form;
    }
    
    /**
     * Process the page edit and edit forms.
     */
    private function _processPageForm($category, $form, $action)
    {
        // Set the page object to the view.
        $this->view->category = $category;

        if ($this->getRequest()->isPost()) {
            if (!$form->isValid($_POST)) {
                $this->_helper->_flashMessenger(__('There was an error on the form. Please try again.'), 'error');
                return;
            }
            try {
                $category->setPostData($_POST);
                if ($category->save()) {
                    if ('add' == $action) {
                        $this->_helper->flashMessenger(__('The tag "%s" has been added.', $keyword->name), 'success');
                    } else if ('edit' == $action) {
                        $this->_helper->flashMessenger(__('The tag "%s" has been edited.', $keyword->name), 'success');
                    }
                    
                    $this->_helper->redirector('browse');
                    return;
                }
            // Catch validation errors.
            } catch (Omeka_Validate_Exception $e) {
                $this->_helper->flashMessenger($e);
            }
        }
    }

    protected function _getDeleteSuccessMessage($record)
    {
        return __('The keyword "%s" has been deleted.', $record->name);
    }

    public function browseAction() 
    {
        parent::browseAction();

        $pluralName = $this->view->pluralize($this->_helper->db->getDefaultModelName());
        $keywords = get_db()->getTable('SimplePagesKeyword')->findAll();

        foreach($keywords as $keyword) {
            $keyword->nb_pages = count($keyword->getPages());
        }

        if ($this->getParam('sort_field') == 'nb_pages') 
        {
            function sortByPagesAsc($a, $b) {
              return strcasecmp($a->nb_pages, $b->nb_pages);
            }
            function sortByPagesDesc($a, $b) {
              return strcasecmp($b->nb_pages, $a->nb_pages);
            }
            if ($this->getParam('sort_dir') == 'a') 
                usort($keywords, 'sortByPagesAsc');            
            else
                usort($keywords, 'sortByPagesDesc');            
        }


        $this->view->$pluralName = $keywords;
    }


    /**
     * Ask for user confirmation before deleting a record.
     * 
     * @uses Omeka_Controller_Action_Helper_Db::findById()
     * @uses self::_getDeleteConfirmMessage()
     */
    public function deleteConfirmAction()
    {
        $isPartial = $this->getRequest()->isXmlHttpRequest();
        $record = $this->_helper->db->findById();
        $form = $this->_getDeleteForm();
        $confirmMessage = $this->_getDeleteConfirmMessage($record);

        $this->view->assign(compact('confirmMessage','record', 'isPartial', 'form'));
        $this->render('common/delete-confirm', null, true);
    }
}
