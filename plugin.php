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
		echo "<h2>Export</h2>";
		echo "<br>Download ZIP file containing a transcription pdf for each item (collection->item). ";
		echo "Provide the number of characters found in the page or file portion of the names.  Example: ####_###.jpg would be 3.";
		echo "<form method=\"get\" action=\"../../export/index/flexible\">";
			echo "Number of characters to strip: <input type=\"number\" name=\"characters\" min=\"1\" max=\"50\"><br>";
			echo "Format: <select name=\"format\"><option value=\"pdf\">PDF</option><option value=\"txt\">TXT</option></select>";
			echo "<input style=\"float:right;\" type=\"submit\" value=\"Submit\">";
			echo "<input type=\"hidden\" value=\"" . $collection->id . "\" name=\"c\">";
		echo "<p><a href=\"../../export/?c=" . $collection->id . "\">Download ZIP file containing a transcription pdf for each file (collection->item->file)</a></p>";
		echo "<p><a href=\"../../export/index/text?c=" . $collection->id . "\">Download ZIP file containing a transcription .txt file for each file (collection->item->file)</a></p>";
		echo "<p><a href=\"../../export/index/list?c=" . $collection->id . "\" target=\"_blank\">View the original file names from this collection (collection->item->file)</a></p>";
    }

}
$exportPlugin = new ExportPlugin();
$exportPlugin->setUp();
