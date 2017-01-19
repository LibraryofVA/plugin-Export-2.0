<?php
class ExportPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
		'admin_collections_show'
	);
 	
	/**
	* Hook into admin_append_to_collections_show_primary
	*
	* @param $collection
	*/
	public function hookAdminCollectionsShow($args) {
      $collection = $args['collection'];
	  echo "<p><a href=\"../../export/index/list?c=" . $collection->id . "\" target=\"_blank\">View the original file names from this collection (collection->item->file)</a></p>";
    }
	
	
	
}
$exportPlugin = new ExportPlugin();
$exportPlugin->setUp();
