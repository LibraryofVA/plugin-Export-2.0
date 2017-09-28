<?php
//PDF directory location, this directory needs to be owned by your httpd service account
$pdfDirectory = dirname(getcwd()) . "/plugins/Export/PDF/";

// Loop over all of the .pdf files in the PDF folder
foreach (glob($pdfDirectory . "*.pdf") as $file) {
	unlink($file); // unlink deletes a file
}

//include FPDF 
require_once(dirname(getcwd()) . "/plugins/Export/fpdf.php");

//get collection from query string
$collectionID = $_GET['c'];

//create empty array to hold pdf files that we will ZIP
$arrayOfPDFs = array();

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
		set_loop_records('files', get_current_record('item')->Files);
		foreach(loop('files') as $file) :
			if (metadata($file, array('Scripto', 'Transcription'))):
				//remove domain and any directory from orginal filename (jpg)
				if (strpos(metadata($file, 'original filename'), '/') !== FALSE) {
					$jpgFileName = substr(strrchr(metadata($file, 'original filename'), "/"), 1);
				} else {
					$jpgFileName = metadata($file, 'original filename');
				}

				//set pdf name, substr -4 is taking off .jpg
				$pdfFileName = substr($jpgFileName, 0, -4) . "_transcription.pdf";

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

				//create a pdf containing the transcription
				$pdf = new FPDF();
				$pdf->AddPage();
				$pdf->SetFont('Times','',12);
				$pdf->Cell(40,15,$pdf->Image(dirname(getcwd()) . '/plugins/Export/logo.png', 10, 10, 35),0,0);
				$pdf->Cell(0,5,$transcriptionTitle,0,1);
				$pdf->SetX(50); //indent the next cell
				$pdf->Cell(0,5,metadata($item, array('Dublin Core', 'Date')),0,1);
				$pdf->SetX(50); //indent the next cell
				$pdf->Cell(0,5,$jpgFileName,0,1);
				$pdf->Cell(0,5,"",0,1);
				$pdf->MultiCell(0,5,$transcriptionText);
				$content = $pdf->Output($pdfDirectory . $pdfFileName,'F');
				//add the pdf name to an array used later to zip the files
				$arrayOfPDFs[] = $pdfFileName;
			endif;
		endforeach;
	endif;
endforeach;

$result = create_zip($arrayOfPDFs,$pdfDirectory . "collection.zip",$pdfDirectory);
if($result) {
	header("Content-type: application/zip"); 
	header("Content-Disposition: attachment; filename=collection.zip"); 
    header("Pragma: no-cache"); 
    header("Expires: 0"); 
    readfile($pdfDirectory . "collection.zip");
}


/* creates a compressed zip file */
function create_zip($files = array(),$destination = '',$localPdfDirectory = '',$overwrite = true) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($localPdfDirectory . $file)) {
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
			$zip->addFile($localPdfDirectory . $file,$file);
		}
		//debug ** turning this on will break the forced download of ZIP file as the echo result will be added to the zip file downloaded
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		//close the zip
		$zip->close();
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}
?>