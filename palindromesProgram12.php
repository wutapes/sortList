<?php					 										 
################################################################											 
function sortFileContents($filename) {
    // Step 1: Read the file into an array
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    natcasesort($lines);
	return $lines;
}
################################################################
//PROCESSING UPLOAD

if (!empty($_POST) && !empty($_FILES)) {				
$target_path = basename($_FILES['uploadedfile']['name']);    //echo $target_path;
   $filename = basename($_FILES['uploadedfile']['name']);   //NAME OF FILE 

//need to check file type first - if is a txt file can use it
		if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {

			echo "{$filename} file uploaded !";  
			
			

			}else{
					//echo "There was an error uploading the file, please try again!";
					//echo "Please upload a text (*.txt) file! " . "<br>";
					echo "<p>";
	
		if(!file_exists($_FILES['uploadedfile']['tmp_name']) || !is_uploaded_file($_FILES['uploadedfile']['tmp_name'])) {
		echo 'No upload - ';
		}  			 // END-IF
	}				//END-ELSE
	
}            	  // END-IF


if (isset($filename) && !empty($filename)) {
 //echo "<pre>";
//print_r(array_filter($revFileArray)); 
//echo "</pre>";
//echo "<p>";

$outputFile = strtolower(str_replace(' ', '_', $filename));
$outputFile2 = str_replace(".txt","(2).txt",$outputFile);


$lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    natcasesort($lines);

file_put_contents($outputFile2, implode("\r\n", $lines));
//$here = "<a href='$outputFile'>Download file</a>";
$here = "<a href='$outputFile2'>here</a>";
echo "<p>";
}	

################################################################

//only show download link if the $filename has a value
if (isset($filename) && !empty($filename)) {
echo "<p>";
echo "right click {$here} to download your file " . "<p>";  
}	

################################################################

//UPLOAD FORM	
echo "Upload a File";	
echo "<br/>";
$formUploader = <<<UPLOADER
<form enctype="multipart/form-data" action=" " method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
Choose an file to upload: <input name="uploadedfile" type="file" /><br />
<input type="submit" value="Upload File" />
</form>
UPLOADER;
echo $formUploader;	

?>