<?php
//PDF directory location, this directory needs to be owned by your httpd service account
$txtDirectory = dirname(getcwd()) . "/plugins/Export/TXT/";

//Create log file
write_to_log($txtDirectory,"w",date("Y-m-d h:i:sa")."\r\n");

// Loop over all of the .txt files in the TXT folder
foreach (glob($txtDirectory . "*.txt") as $file) {
	unlink($file); // unlink deletes a file
}

//get collection from query string
$collectionID = $_GET['c'];

//create empty array to hold TXT files that we will ZIP
$arrayOfTXTs = array();

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
		//logging individual item titles
		write_to_log($txtDirectory,"a",metadata($item, array('Dublin Core', 'Title')));

		set_loop_records('files', get_current_record('item')->Files);
		foreach(loop('files') as $file) :
			if (metadata($file, array('Scripto', 'Transcription'))):
				//remove domain and any directory from orginal filename (jpg)
				if (strpos(metadata($file, 'original filename'), '/') !== FALSE) {
					$jpgFileName = substr(strrchr(metadata($file, 'original filename'), "/"), 1);
				} else {
					$jpgFileName = metadata($file, 'original filename');
				}
				//set txt name
				$txtFileName = preg_replace("/.jpg$/", "_transcription.txt", $jpgFileName);

				//logging individual txt file names
				write_to_log($txtDirectory,"a","\t".$txtFileName);
		

				//clean <p>, </p>, <pre>, </pre>, and <br /> out of the transcription text
				$transcriptionText = preg_replace("/<[\/]*p>/", "", metadata($file, array('Scripto', 'Transcription')));
				$transcriptionText = preg_replace("/<[\/]*pre>/", "", $transcriptionText);
				$transcriptionText = preg_replace("/<br \/>/", "", $transcriptionText);
				//replace &amp; with &
				$transcriptionText = preg_replace("/&amp;/", "&", $transcriptionText);
				//replace a coded non-breaking space with a space
				$transcriptionText = preg_replace("/&#160;/", " ", $transcriptionText);
				//remove Transclusion expansion time report
				$transcriptionText = preg_replace("/<!--.*?-->/ms", "", $transcriptionText);
				//convert transcription text from UTF-8 to windows-1252 which worked better in PDF files created
				$transcriptionText = iconv('UTF-8', 'windows-1252//TRANSLIT', utf8_encode($transcriptionText));

				//replace coded single quotes found in the Title with a single quote
				$transcriptionTitle = preg_replace("/&#039;/", "'", metadata($item, array('Dublin Core', 'Title')));
				//convert transcription title from UTF-8 to windows-1252
				$transcriptionTitle = iconv('UTF-8', 'windows-1252', $transcriptionTitle);
				//create a txt containing the transcription
				$myfile = fopen($txtDirectory . $txtFileName, "w") or die("Unable to open file!");
				fwrite($myfile, $transcriptionTitle."\r\n");
				fwrite($myfile, metadata($item, array('Dublin Core', 'Date'))."\r\n");
				fwrite($myfile, $transcriptionText);
				fclose($myfile);
				//add the txt name to an array used later to zip the files
				$arrayOfTXTs[] = $txtFileName;
			endif;
		endforeach;
	endif;
endforeach;

//logging
write_to_log($txtDirectory,"a","End of text file creation reached");

$result = create_zip($arrayOfTXTs,$txtDirectory . "collection.zip",$txtDirectory);
if($result) {
	header("Content-type: application/zip"); 
	header("Content-Disposition: attachment; filename=collection.zip"); 
    header("Pragma: no-cache"); 
    header("Expires: 0"); 
    readfile($txtDirectory . "collection.zip");
}


/* creates a compressed zip file */
function create_zip($files = array(),$destination = '',$localTxtDirectory = '',$overwrite = true) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { 
		write_to_log($localTxtDirectory,"a","Error: zip file destiniation exists and is not overwrite-able");
		return false; 
	}
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($localTxtDirectory . $file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($localTxtDirectory . $file,$file);
		}
		//log our current status
		write_to_log($localTxtDirectory,"a","The zip archive contains " . $zip->numFiles . " files with a status of " . $zip->status);
		//close the zip
		$zip->close();
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		write_to_log($localTxtDirectory,"a","Error: no valid files were returned for zipping");
		return false;
	}
}

function write_to_log($localTxtDirectory = '', $openMode = '', $logEntry = '') {
	$myfile = fopen($localTxtDirectory . 'export.log', $openMode) or die("Unable to open file!");
	fwrite($myfile, $logEntry."\r\n");
	fclose($myfile);
}
?>
