<?php
session_start();
ob_start(); 
require_once('classes/userClass.php');
error_reporting(0);
ini_set('max_input_vars','20000000');
//*********************To Remove Previous PDF File From Folder **********************************************************//
$files = glob('reports/*'); // get all file names
foreach($files as $file)
{ // iterate files
	if(is_file($file))
	{
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		if(($ext == 'pdf') && (time() - filectime($file) > 1))
		{
			unlink($file); // delete file
		}
	}
}
$queryType = $_POST['queryType'];
require_once('tcpdf/config/tcpdf_config.php');
$tcpdf_include_dirs = array(
realpath('tcpdf/tcpdf.php'),
'/usr/share/php/tcpdf/tcpdf.php',
'/usr/share/tcpdf/tcpdf.php',
'/usr/share/php-tcpdf/tcpdf.php',
'/var/www/tcpdf/tcpdf.php',
'/var/www/html/tcpdf/tcpdf.php',
'/usr/local/apache2/htdocs/tcpdf/tcpdf.php'
);
if($queryType=='printWeaherReportData')
{
	$reportData = $_POST['reportData'];
	//print_r($reportData);
	$cityName = trim(addslashes($_POST['cityname']));
	$startDate = trim($_POST['fromDate']);
	$endDate = trim($_POST['toDate']);
	$explodeStartDate = explode(' ',$startDate);
	$explodendDate = explode(' ',$endDate);
		foreach ($tcpdf_include_dirs as $tcpdf_include_path) 
		{
			if (@file_exists($tcpdf_include_path)) 
			{
				require_once($tcpdf_include_path);
				break;
			}
		}
		class MYPDF extends TCPDF 
		{
			public $startDate,$endDate;
			public function setData($startDate,$endDate)
			{
				$this->startDate = $startDate;
				$this->endDate = $endDate;
			}
			//Page header
			public function Header() 
			{
				$fitbox=false;
				$this->Rect(6, 8, 200, 313, 'DF', '', array(255, 255, 255));
				
				//$this->SetLineWidth(0.2);
				$this->Line(6, 28, 206, 28, '');
				//$this->Line(170, 3, 170, 25, $style);
				$this->Line(36, 8, 36, 28, '');
				$this->SetFont('times', 'B', 14);
				$this->Text(75, 10, 'WEATHER REPORT');
				$this->SetFont('times', 'B', 12);
				$this->Text(60, 20, 'From '.$this->startDate.' to '.$this->endDate);
				$this->SetFont('times', 'B', 12,'C');
				$this->Cell(180, 0, '', 0, 1);
				//$this->write1DBarcode(2010192, 'I25', 170, 9, 35, 16, 0.4, $style1, 'N');
				$this->Image('weather.png', 13, 8, 18, 20, 'PNG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
				$this->Text(20, 25, '');
				$this->SetTopMargin(35);
			}
		}		
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(216, 336), true, 'UTF-8', false);
		$pdf->setData($startDate,$endDate);
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Weather Report');
		$pdf->SetTitle('Weather Report');
		$pdf->SetSubject('Weather Report');
		$pdf->SetKeywords('Weather Report');

		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		// remove default header/footer
		$pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);	
		$pdf->SetHeaderMargin(15);
		$pdf->SetFooterMargin(15);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/tcpdf/lang/eng.php')) {
			require_once(dirname(__FILE__).'/tcpdf/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		// ---------------------------------------------------------

		// set font
		$pdf->SetFont('times', '', 8);
		$pdf->SetTopMargin(27);
		$pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
		// add a page
		$pdf->AddPage();
		$pdf->SetMargins(10.5, 5, 5);
		
		$html='<html><head><style>
			.no-border
			{
				border-collapse: collapse;
				border: 1px solid #000;
			}
			</style></head>';
		   $html .= '<table align="center" cellpadding="3" cellspacing="0" border="1" style="padding-top:10px;width:100%;">
					<thead>
						<tr nobr="true">
							<th style="font-size:12px;text-align:left;vertical-align:middle;width:20%;font-weight:bold;">Time</th>
							<th style="font-size:12px;text-align:center;vertical-align:middle;font-weight:bold;width:15%;font-weight:bold;">Weather</th>
							<th style="font-size:12px;text-align:center;vertical-align:middle;font-weight:bold;width:10%;font-weight:bold;">Temperature</th>
							<th style="font-size:12px;text-align:center;vertical-align:middle;font-weight:bold;width:15%;font-weight:bold;">Min/Max Temperature</th>
							<th style="font-size:12px;text-align:center;vertical-align:middle;font-weight:bold;width:10%;font-weight:bold;">Pressure(hPa)</th>
							<th style="font-size:12px;text-align:center;vertical-align:middle;font-weight:bold;width:10%;font-weight:bold;">Air Speed(mps)</th>
							<th style="font-size:12px;text-align:center;vertical-align:middle;font-weight:bold;width:10%;font-weight:bold;">Humadity</th>
						</tr>
					</thead><tbody>';
			foreach($reportData as $row)
			{
				$link=$row['weatherLink'];
				$imageLink = '<img src="'.$link.'" width="50" height="50" style="border-radius:50%" />';
				$html.= '<tr nobr="true">
							<td style="font-size:12px;text-align:left;vertical-align:middle;width:20%;">'.$row['date'].'</td>
							<td style="font-size:12px;text-align:center;vertical-align:middle;font-weight:bold;width:15%;">'.$link.'</td>
							<td style="font-size:12px;text-align:center;vertical-align:middle;font-weight:bold;width:10%;">'.sprintf("%.1f", trim($row['max_temp'])).'&nbsp;<sup>0</sup>C</td>
							<td style="font-size:12px;text-align:center;vertical-align:middle;font-weight:bold;width:15%;">'.sprintf("%.1f", trim($row['max_temp'])).'/'.sprintf("%.1f", trim($row['min_temp'])).'&nbsp;<sup>0</sup>C</td>
							<td style="font-size:12px;text-align:center;vertical-align:middle;font-weight:bold;width:10%;">'.sprintf("%.1f", trim($row['pressure'])).'</td>
							<td style="font-size:12px;text-align:center;vertical-align:middle;font-weight:bold;width:10%;">'.sprintf("%.1f", trim($row['air_speed'])).'</td>
							<td style="font-size:12px;text-align:center;vertical-align:middle;font-weight:bold;width:10%;">'.sprintf("%.1f", trim($row['humidity'])).'&nbsp;%</td>
						</tr>';
			}
			//echo $html;
			$html .='</tbody></table>';
			$html .= '<html>';
			$pdf->writeHTML($html, true, false, true, false, '');
			$output = $pdf->Output(__DIR__ . '/reports/'.'weatherReport.pdf', 'F');
			echo "reports/weatherReport.pdf";
	}
	else
	{
		echo "";
	}

?>