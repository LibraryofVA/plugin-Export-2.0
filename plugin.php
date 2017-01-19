<?php
class ExportPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
		'admin_collections_show'
	);
 	
	/**
	* Hook into adminAdminCollectionsShow
	*
	* @param $collection
	*/
	public function hookAdminCollectionsShow($args) {
    	$collection = $args['collection'];
		echo "<br /><p><a href=\"../../export/index/item?c=" . $collection->id . "\">Download ZIP file containing a transcription pdf for each item (collection->item)</a></p>";
		echo "<p><a href=\"../../export/index/list?c=" . $collection->id . "\" target=\"_blank\">View the original file names from this collection (collection->item->file)</a></p>";
    }
	
}
$exportPlugin = new ExportPlugin();
$exportPlugin->setUp();
