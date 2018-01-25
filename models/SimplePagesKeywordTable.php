<?php
/**
 * Simple Pages
 *
 * @copyright Copyright 2008-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * The Simple Pages page table class.
 *
 * @package SimplePages
 */
class SimplePagesKeywordTable extends Omeka_Db_Table
{
    public function addKeywords($keywords) {

    	// Retrieve existing keywords in database
        $existingKeywords = $this->findAll();
        $existingNames = array();
		foreach($existingKeywords as $existingKeyword) {
			$existingNames[] = strtolower($existingKeyword->name);
		}

        $pageKeywords = array();
        foreach($keywords as $key => $keyword) {
            if (!in_array(strtolower($keyword), $existingNames)) {
            	$k = new SimplePagesKeyword;
        		$k->name = $keyword;
        		$k->save();
            }
            $pageKeywords[] = strtolower($keyword);
        }
        
        return $pageKeywords;
    }    
}
