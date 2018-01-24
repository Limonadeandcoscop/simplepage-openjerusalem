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
class SimplePages_CategoriesController extends Omeka_Controller_AbstractActionController
{    
    public function init()
    {
        $this->_helper->db->setDefaultModelName('SimplePagesCategory');
    }
    
        
    public function showAction()
    {
        parent::showAction();   

        $route = $this->getFrontController()->getRouter()->getCurrentRouteName();
        $isHomePage = ($route == Omeka_Application_Resource_Router::HOMEPAGE_ROUTE_NAME);

        $category = $this->view->simple_pages_category;
        //$this->view->parents    = $category->getParents($category->id, 1);
        //$this->view->children   = $category->getCategories($category->id, 1);
        $this->view->tree       = $category->getTree($category->id);

        $this->view->pages = $category->getPages();

        $this->view->is_home_page = $isHomePage;
    }

    public function addAction()
    {
        // Create a new page.
        $category = new SimplePagesCategory;
        $category->created_by_user_id = current_user()->id;        
        $category->order = 1;
        $form = $this->_getForm($category);
        $this->view->form = $form;
        $this->_processPageForm($category, $form, 'add');
    }
    
    public function editAction()
    {
        // Get the requested page.
        $category = $this->_helper->db->findById();
        $form = $this->_getForm($category);
        $this->view->form = $form;
        $this->_processPageForm($category, $form, 'edit');        
    }
    
    protected function _getForm($category = null)
    { 
        $formOptions = array('type' => 'simple_pages_category', 'hasPublicPage' => true);
        if ($category && $category->exists()) {
            $formOptions['record'] = $category;
        }
      
        $form = new Omeka_Form_Admin($formOptions);

        $form->addElementToEditGroup(
            'text', 'title',
            array(
                'id' => 'simple-pages-title',
                'value' => $category->title,
                'label' => __('Title'),
                'description' => __('Name and heading for the category (required)'),
                'required' => true
            )
        );
        

        $form->addElementToEditGroup(
            'text', 'slug',
            array(
                'id' => 'simple-pages-slug',
                'value' => $category->slug,
                'label' => __('Slug'),
                'description' => __(
                    'The slug is the part of the URL for this category. A slug '
                    . 'will be created automatically from the title if one is '
                    . 'not entered. Letters, numbers, underscores, dashes, and '
                    . 'forward slashes are allowed.'
                )
            )
        );
        
        $form->addElementToEditGroup(
            'select', 'parent_id',
            array(
                'id' => 'simple-pages-parent-id',
                'multiOptions' => simple_pages_categories_get_parent_options($category),
                'value' => $category->parent_id,
                'label' => __('Parent'),
                'description' => __('The parent category')
            )
        );

        $form->addElementToEditGroup(
            'textarea', 'text',
            array('id' => 'simple-pages-text',
                'cols'  => 50,
                'rows'  => 10,
                'value' => $category->text,
                'label' => __('Text'),
                'description' => __(
                    'Add description content for category.'
                )
            )
        );
        
        
/*
        $form->addElementToSaveGroup(
            'text', 'order',
            array(
                'value' => $category->order,
                'label' => __('Order'),
                'description' => __(
                    'The order of the page relative to the other pages with '
                    . 'the same parent'
                )
            )
        );
     
        $form->addElementToSaveGroup(
            'checkbox', 'is_published',
            array(
                'id' => 'simple_pages_is_published',
                'values' => array(1, 0),
                'checked' => $category->is_published,
                'label' => __('Publish this page?'),
                'description' => __('Checking this box will make the page public')
            )
        );
        */

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
                        $this->_helper->flashMessenger(__('The category "%s" has been added.', $category->title), 'success');
                    } else if ('edit' == $action) {
                        $this->_helper->flashMessenger(__('The category "%s" has been edited.', $category->title), 'success');
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
        return __('The category "%s" has been deleted.', $record->title);
    }

    public function browseAction() 
    {
        parent::browseAction();

        $pluralName = $this->view->pluralize($this->_helper->db->getDefaultModelName());
        $c = new SimplePagesCategory;
        $this->view->$pluralName = $c->getCategories();

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
