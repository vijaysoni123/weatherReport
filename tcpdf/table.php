<?php
session_start();
ob_start();
//include('connection.php');
//$files = glob('Storefiles/*'); // get all file names

	/*foreach($files as $file){ // iterate files
		if(is_file($file))
		{
			$ext = pathinfo($file, PATHINFO_EXTENSION);
			if(($ext == 'pdf') && (time() - filectime($file) > 300))
			{
				unlink($file); // delete file
			}
		}
	}
*/
error_reporting(0);
include '../spoperation.php';
$user_role_id=$_SESSION['role_id'];
	// Include the main TCPDF library (search for installation path).
	require_once('config/tcpdf_config.php');
	$tcpdf_include_dirs = array(
		realpath('tcpdf.php'),
		'/usr/share/php/tcpdf/tcpdf.php',
		'/usr/share/tcpdf/tcpdf.php',
		'/usr/share/php-tcpdf/tcpdf.php',
		'/var/www/tcpdf/tcpdf.php',
		'/var/www/html/tcpdf/tcpdf.php',
		'/usr/local/apache2/htdocs/tcpdf/tcpdf.php'
	);
	foreach ($tcpdf_include_dirs as $tcpdf_include_path) 
	{
		if (@file_exists($tcpdf_include_path)) 
		{
			require_once($tcpdf_include_path);
			break;
		}
	}
	$dataFlag='';
	$call = new spoperation();
	$conn = $call->CreateConnection();

	if($user_role_id=='2')
	{
		$dataqw = $call->SP_GET_BASIC_DETAILS_OF_TOKEN_NO($_REQUEST['TokenNo'],$_SESSION['user_id']);
	}
	else
	{
		$dataqw = $call->SP_GET_BASIC_DETAILS_OF_TOKEN_NO_BY_ADMIN($_REQUEST['TokenNo']);
	}
	//echo sizeof($dataqw);

	if(sizeof($dataqw)-1 > 0)
	{
		foreach($dataqw as $dataqws)
		{
			$dataqws= get_object_vars($dataqws);
			//print_r($dataqws);
			if(!empty($dataqws))
			{			
			    $TokenNumber=$dataqws['token_no'];
				//echo $TokenNumber;
				$FilingDate=$dataqws['FilingDate'];
				$FilingTime=$dataqws['FilingTime'];
				$dateoffiling= $dataqws['dateoffiling'];
				$FilingTime='';
				$kk=0;
				foreach($dateoffiling as $dater)
				{
					if($kk == 0)
					{
						$FilingTime = date('h:i:s a', strtotime($dater));
						++$kk;
					}
					
					
				}
				//$FilingTime = date('h:i:s a', strtotime($dateoffiling['date']));
				$CaseCategory=$dataqws['case_cat']; 
				$CaseType=$dataqws['case_type']; 
				$Bench=$dataqws['bench'];
				$dataFlag=$dataqws['DataFlag'];
				$firDistrict=$dataqws['firDistrict'];
				$firStation=$dataqws['firStation'];
				$firNumber=$dataqws['firNumber'];
				$firYear=$dataqws['firYear'];
				$lcCaseNumber=$dataqws['lcCaseNumber'];
				$lcCaseType=$dataqws['lcCaseType'];
				$lcCaseYear=$dataqws['lcCaseYear'];
				$lcCourtName=$dataqws['lcCourtName'];
				$date_of_decision=$dataqws['date_of_decision'];
				$lcJudgeName=$dataqws['lcJudgeName'];
				$lcJudgeShip=$dataqws['lcJudgeShip'];
				$lcPlace=$dataqws['lcPlace'];
				
				$my_array=array();
				$CaseFilingData = "";
				if($user_role_id=='2')
				{
					$CaseData = $call->SP_GET_CASE_DETAILS_BY_TOKEN_NO($_REQUEST['TokenNo'],$_SESSION['user_id']);
				}
				else
				{
					$CaseData = $call->SP_GET_CASE_DETAILS_BY_TOKEN_NO_BY_ADMIN($_REQUEST['TokenNo']);
				}				
				//$CaseData = $call->SP_GET_CASE_DETAILS_BY_TOKEN_NO($_REQUEST['TokenNo'],$_SESSION['user_id']);	
				foreach($CaseData as $datarow)
				{
					if(!empty($datarow))
					{
						$datarows= get_object_vars($datarow);
						//print_r($datarows);
						if(!empty($datarows))
						{
							$CaseFilingData = implode("; ",$datarows);
							array_push($my_array,$CaseFilingData);
						}
					}
				}
			}
		}	
	}
	//echo "<pre>";
	//print_r($my_array);
	// create new PDF document
	class MYPDF extends TCPDF 
	{
		//Page header
		public function Header() 
		{
			$fitbox=false;
			$this->Rect(5, 6, 200, 340, 'DF', '', array(255, 255, 255));
			
			//$this->SetLineWidth(0.2);
			$this->Line(5, 28, 205, 28, '');
			//$this->Line(170, 3, 170, 25, $style);
			$this->Line(30, 6, 30, 28, '');
			$this->SetFont('times', 'B', 14);
			$this->Text(68, 8, 'RAJASTHAN HIGH COURT BENCH JAIPUR');
			$this->SetFont('times', 'B', 12);
			$this->Text(88, 15, 'Online Cause Title Receipt');
			$this->SetFont('times', '', 12);
			if($dataFlag==0)
			{
				$this->Text(90, 21,'Filing Status : Pending');
			}
			else
			{
				$varai = 'Please Mention Token Number. '."\n".'Printout of this receipt is not required with file. ';
				$this->Text(50, 21,$varai );
				
			}
			$this->Cell(180, 0, '', 0, 1);
			//$this->write1DBarcode(2010192, 'I25', 170, 9, 35, 16, 0.4, $style1, 'N');
			$this->Image('Emblem_of_India.png', 8, 8, 18, 18, 'PNG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
			$this->Text(20, 25, '');
			// Colors, line width and bold font
			$this->SetFillColor(255, 0, 0);
			$this->SetTextColor(255);
			$this->SetDrawColor(0, 0, 0);
			$this->SetLineWidth(0.4);
			$this->SetFont('', 'B');
		}
	}
			
	// create new PDF document
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(216, 356), true, 'UTF-8', false);

	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Rajasthan High Court Bench Jaipur');
	$pdf->SetTitle('Copying Reports');
	$pdf->SetSubject('Copying Reports');
	$pdf->SetKeywords('Copying Reports');

	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(10);
	$pdf->SetFooterMargin(10);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	
	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	if (@file_exists(dirname(__FILE__).'/tcpdf/lang/eng.php')) {
			require_once(dirname(__FILE__).'/tcpdf/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

	$pdf->SetFont('times', '', 8);

	// add a page
	$pdf->AddPage();
	
	$pdf->SetMargins(7, 5, 5);
	$pdf->setY(30);
	$pdf->SetTopMargin(35);
		$html='<html><table align="left" cellpadding="3" cellspacing="0"  style="padding-top:10px;">';
		$html .= '
		<tr>
			<td style="font-size:10px;text-align:left;width:15%;"><b>Token No. : </b></td>
			<td style="font-size:9px;text-align:left;width:20%;">'.$TokenNumber.'</td>
			<td style="font-size:10px;text-align:left;width:15%;"><b>Date of Filing : </b></td>
			<td style="font-size:9px;text-align:left;width:20%;">'.$FilingDate.'</td>
			<td style="font-size:10px;text-align:right;width:15%;"><b>Time of Filing : </b></td>
			<td style="font-size:9px;text-align:left;width:15%;">'.$FilingTime.'</td>
		</tr>
		
		<tr>
			<td style="font-size:10px;text-align:left;width:15%;"><b>Case Category : </b></td>
			<td style="font-size:9px;text-align:left;width:20%;">'.$CaseCategory.'</td>
			<td style="font-size:10px;text-align:left;width:15%;"><b>Case Type : </b></td>
			<td style="font-size:9px;text-align:left;width:20%;">'.$CaseType.'</td>
			<td style="font-size:10px;text-align:right;width:15%;"><b>Bench : </b></td>
			<td style="font-size:9px;text-align:left;width:15%;">'.$Bench.'</td>
		</tr>
		
		<tr>
			<td style="font-size:10px;text-align:left;width:15%;"><b>District : </b></td>
			<td style="font-size:9px;text-align:left;width:20%">'.$firDistrict.'</td>
			<td style="font-size:10px;text-align:left;width:15%;"><b>Police Station : </b></td>
			<td style="font-size:9px;text-align:left;width:20%">'.$firStation.'</td>
			<td style="font-size:10px;text-align:right;width:15%;"><b>FIR No. / Year : </b></td>
			<td style="font-size:9px;text-align:left;width:15%;">'.$firNumber." / ".$firYear.'</td>
		</tr>
		
		<tr>
			<td style="font-size:10px;text-align:left;width:15%;"><b>JudgeShip : </b></td>
			<td style="font-size:9px;text-align:left;width:20%;">'.$lcJudgeShip.'</td>
			<td style="font-size:10px;text-align:left;width:15%;"><b>Place : </b></td>
			<td style="font-size:9px;text-align:left;width:20%;">'.$lcPlace.'</td>
			<td style="font-size:10px;text-align:right;width:15%;"><b>Court Name : </b></td>
			<td style="font-size:9px;text-align:left;width:15%;">'.$lcCourtName.'</td>
		</tr>
		
		<tr>
			<td style="font-size:10px;text-align:left;width:15%;"><b>Judge Name : </b></td>
			<td style="font-size:9px;text-align:left;width:20%;">'.$lcJudgeName.'</td>
			<td style="font-size:10px;text-align:left;width:15%;"><b>LC Case Type : </b></td>
			<td style="font-size:9px;text-align:left;width:20%;">'.$lcCaseType.'</td>
			<td style="font-size:10px;text-align:right;width:16%;"><b>Case No. / Year : </b></td>
			<td style="font-size:9px;text-align:left;width:14%;">'.$lcCaseNumber." / ".$lcCaseYear.'</td>
		</tr>
		<tr border="1">
			<td colspan="6" style="border-top: 0.5 solid black;width:97%;padding-left:10px;"></td>
		</tr>
		';						
		$html .= '</table>';
		$html .='<table align="left" border="1" cellpadding="3" cellspacing="0" >';
		$html .= '
		<thead>
		<tr style="background-color:rgb(217, 224, 235);">
			<th style="font-size:10px;text-align:center;width:12%;">Sr No. </th>
			<th style="font-size:10px;text-align:center;width:18%;">Party Description </th>
			<th style="font-size:10px;text-align:center;width:18%;">Party name </th>
			<th style="font-size:10px;text-align:center;width:12%;">Mobile No. </th>
			<th style="font-size:10px;text-align:center;width:5%;">Age</th>
			<th style="font-size:10px;text-align:center;width:7%;">Gender</th>
			<th style="font-size:10px;text-align:center;width:24%;">Address</th>
		</tr></thead><tbody>';		
		for($j=0;$j<count($my_array);$j++)
		{
			$i++;
			$explode_data=explode(";",$my_array[$j]);
			$html .= '<tr nobr="true">
						<td style="font-size:10px;text-align:center;width:12%;">'.$explode_data[0].'</td>
						<td style="font-size:10px;text-align:left;width:18%;">'.$explode_data[1].'</td>
						<td style="font-size:10px;text-align:left;width:18%;">'.$explode_data[2].'</td>
						<td style="font-size:10px;text-align:left;width:12%;">'.$explode_data[3].'</td>
						<td style="font-size:10px;text-align:left;width:5%;">'.$explode_data[4].'</td>
						<td style="font-size:10px;text-align:center;width:7%;">'.$explode_data[5].'</td>
						<td style="font-size:10px;text-align:left;width:24%;">'.$explode_data[6].'</td>
					  </tr>';	
		}		
		$html .="</tbody></table>";
		$html .="</html>";
			//echo $html;
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->Output('example_011.pdf', 'I');



?>