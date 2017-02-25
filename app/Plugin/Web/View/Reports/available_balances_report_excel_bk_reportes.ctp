<?php
	$date = date("dmY_His");
	$fileName = "Reporte Saldos Disponibles_{$date}.xls";

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	
	$styleArrayTitle = array(
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN
			)
		),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '27AAE1')
        ),
	    'font'  => array(
	        'bold'  => true,
	        'color' => array('rgb' => 'FFFFFF'),
	    )
	);
	
	$styleArray = array(
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN
			)
		),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FFFFFF')
        ),
	    'font'  => array(
	        'bold'  => false,
	        'color' => array('rgb' => '000000'),
	    )
	);

	// Create a new worksheet, after the default sheet
	$objPHPExcel->createSheet();
	
	// Create a first sheet, representing sales data
	$objPHPExcel->setActiveSheetIndex(0);
	$specials = array(":", "[", "]", "\\", "\/", "*");

	$title="Saldos Disponibles";
	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle($title);
	$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArrayTitle);
	
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Concesionario');
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Ciudad');
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Privado');
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Pendiente');
	$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Nacional');
	$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Fecha');
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);

	
	foreach ($balances as $keyBalance => $balance) {
		$row = $keyBalance + 1;
		$objPHPExcel->getActiveSheet()->setCellValue('A' . ($row + 1), $balance['Group']['name']);
		$objPHPExcel->getActiveSheet()->setCellValue('B' . ($row + 1), trim($balance['Group']['city']));
		$objPHPExcel->getActiveSheet()->setCellValue('C' . ($row + 1), "$".number_format($balance['HistoricalBalance']['balance'], 0, ',', '.'));
		$objPHPExcel->getActiveSheet()->setCellValue('D' . ($row + 1), "$".number_format($balance['HistoricalBalance']['pending'], 0, ',', '.'));
		$objPHPExcel->getActiveSheet()->setCellValue('E' . ($row + 1), "$".number_format($balance['HistoricalBalance']['nacional'], 0, ',', '.'));
		$dt = new DateTime($balance['HistoricalBalance']['created']);
		$objPHPExcel->getActiveSheet()->setCellValue('F' . ($row + 1), $dt->format('d/m/Y'));

		
		
		$objPHPExcel->getActiveSheet()->getStyle('A' . ($row + 1))->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('B' . ($row + 1))->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('C' . ($row + 1))->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('D' . ($row + 1))->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('E' . ($row + 1))->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('F' . ($row + 1))->applyFromArray($styleArray);

	}
	$objPHPExcel->setActiveSheetIndex(0);
	
	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="' . $fileName . '"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	ob_end_clean();
	$objWriter->save('php://output');
	exit;
?>
