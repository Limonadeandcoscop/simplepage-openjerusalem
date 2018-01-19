<?php
/**
 * Simple Pages Categories
 *
 * @copyright Copyright 2008-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * The Simple Pages Category page record class.
 *
 * @package SimplePagesCategory
 */
class SimplePagesCategory extends Omeka_Record_AbstractRecord implements Zend_Acl_Resource_Interface
{
    public $modified_by_user_id;         
    public $created_by_user_id;         
    public $is_published = 0;         
    public $title;
    public $slug;
    public $text = null;
    public $updated;
    public $inserted;
    public $order = 0;
    public $parent_id = 0;


    /**
     * Validate the form data.
     */
    protected function _validate()
    {        
        if (empty($this->title)) {
            $this->addError('title', __('The page must be given a title.'));
        }        
        
        if (255 < strlen($this->title)) {
            $this->addError('title', __('The title for your page must be 255 characters or less.'));
        }
        
        if (!$this->fieldIsUnique('title')) {
            $this->addError('title', __('The title is already in use by another page. Please choose another.'));
        }
        
        if (trim($this->slug) == '') {
            $this->addError('slug', __('The page must be given a valid slug.'));
        }
        
        if (preg_match('/^\/+$/', $this->slug)) {
            $this->addError('slug', __('The slug for your page must not be a forward slash.'));
        }
        
        if (255 < strlen($this->slug)) {
            $this->addError('slug', __('The slug for your page must be 255 characters or less.'));
        }
        
        if (!$this->fieldIsUnique('slug')) {
            $this->addError('slug', __('The slug is already in use by another page. Please choose another.'));
        }
        
        if (!is_numeric($this->order) || (!(strpos((string)$this->order, '.') === false)) || intval($this->order) < 0) {
            $this->addError('order', __('The order must be an integer greater than or equal to 0.'));
        }
    }
    

    /**
     * Prepare special variables before saving the form.
     */
    protected function beforeSave($args)
    {
        $this->title = trim($this->title);
        // Generate the page slug.
        $this->slug = $this->_generateSlug($this->slug);
        // If the resulting slug is empty, generate it from the page title.
        if (empty($this->slug)) {
            $this->slug = $this->_generateSlug($this->title);
        }
        
        if ($this->order == '') {
            $this->order = 0;
        }
        
        if ($this->parent_id == '') {
            $this->parent_id = 0;
        }
        
        $this->modified_by_user_id = current_user()->id;
    }   


    /**
     * Generate a slug given a seed string.
     * 
     * @param string
     * @return string
     */
    private function _generateSlug($seed)
    {
        $seed = trim($seed);
        $seed = strtolower($seed);
        // Replace spaces with dashes.
        $seed = str_replace(' ', '-', $seed);
        // Remove all but alphanumeric characters, underscores, and dashes.
        return preg_replace('/[^\w\/-]/i', '', $seed);
    }


    public function getRecordUrl($action = 'show')
    {
        if ('show' == $action) {
            return public_url('categories/'.$this->slug);
        }
        return array('module' => 'simple-pages', 'controller' => 'categories', 
                     'action' => $action, 'id' => $this->id);
    }
    

    public function getResourceId()
    {
        return 'SimplePages_Category';
    }

}
