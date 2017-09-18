<?php

//get collection from query string
$collectionID = $_GET['c'];

//create empty array to hold files
$arrayOfFiles = array();

$collection_items = get_records('Item',
            array(
                'collection' => $collectionID,
                'sort_field' => 'Dublin Core,Audience',
                'sort_dir' => 'a',
            ),
            999);

set_loop_records('items', $collection_items);
foreach (loop('items') as $item) :
	set_current_record('item', $item);
	if (metadata($item, 'has files')):
		echo metadata('item', array('Dublin Core', 'Title')). "</br>";
		set_loop_records('files', get_current_record('item')->Files);
		foreach(loop('files') as $file) :
			if (metadata($file, array('Scripto', 'Transcription'))) {
				//remove domain and any directory from orginal filename (jpg)
				if (strpos(metadata($file,'original filename'), '/') !== FALSE) {
					$jpgFileName = substr(strrchr(metadata($file,'original filename'), "/"), 1);
				} else {
					$jpgFileName = metadata($file,'original filename');
				}
				//build array of files
				$arrayOfFiles[] = array('id' => metadata($file,'id'), 'of' => $jpgFileName);
			}
		endforeach;

		//sort the array of files
		$arr2 = array_msort($arrayOfFiles, array('of'=>SORT_ASC));

		//print out the files
		foreach ($arr2 as $key => $row) {
			echo $row['of'] . " - <a href=\"" . WEB_ROOT . "/admin/items/show/" . metadata($item,'id') . "\" target=\"_blank\">Item</a><br />";
		}
		$arr2 = array();
		$arrayOfFiles = array();
	endif;
	echo "<br>";
endforeach;

function array_msort($array, $cols) {
    $colarr = array();
    foreach ($cols as $col => $order) {
        $colarr[$col] = array();
        foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
    }
    $eval = 'array_multisort(';
    foreach ($cols as $col => $order) {
        $eval .= '$colarr[\''.$col.'\'],'.$order.',';
    }
    $eval = substr($eval,0,-1).');';
    eval($eval);
    $ret = array();
    foreach ($colarr as $col => $arr) {
        foreach ($arr as $k => $v) {
            $k = substr($k,1);
            if (!isset($ret[$k])) $ret[$k] = $array[$k];
            $ret[$k][$col] = $array[$k][$col];
        }
    }
    return $ret;
}
?>
