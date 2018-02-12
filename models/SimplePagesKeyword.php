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
class SimplePagesKeyword extends Omeka_Record_AbstractRecord implements Zend_Acl_Resource_Interface
{
    public $name;
    public $inserted;
    
    public function getUrl() {
        return url('tags/'.urlencode($this->name));
    }    

    public function getPages() {

    	$params['keyword_id'] = $this->id;
        $pages = get_db()->getTable('SimplePagesPageKeyword')->findBy($params);
        $res  = array();
        foreach($pages as $page) {
        	$res[] = get_record_by_id('SimplePagesPage', $page->page_id);
        }
        return $res;
    }

    protected function beforeDelete()
    {
        $params['keyword_id'] = $this->id;
        $pages = get_db()->getTable('SimplePagesPageKeyword')->findBy($params);
        if (count($pages)) {
            foreach($pages as $page) {
                $page->delete();
            }
        }
    }


    public function getRecordUrl($action = 'show')
    {
        if ('show' == $action) {
            return public_url('tags/'.$this->name);
        }
        return array('module' => 'simple-pages', 'controller' => 'categories', 
                     'action' => $action, 'id' => $this->id);
    }    

    public function getResourceId()
    {
        return 'SimplePages_Tag';
    }

}
