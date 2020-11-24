<?php // Simple File List Script: ee-list-settings.php | Author: Mitchell Bennis | support@simplefilelist.com
	
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! wp_verify_nonce( $eeSFL_Nonce, 'eeInclude' ) ) exit('ERROR 98'); // Exit if nonce fails

$eeSFL_FREE_Log['SFL'][] = 'Loading List Settings Page ...';

// Check for POST and Nonce
if(@$_POST['eePost'] AND check_admin_referer( 'ee-simple-file-list-settings', 'ee-simple-file-list-settings-nonce')) {	
	
	// List Visibility
	if($_POST['eeShowList'] == 'YES') { $eeSFL_Settings_1['ShowList'] = 'YES'; } 
		elseif($_POST['eeShowList'] == 'USER') { $eeSFL_Settings_1['ShowList'] = 'USER'; } // Show only to logged in users
			 elseif($_POST['eeShowList'] == 'ADMIN') { $eeSFL_Settings_1['ShowList'] = 'ADMIN'; } // Show only to logged in Admins
				else { $eeSFL_Settings_1['ShowList'] = 'NO'; }	

		
		// YES/NO Checkboxes
	$eeCheckboxes = array(
		'AllowFrontManage'
		,'ShowFileThumb'
		,'ShowFileDate'
		,'ShowFileSize'
		,'ShowFileActions'
		,'ShowFileDescription'
		,'ShowHeader'
		,'ShowSubmitterInfo'
		,'PreserveSpaces'
		,'ShowFileExtension'
		,'ExpireTime'
	);
		
	foreach( $eeCheckboxes as $eeTerm){ // "ee" is added in the function
		
		$eeSFL_Settings_1[$eeTerm] = eeSFL_FREE_ProcessCheckboxInput($eeTerm);
	}
	
	$eeTextInputs = array(
		'LabelThumb'
		,'LabelName'
		,'LabelDate'
		,'LabelSize'
	);
	foreach( $eeTextInputs as $eeTerm){
		$eeSFL_Settings_1[$eeTerm] = eeSFL_FREE_ProcessTextInput($eeTerm);
	}
	
	// Sort by Select Box	
	if(@$_POST['eeSortBy']) { 
		
		if($_POST['eeSortBy'] != $eeSFL_Settings_1['SortBy']) { // Changed
			
			$eeSFL_Settings_1['SortBy'] = sanitize_text_field($_POST['eeSortBy']);
			
		} else { $eeSFL_Settings_1['SortBy'] = 'Name'; }
		
	}
	
	// Asc/Desc Checkbox
	if(@$_POST['eeSortOrder'] == 'Descending') { $eeSFL_Settings_1['SortOrder'] = 'Descending'; }
		elseif($_POST['eeSortBy'] AND !@$_POST['eeSortOrder']) { $eeSFL_Settings_1['SortOrder'] = 'Ascending'; }

	
	// Sort for Sanity
	ksort($eeSFL_Settings_1);
	
	// Update DB
	if( update_option('eeSFL_Settings_1', $eeSFL_Settings_1) ) {
		$eeSFL_Confirm = __('Settings Saved', 'ee-simple-file-list');
		$eeSFL_FREE_Log['SFL'][] = $eeSFL_Confirm;
	} else {
		$eeSFL_FREE_Log['SFL'][] = '!!! The database was not updated.';
	}
}

// Settings Display =========================================
	
$eeOutput .= '<div class="eeSFL_Admin">';
	
if(@$eeSFL_FREE_Log['errors']) { 
	$eeOutput .=  eeSFL_FREE_ResultsDisplay($eeSFL_FREE_Log['errors'], 'notice-error');
} elseif(@$eeSFL_Confirm) { 
	$eeOutput .=  eeSFL_FREE_ResultsDisplay($eeSFL_Confirm, 'notice-success');
}

// Begin the Form	
$eeOutput .= '

