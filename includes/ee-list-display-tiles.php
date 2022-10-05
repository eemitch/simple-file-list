<?php
	
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! wp_verify_nonce( $eeSFL_Nonce, 'eeInclude' )) exit('ERROR 98'); // Exit if nonce fails
	
$eeFileID = 0; // Assign an ID number to each Tile, aka Row

$eeSFL_BASE->eeLog['notice'][] = 'Listing Files in Tile View...';

$eeOutput .= '<section class="eeFiles">';
						
// Loop through array
foreach($eeSFL_Files as $eeFileKey => $eeFileArray) { // <<<---------------------------- BEGIN FILE LIST LOOP ----------------<<<
	
	// Populate our class properties for this file
	if( $eeSFL_BASE->eeSFL_ProcessFileArray($eeFileArray) === FALSE ) { continue; } // Skip This File
			
	if( $eeSFL_BASE->eeIsFile === TRUE ) {
		
		$eeFileID ++;
		
		$eeOutput .= '
		
		<article id="eeSFL_FileID-' . $eeFileID . '" class="eeSFL_Tile">
		
		<span class="eeSFL_RealFileName eeHide">' . $eeSFL_BASE->eeRealFileName . '</span>
		<span class="eeSFL_FileNiceName eeHide">' . $eeSFL_BASE->eeFileNiceName . '</span>
		
		<h4 class="eeSFL_FileLink">
			<a class="eeSFL_FileName" href="' . $eeSFL_BASE->eeFileURL .  '" target="_blank">' . stripslashes($eeSFL_BASE->eeFileName) . '</a></h4>';
			
		// File Actions
		$eeOutput .= $eeSFL_BASE->eeSFL_ReturnFileActions($eeFileID);
		
		
		// Thumbnail
		if($eeAdmin OR $eeSFL_BASE->eeListSettings['ShowFileThumb'] == 'YES') {
			if($eeSFL_BASE->eeFileThumbURL) { 
				$eeOutput .= '<div class="eeSFL_Thumbnail"><a href="' . $eeSFL_BASE->eeFileURL .  '"><img src="' . $eeSFL_BASE->eeFileThumbURL . '" width="64" height="64" alt="Thumb" /></a></div>';
			}
		}
		
		
		// File Description
		if($eeSFL_BASE->eeListSettings['ShowFileDesc'] == 'NO' AND !$eeAdmin) { $eeClass = 'eeHide'; }
		$eeOutput .= '<p class="eeSFL_FileDesc ' . $eeClass . '">' . stripslashes($eeSFL_BASE->eeFileDescription) . '</p>'; // Always here for JS
		
		// Submitter Info
		if($eeAdmin OR $eeSFL_BASE->eeListSettings['ShowSubmitterInfo'] == 'YES') {	
			if($eeSFL_BASE->eeFileSubmitterName) {
				$eeOutput .= '<small class="eeSFL_FileSubmitter"><span>' . $eeSFL_BASE->eeListSettings['LabelOwner'] . ': </span>
					<a href="mailto:' . $eeSFL_BASE->eeFileSubmitterEmail . '">' . stripslashes($eeSFL_BASE->eeFileSubmitterName) . '</a></small>';
			}
		}
		
		$eeOutput .= '<div class="eeSFL_FileDetails">';

		
		// File Size
		if($eeAdmin OR $eeSFL_BASE->eeListSettings['ShowFileSize'] == 'YES') {
		
			$eeOutput .= '<span class="eeSFL_FileSize">' . $eeSFL_BASE->eeFileSize . '</span>';
		}
		
		
		// File Modification Date
		if($eeAdmin OR $eeSFL_BASE->eeListSettings['ShowFileDate'] == 'YES') {
			
			$eeOutput .= '<span class="eeSFL_FileDate">' . $eeSFL_BASE->eeFileDate . '</span>';
		}
		
		
		$eeOutput .= '</div>
		
		</article>';
	
	}

}

$eeOutput .= '</section>';


?>