<?php
require realpath(dirname(__FILE__)) . '/../includes/classes/PHPExcel.php';
require realpath(dirname(__FILE__)) . '/../dbconnect.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// create headers
$objPHPExcel->setActiveSheetIndex(0)
			->mergeCells('A1:A2')
			->mergeCells('B1:B2')
			->mergeCells('C1:C2')
			->mergeCells('D1:D2');

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
			->getAlignment()
  		->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->setActiveSheetIndex(0)
			->mergeCells('G1:H1')
			->getStyle('G1:H1')
			->getAlignment()
  		->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->setActiveSheetIndex(0)
			->mergeCells('I1:K1')
			->getStyle('I1:K1')
			->getAlignment()
    		->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->setActiveSheetIndex(0)
 			->getStyle('A1:K2')
 			->applyFromArray(array(
				'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb'=>'ffa07a'),
                ),
                'font' => array(
                	'bold' => true
            	)
            ));


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Sheet Title');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

if(isset($_POST) && count($_POST)){
	$datefrom = strtotime(str_replace('/','-',$_POST['datefrom']));
	$dateto   = strtotime(str_replace('/','-',$_POST['dateto']));

	$query = "SELECT 
		CONCAT(t2.firstname,0x20,t2.lastname) AS description
		, t1.invoicenum
		, t1.total
		, IF(t1.paymentmethod='Paypal','P','B') AS method
		FROM tblinvoices t1 
		JOIN tblclients t2 ON t1.userid=t2.id
		WHERE t1.status='Paid' AND t1.datepaid BETWEEN FROM_UNIXTIME($datefrom) AND FROM_UNIXTIME($dateto)
		ORDER BY t1.datepaid DESC";

	$result = mysql_query($query);

	$worksheet = $objPHPExcel->getActiveSheet();

	$row = 3;
	while($obj = mysql_fetch_object($result)){
		$worksheet->setCellValue('A'.$row, $obj->description)
						  ->setCellValue('B'.$row, $obj->invoicenum)
						  ->setCellValue('C'.$row, $obj->total)
						  ->setCellValue('D'.$row, $obj->method)
						  ->setCellValue('E'.$row, '=IF(D'.$row.'="C",C'.$row.',"")')
						  ->setCellValue('G'.$row, '=IF(D'.$row.'="B",C'.$row.',"")')
						  ->setCellValue('I'.$row, '=IF(D'.$row.'="P",SUM((C'.$row.'*4/100)+C'.$row.'+0.34),"")')
						  ->setCellValue('J'.$row, '=IF(D'.$row.'=IF(D'.$row.'="p",SUM(I'.$row.'-(I'.$row.'*2.7/100)-0.35),"")');
	 	$row++;
	}
}

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="BookTitle.xls"');
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