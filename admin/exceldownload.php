<?php

ini_set('display_errors',true);
ini_set('error_reporting',true);

require realpath(dirname(__FILE__)) . '/../includes/classes/PHPExcel.php';
require realpath(dirname(__FILE__)) . '/../dbconnect.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// set document orientation
$objPHPExcel->getActiveSheet()
			->getPageSetup()
			->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

// set default font
$objPHPExcel->getDefaultStyle()
		    ->applyFromArray(array(
                'font' => array(
                	'size' => 10,
                	'name' => 'Arial'
            	),
	        	'alignment' => array(
			        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			    ),
            ));

// set worksheet title and frozen headers
$objPHPExcel->getActiveSheet()
			->freezePane('Z3')
			->setTitle('Sheet 1');

// create headers
$objPHPExcel->setActiveSheetIndex(0)
			->mergeCells('A1:A2')
			->mergeCells('B1:B2')
			->mergeCells('C1:C2')
			->mergeCells('D1:D2');


// set width to column A
$objPHPExcel->setActiveSheetIndex(0)
			->getColumnDimension('A')
			->setWidth(30);

// set width column D			
$objPHPExcel->setActiveSheetIndex(0)
			->getColumnDimension('D')
			->setWidth(5);
// set headers text
$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E1', 'CASSA __ "c"')
			->setCellValue('G1', 'BANCA __ "b"')
			->setCellValue('I1', 'PAYPAL __ "p"')
			->setCellValue('A1', 'Descrizione')
			->setCellValue('B1', 'Fatt. N.')
			->setCellValue('C1', 'Importo')
			->setCellValue('D1', 'Cod.')
			->setCellValue('E2', 'entrate')
			->setCellValue('F2', 'uscite')
			->setCellValue('G2', 'entrate')
			->setCellValue('H2', 'uscite')
			->setCellValue('I2', 'Ent Lordo')
			->setCellValue('J2', 'Ent Netto')
			->setCellValue('K2', 'uscite');


$objPHPExcel->setActiveSheetIndex(0)
			->mergeCells('E1:F1')
			->getStyle('E1:F1')
    		->applyFromArray(array(
                'font' => array(
                	'size' => 12,
            	),
	        	'alignment' => array(
			        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			    ),
			    'borders' => array(
			    	'color' => array('rgb','000000'),
			    	'style' => 'solid'
			    ),
            ));

$objPHPExcel->setActiveSheetIndex(0)
			->mergeCells('G1:H1')
			->getStyle('G1:H1')
			->applyFromArray(array(
                'font' => array(
                	'size' => 12,
            	),
	        	'alignment' => array(
			        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			    ),
            ));
$objPHPExcel->setActiveSheetIndex(0)
			->mergeCells('I1:K1')
			->getStyle('I1:K1')
			->applyFromArray(array(
                'font' => array(
                	'size' => 12,
            	),
	        	'alignment' => array(
			        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			    ),
            ));

$objPHPExcel->setActiveSheetIndex(0)
 			->getStyle('A1:K2')
 			->applyFromArray(array(
				'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb'=>'ffcc99'),
                ),
                'font' => array(
                	'bold' => true,
                	'color' => array('rgb'=>'993314')
            	)
            ));


// normalize font E2:K2
$objPHPExcel->setActiveSheetIndex(0)
			->getStyle('E2:K2')
			->applyFromArray(array(
				'font' => array(
					'bold' => false
				)
			));

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

if(isset($_POST) && count($_POST)){
	$datefrom = strtotime(str_replace('/','-',$_POST['datefrom']));
	$dateto   = strtotime(str_replace('/','-',$_POST['dateto']));

	$query = "SELECT t2.lastname AS description
		, t1.invoicenum
		, t1.total
		, IF(t3.transid!='','P','B') AS method
		FROM tblinvoices t1 
		JOIN tblclients t2 ON t1.userid=t2.id
		JOIN tblaccounts t3 ON t1.id=t3.invoiceid
		WHERE t1.status='Paid' AND t1.datepaid BETWEEN FROM_UNIXTIME($datefrom) AND FROM_UNIXTIME($dateto)
		ORDER BY t1.datepaid DESC,t1.invoicenum ASC";

	if(function_exists('mysql_set_charset')){
		mysql_set_charset('utf8');
	}else{
		mysql_query("SET NAMES utf8");
	}
	$result = mysql_query($query);

	$row = 3;
	$worksheet = $objPHPExcel->getActiveSheet();

	while($obj = mysql_fetch_object($result)){
		$worksheet->setCellValue('A'.$row, $obj->description)
						  ->setCellValue('B'.$row, str_replace('ITH2013-','',$obj->invoicenum))
						  ->setCellValue('C'.$row, $obj->total)
						  ->setCellValue('D'.$row, $obj->method)
						  ->setCellValue('E'.$row, '=IF(D'.$row.'="C",C'.$row.',"")')
						  ->setCellValue('G'.$row, '=IF(D'.$row.'="B",C'.$row.',"")')
						  ->setCellValue('I'.$row, '=IF(D'.$row.'="P",SUM((C'.$row.'*4/100)+C'.$row.'+0.34),"")')
						  ->setCellValue('J'.$row, '=IF(D'.$row.'="p",SUM(I'.$row.'-(I'.$row.'*2.7/100)-0.34),"")');
	 	$row++;
	}
}