<form action="' . admin_url() . '?page=' . $eeSFL_FREE->eePluginSlug . '&tab=settings&subtab=list_settings" method="post" id="eeSFL_Settings">
		
		<p class="eeSettingsRight"><a class="eeInstructionsLink" href="https://simplefilelist.com/file-list-settings/" target="_blank">' . __('Instructions', 'ee-simple-file-list') . '</a>
		<input type="submit" name="submit" value="' . __('SAVE', 'ee-simple-file-list') . '" class="button eeSFL_Save" /></p>
		
		<h2>' . __('List Settings', 'ee-simple-file-list') . '</h2>
		
		<input type="hidden" name="eePost" value="TRUE" />';	
		
		$eeOutput .= wp_nonce_field( 'ee-simple-file-list-settings', 'ee-simple-file-list-settings-nonce', TRUE, FALSE);
		
		$eeOutput .= '<br class="eeClearFix" />
		
		<fieldset class="eeSFL_SettingsFull">
		
			<h3>' . __('List Location', 'ee-simple-file-list') . '</h3>
				<p><strong><em>' . ABSPATH . $eeSFL_Settings_1['FileListDir'] . '</strong></em></p>
				
				<div class="eeNote"><a href="" title="Get Pro Version">' . __('Get Pro Version', 'ee-simple-file-list') . '</a> &rarr; ' . __('The Pro Version allows you to define a custom file list directory.', 'ee-simple-file-list') . '</div>
			
		</fieldset>
		
		
		
		
		
		
		<fieldset class="eeSFL_SettingsBlock">
		
		<h3>' . __('File List Access', 'ee-simple-file-list') . '</h3>
	
		<label for="eeShowList">' . __('Front-Side Display', 'ee-simple-file-list') . '</label>
		
		<select name="eeShowList" id="eeShowList">
		
			<option value="YES"';

			if($eeSFL_Settings_1['ShowList'] == 'YES') { $eeOutput .= ' selected'; }
			
			$eeOutput .= '>' . __('Show to Everyone', 'ee-simple-file-list') . '</option>
			
			<option value="USER"';

			if($eeSFL_Settings_1['ShowList'] == 'USER') { $eeOutput .= ' selected'; }
			
			$eeOutput .= '>' . __('Show to Only Logged in Users', 'ee-simple-file-list') . '</option>
			
			<option value="ADMIN"';

			if($eeSFL_Settings_1['ShowList'] == 'ADMIN') { $eeOutput .= ' selected'; }
			
			$eeOutput .= '>' . __('Show to Only Logged in Admins', 'ee-simple-file-list') . '</option>
			
			<option value="NO"';

			if($eeSFL_Settings_1['ShowList'] == 'NO') { $eeOutput .= ' selected'; }
			
			$eeOutput .= '>' . __('Hide the File List Completely', 'ee-simple-file-list') . '</option>
		
		</select>
		<div class="eeNote">' . __('Determine who you will show the front-side list to.', 'ee-simple-file-list') . '</div> 
		
		
		
		
		
		<label for="eeAllowFrontManage">' . __('Front-End Management', 'ee-simple-file-list') . ':</label>
		<input type="checkbox" name="eeAllowFrontManage" value="YES" id="eeAllowFrontManage"';
		
		if( $eeSFL_Settings_1['AllowFrontManage'] == 'YES') { $eeOutput .= ' checked="checked"'; }
		
		$eeOutput .= ' /> <p><strong>' . __('Allow file deleting, renaming, etc...', 'ee-simple-file-list') . '</strong></p>
		
		<div class="eeNote"><a href="https://simplefilelist.com/file-access-manager/" target="_blank">' . __('Get File Access Manager', 'ee-simple-file-list') . '</a> &rarr; ' . 
			__('The Pro version allows you to add the "File Access Manager" extension.  This gives you improved user access control.', 'ee-simple-file-list') . '</a></div>
	
		</fieldset>
		
		
		
		
		
		
		
		<fieldset class="eeSFL_SettingsBlock">
		
		<h3>' . __('File List Table Information', 'ee-simple-file-list') . '</h3>

		<table id="eeListSettingsTable">
			<thead>
			  	<tr>
			     	<th>' . __('Item', 'ee-simple-file-list') . '</th>
				 	<th>' . __('Show', 'ee-simple-file-list') . '</th>
				 	<th>' . __('Label', 'ee-simple-file-list') . '</th>
				 </tr>
			</thead>
		<tbody>
		  
		<tr>
		     <td>' . __('File Thumbnail', 'ee-simple-file-list') . '</td>
		     <td><input type="checkbox" name="eeShowFileThumb" value="YES" id="eeShowFileThumb"'; 
		if($eeSFL_Settings_1['ShowFileThumb'] == 'YES') { $eeOutput .= ' checked'; }
		$eeOutput .= ' /></td>
		     <td><input type="text" name="eeLabelThumb" value="';
		if(@$eeSFL_Settings_1['LabelThumb']) { $eeOutput .= stripslashes($eeSFL_Settings_1['LabelThumb']); } else { $eeOutput .= __('Thumb', 'ee-simple-file-list'); }
		$eeOutput .= '" size="16" /></td>
		  </tr>
		  
		  <tr>
		     <td>' . __('File Name', 'ee-simple-file-list') . '</td>
		     <td><input type="checkbox" name="eeShowFileName" value="YES" id="eeLabelName" checked="checked" disabled /></td>
		     <td><input type="text" name="eeLabelName" value="';
		if(@$eeSFL_Settings_1['LabelName']) { $eeOutput .= stripslashes($eeSFL_Settings_1['LabelName']); } else { $eeOutput .= __('Name', 'ee-simple-file-list'); }
		$eeOutput .= '" size="16" /></td>
		  </tr>
		  
		  <tr>
		     <td>' . __('File Date', 'ee-simple-file-list') . '</td>
		     <td><input type="checkbox" name="eeShowFileDate" value="YES" id="eeShowFileDate"'; 
		if($eeSFL_Settings_1['ShowFileDate'] == 'YES') { $eeOutput .= ' checked'; }
		$eeOutput .= ' /></td>
		     <td><input type="text" name="eeLabelDate" value="';
		if(@$eeSFL_Settings_1['LabelDate']) { $eeOutput .= stripslashes($eeSFL_Settings_1['LabelDate']); } else { $eeOutput .= __('Date', 'ee-simple-file-list'); }
		$eeOutput .= '" size="16" /></td>
		  </tr>
		  
		  <tr>
		     <td>' . __('File Size', 'ee-simple-file-list') . '</td>
		     <td><input type="checkbox" name="eeShowFileSize" value="YES" id="eeShowFileSize"'; 
		if($eeSFL_Settings_1['ShowFileSize'] == 'YES') { $eeOutput .= ' checked'; }
		$eeOutput .= ' /></td>
		     <td><input type="text" name="eeLabelSize" value="';
		if(@$eeSFL_Settings_1['LabelSize']) { $eeOutput .= stripslashes($eeSFL_Settings_1['LabelSize']); } else { $eeOutput .= __('Size', 'ee-simple-file-list'); }
		$eeOutput .= '" size="16" /></td>
		  </tr>
		 
		 
		  	</tbody>
		</table>
				
		<div class="eeNote">' . __('Limit the file information to display on the front-side file list.', 'ee-simple-file-list') . '</div>
			
		
		<label for="eeShowListHeader">' . __('Show Header', 'ee-simple-file-list') . ':</label>
		<input type="checkbox" name="eeShowHeader" value="YES" id="eeShowListHeader"';
		
		if( $eeSFL_Settings_1['ShowHeader'] == 'YES') { $eeOutput .= ' checked="checked"'; }
		
		$eeOutput .= ' /> <p>' . __('Show the table header', 'ee-simple-file-list') . '</p>
		
		<div class="eeNote">' . __('Show the table header above the file list or not.', 'ee-simple-file-list') . '</div>
		
		
		
		<label for="eeShowFileActions">' . __('Show File Actions', 'ee-simple-file-list') . ':</label>
		<input type="checkbox" name="eeShowFileActions" value="YES" id="eeShowFileActions"';
		
		if( $eeSFL_Settings_1['ShowFileActions'] == 'YES') { $eeOutput .= ' checked="checked"'; }
		
		$eeOutput .= ' /> <p>' . __('Open, Download, etc.', 'ee-simple-file-list') . '</p>
		
		<div class="eeNote">' . __('Show file action links below each file name on the front-end list', 'ee-simple-file-list') . '</div>
		
		
		
		<label for="eeShowSubmitterInfo">' . __('Show File Owner', 'ee-simple-file-list') . ':</label>
		<input type="checkbox" name="eeShowSubmitterInfo" value="YES" id="eeShowSubmitterInfo"';
		
		if( $eeSFL_Settings_1['ShowSubmitterInfo'] == 'YES') { $eeOutput .= ' checked="checked"'; }
		
		$eeOutput .= ' /> <p>' . __('Show on Front-End', 'ee-simple-file-list') . '</p>
		
		<div class="eeNote">' . __('Show the name of the user who uploaded the file.', 'ee-simple-file-list') . '</div>
			
		</fieldset>
		
		
			
		
			
		
			
			
			
			
			
		<fieldset class="eeSFL_SettingsBlock">
		
		<h2>' . __('File Sorting and Order', 'ee-simple-file-list') . '</h2>	
			
		<label for="eeSortList">' . __('Sort By', 'ee-simple-file-list') . ':</label>
		
		<select name="eeSortBy" id="eeSortList">
		
				<option value="Name"';
				
				if($eeSFL_Settings_1['SortBy'] == 'Name') { $eeOutput .=  ' selected'; }
				
				$eeOutput .= '>' . __('File Name', 'ee-simple-file-list') . '</option>
				<option value="Date"';
				
				if($eeSFL_Settings_1['SortBy'] == 'Date') { $eeOutput .=  ' selected'; }
				
				$eeOutput .= '>' . __('File Date', 'ee-simple-file-list') . '</option>
				<option value="Size"';
				
				if($eeSFL_Settings_1['SortBy'] == 'Size') { $eeOutput .=  ' selected'; }
				
				$eeOutput .= '>' . __('File Size', 'ee-simple-file-list') . '</option>
				<option value="Random"';
				
				if($eeSFL_Settings_1['SortBy'] == 'Random') { $eeOutput .=  ' selected'; }
				
				$eeOutput .= '>' . __('Random', 'ee-simple-file-list') . '</option>
			
			</select> 
		
		<div class="eeNote">' . __('Sort the list by name, date, file size, or randomly.', 'ee-simple-file-list') . '</div>
			
		<label for="eeSortOrder">' . __('Reverse Order', 'ee-simple-file-list') . ':</label>
		<input type="checkbox" name="eeSortOrder" value="Descending" id="eeSortOrder"';
		
		if( $eeSFL_Settings_1['SortOrder'] == 'Descending') { $eeOutput .= ' checked="checked"'; }
		
		$eeOutput .= ' /> <p>&darr; ' . __('Descending', 'ee-simple-file-list') . '</p>
		
		<div class="eeNote">' . __('Check this box to reverse the default sort order.', 'ee-simple-file-list') . '<br />
			' . __('The list is sorted Ascending by default', 'ee-simple-file-list') . ': A to Z, ' . __('Small to Large', 'ee-simple-file-list') . ', ' . __('Old to New', 'ee-simple-file-list') . '</div>	
		
		
		
		
		</fieldset>	
				
				
				
				
				
		<fieldset class="eeSFL_SettingsBlock">
		
		<h2>' . __('File Details', 'ee-simple-file-list') . '</h2>	
		
		<label for="eePreserveSpaces">' . __('Preserve Spaces', 'ee-simple-file-list') . ':</label>
		<input type="checkbox" name="eePreserveSpaces" value="YES" id="eePreserveSpaces"';
		
		if( $eeSFL_Settings_1['PreserveSpaces'] == 'YES') { $eeOutput .= ' checked="checked"'; }
		
		$eeOutput .= ' /> <p>' . __('File Name Spaces', 'ee-simple-file-list') . '</p>
		
		<div class="eeNote">' . __('Spaces in file names are replaced with hyphens in order to make the URL legal.', 'ee-simple-file-list') . '<br />' . 
			__('This setting will revert this action for display.', 'ee-simple-file-list') . '</div>
		
		<br class="eeClearFix" />	
		
		<label for="eeShowFileDescription">' . __('Show File Description', 'ee-simple-file-list') . ':</label>
		<input type="checkbox" name="eeShowFileDescription" value="YES" id="eeShowFileDescription"';
		
		if( $eeSFL_Settings_1['ShowFileDescription'] == 'YES') { $eeOutput .= ' checked="checked"'; }
		
		$eeOutput .= ' /> <p>' . __('Description of the file', 'ee-simple-file-list') . '</p>
		
		<div class="eeNote">' . __('Display the file description below the file name.', 'ee-simple-file-list') . '</div>
		
		<br class="eeClearFix" />		
		
		<label for="eeShowFileExtension">' . __('Show Extension', 'ee-simple-file-list') . ':</label>
		<input type="checkbox" name="eeShowFileExtension" value="YES" id="eeShowFileExtension"';
		
		if( $eeSFL_Settings_1['ShowFileExtension'] == 'YES') { $eeOutput .= ' checked="checked"'; }
		
		$eeOutput .= ' /> <p>' . __('File Type', 'ee-simple-file-list') . '</p>
		
		<div class="eeNote">' . __('Show or hide the file extension.', 'ee-simple-file-list') . '</div>
		
		<br class="eeClearFix" />

		</fieldset>
		
		
		
			
			
		<fieldset class="eeSFL_SettingsBlock">
		
		<h2>' . __('File List Cache', 'ee-simple-file-list') . '</h2>	
				
		<label for="eeExpireTime">' . __('Use the File List Cache', 'ee-simple-file-list') . ':</label>
		<input type="checkbox" name="eeExpireTime" value="YES"';
		if( $eeSFL_Settings_1['ExpireTime'] != 'NO') { $eeOutput .= ' checked="checked"'; }
		$eeOutput .= ' /> 
		<div class="eeNote">' . __('Reduce server load by only scanning the hard disk occasionally.', 'ee-simple-file-list') . ' '  . 
			__('If you use FTP or another method to upload files to your list, turn this off to always see the most recent files.', 'ee-simple-file-list') . '</div>
		
		</fieldset>
		
		
		
		<input type="submit" name="submit" value="' . __('SAVE', 'ee-simple-file-list') . '" class="button eeSFL_Save" />
		
	</form>
	
</div>';
	
?>