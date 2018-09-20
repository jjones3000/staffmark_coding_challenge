<?php
/**
 * fileUpload.php
 * This page accepts a .xls file to be uploaded.
 * It ensures that a file is uploaded, and that it is of the type .xls
 * The file name is 'sanitized' to prevent injection of malicious code
 * The excel file is parsed with PHPExcel (open license), and both the data and labels are held as arrays...
 * that get passed to the ChartDirector software. ChartDirector out put cannot contain HTML content, so, after...
 * the excel file is uploaded, users are redirected to graph.php which contains the graphical output.
 */
session_start();
include('includes/fxns.inc.php');
require_once './Classes/PHPExcel.php';
require_once ("./ChartDirector/lib/phpchartdir.php");
$error='';
$img='';



/*
if (!isset($_SESSION['user'])){
die("<br />You need to login to view this page");
}
$user = $_SESSION['user'];
if(isset($_FILES['xl']['name']) && empty($_FILES['xl']['name'])){
$error="You have not selected anything to upload.";
}
*/
if(isset($_FILES['xl']['name']) && (!empty($_FILES['xl']['name']))){
	$xl=sanitizeString($_FILES['xl']['name']);
	$xlExp=explode('.', $xl);
	$xlExt=$xlExp[1];
	move_uploaded_file($_FILES['xl']['tmp_name'], "./".$xl);	
	
		if($xlExt !== 'xls'){
			die ($error = "Sorry, .xls files only");
			
		}
		
		
		else{
			
			$objReader = new PHPExcel_Reader_Excel5();//create new PHPExcel Reader object
			$objPHPExcel = $objReader->load($xl);//instantiate our uploaded excel file
			$sheetNames=$objPHPExcel->getSheetNames();//get the worksheet names into an array 
			$objWorksheet = $objPHPExcel->setActiveSheetIndex(1); // set worksheet to second sheet  
			$highestRow = $objWorksheet->getHighestRow(); //will get total number of rows (will use in the 2 'for loops' below)

			//for-loop to assign all A column values to variables
				for($x=1; $x<=$highestRow; $x++){
					$valA="A".($x+1);
					$data[$x] = $objPHPExcel->getActiveSheet()->getCell($valA)->getValue();
				}
			//for loop to assign all B column values to variables
				for($x=1; $x<=$highestRow; $x++){
					$valB="B".($x+1);
					$labels[$x] = $objPHPExcel->getActiveSheet()->getCell($valB)->getValue();
				}
				$_SESSION['data']=$data;
				$_SESSION['labels']=$labels;
				$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
				$title=$objPHPExcel->getActiveSheet()->getCell('A1')->getValue();
				$_SESSION['sheet_title']=$title;
				header('location:graph.php');

		}
}
echo <<<_END
<html>
<head><title></title></head>
<body>$error
	<form enctype='multipart/form-data' method='POST' action='fileUpload.php'>
	<input type='file' name='xl' id='xl' /><br />	
	<input type='submit' value='upload' />
	</form>
</body>
</html>
_END
?>