// headers decor
$objPHPExcel->getActiveSheet()
			->getStyle('A1:K1')
			->applyFromArray(array(
				'borders' => array(
				    'top' => array(
				      	'style' => PHPExcel_Style_Border::BORDER_THIN,
			    	),
			  	),
			), False);

$objPHPExcel->getActiveSheet()
			->getStyle('A1:A2')
			->applyFromArray(array(
				'borders' => array(
				    'left' => array(
				      	'style' => PHPExcel_Style_Border::BORDER_THIN,
			    	),
			  	),
			), False);
$objPHPExcel->getActiveSheet()
			->getStyle('A3:A'.$objPHPExcel->getActiveSheet()->getHighestRow())
			->applyFromArray(array(
				'borders' => array(
				    'left' => array(
				      	'style' => PHPExcel_Style_Border::BORDER_THIN,
				      	'color' => array('rgb' => '0000FF')
			    	),
			  	),
			), False);

$objPHPExcel->getActiveSheet()
			->getStyle('B1:D2')
			->applyFromArray(array(
				'borders' => array(
				    'right' => array(
				      	'style' => PHPExcel_Style_Border::BORDER_THIN,
			    	),
			  	),
			), False);

$objPHPExcel->getActiveSheet()
			->getStyle('F1:F2')
			->applyFromArray(array(
				'borders' => array(
				    'right' => array(
				      	'style' => PHPExcel_Style_Border::BORDER_THIN,
			    	),
			  	),
			), False);

$objPHPExcel->getActiveSheet()
			->getStyle('H1:H2')
			->applyFromArray(array(
				'borders' => array(
				    'right' => array(
				      	'style' => PHPExcel_Style_Border::BORDER_THIN,
			    	),
			  	),
			), False);

$objPHPExcel->getActiveSheet()
			->getStyle('K1')
			->applyFromArray(array(
				'borders' => array(
				    'right' => array(
				      	'style' => PHPExcel_Style_Border::BORDER_THIN,
			    	),
			  	),
			), False);

$objPHPExcel->getActiveSheet()
			->getStyle('E2:K2')
			->applyFromArray(array(
				'borders' => array(
				    'top' => array(
				      	'style' => PHPExcel_Style_Border::BORDER_THIN,
			    	),
				    'right' => array(
				      	'style' => PHPExcel_Style_Border::BORDER_THIN,
			    	),
			  	),
			), False);

$objPHPExcel->getActiveSheet()
			->getStyle('A3:K'.$objPHPExcel->getActiveSheet()->getHighestRow())
			->applyFromArray(array(
				'borders' => array(
				    'right' => array(
				      	'style' => PHPExcel_Style_Border::BORDER_THIN,
				      	'color' => array(
							'rgb' => '0000FF'
				    	)
			    	),
			    	'bottom' => array(
				      	'style' => PHPExcel_Style_Border::BORDER_THIN,
				      	'color' => array(
							'rgb' => '0000FF'
				    	)
			    	),
			  	),
			), False);

$objPHPExcel->getActiveSheet()
						->getStyle('C3:C'.$objPHPExcel->getActiveSheet()->getHighestRow())
						->getNumberFormat()
						->setFormatCode("#,##0.00");
$objPHPExcel->getActiveSheet()
						->getStyle('E3:E'.$objPHPExcel->getActiveSheet()->getHighestRow())
						->getNumberFormat()
						->setFormatCode("#,##0.00");
$objPHPExcel->getActiveSheet()
						->getStyle('G3:G'.$objPHPExcel->getActiveSheet()->getHighestRow())
						->getNumberFormat()
						->setFormatCode("#,##0.00");
$objPHPExcel->getActiveSheet()
						->getStyle('I3:I'.$objPHPExcel->getActiveSheet()->getHighestRow())
						->getNumberFormat()
						->setFormatCode("#,##0.00");
$objPHPExcel->getActiveSheet()
						->getStyle('J3:J'.$objPHPExcel->getActiveSheet()->getHighestRow())
						->getNumberFormat()
						->setFormatCode("#,##0.00");

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.date('d-m-Y').'.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;