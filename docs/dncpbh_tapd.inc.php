<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2013 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2013 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.9, 2013-06-02
 */

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../classes/PHPExcel/Classes/PHPExcel.php';

// Create new PHPExcel object
//echo date('H:i:s') , " Create new PHPExcel object" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
//echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("Aditya Nursyahbani")
							 ->setLastModifiedBy("Aditya Nursyahbani")
							 ->setTitle("DNCPBH - TAPD")
							 ->setSubject("DNCPBH TAPD")
							 ->setDescription("Daftar Nominatif Calon Penerima Hibah - TAPD")
							 ->setKeywords("dncpbh")
							 ->setCategory("report");


// Create a first sheet, representing sales data
$objPHPExcel->setActiveSheetIndex(0);

$AS = $objPHPExcel->getActiveSheet();

// Header
$AS->mergeCells('A1:H1');
$AS->setCellValue('A1', 'DAFTAR NOMINATIF CALON PENERIMA BELANJA HIBAH (DNC-PBH)');
$AS->mergeCells('A2:H2');
$AS->setCellValue('A2', 'TAHUN ANGGARAN '.$tahun.'');
$AS->mergeCells('A4:B4');
$AS->setCellValue('A4', 'Nama OPD');
$AS->setCellValue('C4', ': '.$opd_nama.'');
$AS->mergeCells('A5:B5');
$AS->setCellValue('A5', 'Jenis Hibah');
$AS->setCellValue('C5', ': Uang');

// Main Table
$AS->mergeCells('A7:A8');
$AS->setCellValue('A7', 'No.');
$AS->mergeCells('B7:B8');
$AS->setCellValue('B7', 'Nama Jelas Calon Penerima');
$AS->getStyle('B7')->getAlignment()->setWrapText(true); 
$AS->mergeCells('C7:C8');
$AS->setCellValue('C7', 'Alamat Lengkap');
$AS->getStyle('C7')->getAlignment()->setWrapText(true);
$AS->mergeCells('D7:D8');
$AS->setCellValue('D7', 'Rencana Penggunaan');
$AS->getStyle('D7')->getAlignment()->setWrapText(true);
$AS->mergeCells('E7:G7');
$AS->setCellValue('E7', 'Besaran Hibah (Rp)');
$AS->setCellValue('E8', 'Permohonan');
$AS->setCellValue('F8', 'Hasil Evaluasi OPD');
$AS->setCellValue('G8', 'Pertimbangan TAPD');
$AS->getStyle('F8')->getAlignment()->setWrapText(true);
$AS->getStyle('G8')->getAlignment()->setWrapText(true);
$AS->mergeCells('H7:H8');
$AS->setCellValue('H7', 'Keterangan');

$AS->setCellValue('A9', '1');
$AS->setCellValue('B9', '2');
$AS->setCellValue('C9', '3');
$AS->setCellValue('D9', '4');
$AS->setCellValue('E9', '5');
$AS->setCellValue('F9', '6');
$AS->setCellValue('G9', '7');
$AS->setCellValue('H9', '8');

$sql = "SELECT nama,alamat,kelurahan,kecamatan,kota,propinsi,rencana_penggunaan,permohonan,hasil_evaluasi_opd,hasil_evaluasi_tapd,keterangan FROM v_dncpbh_tapd WHERE kode='$kode' and jenis='Uang'";
$result=$db->Execute($sql);

$total_rows = $result->NumRows();
$start_row=10;
$i=0;
while($row=$result->Fetchrow()){
	foreach($row as $key => $val){
		$$key=$val;
	}
    $i++;
	$AS->setCellValue('A'.$start_row.'', $i);
	$AS->setCellValue('B'.$start_row.'', $nama);
	$AS->setCellValue('C'.$start_row.'', ''.$alamat.' Kel. '.ucwords(strtolower($kelurahan)).' Kec. '.ucwords(strtolower($kecamatan)).' '.ucwords(strtolower($kota)).', '.ucwords(strtolower($propinsi)).'');
	$AS->setCellValue('D'.$start_row.'', $rencana_penggunaan);
	$AS->setCellValue('E'.$start_row.'', $permohonan);
	$AS->setCellValue('F'.$start_row.'', $hasil_evaluasi_opd);
	$AS->setCellValue('G'.$start_row.'', $hasil_evaluasi_tapd);
	$AS->setCellValue('H'.$start_row.'', $keterangan);
	
	$start_row++;
}

$AS->mergeCells('A'.$start_row.':D'.$start_row.'');
$AS->setCellValue('A'.$start_row.'', 'Total');
$AS->setCellValue('E'.$start_row.'', '=SUM(E10:E'.($start_row - 1).')');
$AS->setCellValue('F'.$start_row.'', '=SUM(F10:F'.($start_row - 1).')');
$AS->setCellValue('G'.$start_row.'', '=SUM(G10:G'.($start_row - 1).')');

$sql = "SELECT tgl_ba, opd FROM v_dncpbh_tapd WHERE kode='$kode'";
$result=$db->Execute($sql);
$row = $result->FetchRow();

$tgl_row = $start_row + 4;
$AS->mergeCells('E'.$tgl_row.':G'.$tgl_row.'');
$AS->setCellValue('E'.$tgl_row.'', 'Bogor, '.$f->convertdatetime(array("datetime"=>$row['tgl_ba'])).'');

$ttd_row = $start_row + 6;
$ttd_row2 = $start_row + 15;

$AS->mergeCells('A'.$ttd_row.':C'.$ttd_row.'');
$AS->setCellValue('A'.$ttd_row.'', 'Ketua TAPD,');

$AS->mergeCells('E'.$ttd_row.':G'.$ttd_row.'');
$AS->setCellValue('E'.$ttd_row.'', 'Kepala '.$row['opd'].',');

/*
$AS->mergeCells('A'.$ttd_row2.':C'.$ttd_row2.'');
$AS->setCellValue('A'.$ttd_row2.'', 'SEKRETARIS DAERAH KOTA BOGOR,');

$AS->mergeCells('E'.$ttd_row2.':G'.$ttd_row2.'');
$AS->setCellValue('E'.$ttd_row2.'', 'WALIKOTA BOGOR,');
*/


$sql2 = "SELECT nama, nip FROM tbl_penandatanganan WHERE jabatan='KETUA TAPD'";
$result2=$db->Execute($sql2);
$row2 = $result2->FetchRow();

$sql3 = "SELECT opd_kepala, opd_nip FROM tbl_opd WHERE opd_nama='".$row['opd']."'";
$result3=$db->Execute($sql3);
$row3=$result3->Fetchrow();

$AS->mergeCells('A'.($ttd_row + 5).':C'.($ttd_row + 5).'');
$AS->setCellValue('A'.($ttd_row + 5).'', '( '.$row2['nama'].' / NIP. '.$row2['nip'].' )');

$AS->mergeCells('E'.($ttd_row + 5).':G'.($ttd_row + 5).'');
$AS->setCellValue('E'.($ttd_row + 5).'', '( '.strtoupper($row3['opd_kepala']).' / NIP. '.$row3['opd_nip'].' )');

/*
$sql4 = "SELECT nama FROM tbl_penandatanganan WHERE jabatan='SEKRETARIS DAERAH KOTA BOGOR'";
$result4=$db->Execute($sql4);
$row4 = $result4->FetchRow();

$sql5 = "SELECT nama FROM tbl_penandatanganan WHERE jabatan='WALIKOTA BOGOR'";
$result5=$db->Execute($sql5);
$row5 = $result5->FetchRow();

$AS->mergeCells('A'.($ttd_row2 + 5).':C'.($ttd_row2 + 5).'');
$AS->setCellValue('A'.($ttd_row2 + 5).'', ''.$row4['nama'].'');

$AS->mergeCells('E'.($ttd_row2 + 5).':G'.($ttd_row2 + 5).'');
$AS->setCellValue('E'.($ttd_row2 + 5).'', ''.$row5['nama'].'');
*/

// Define Style
$formatJudul = 
array(
	'font'    => array(
		'name'		=> 'Times New Roman',
		'bold'		=> TRUE,
		'size'		=> 12
	),
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	)
);

$formatIsi= 
array(
	'font'    => array(
		'name'		=> 'Times New Roman',
		'size'		=> 10
	)
);

$formatIsiCenter= 
array(
	'font'    => array(
		'name'		=> 'Times New Roman',
		'size'		=> 10
	),
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	)
);

$formatIsiLeft= 
array(
	'font'    => array(
		'name'		=> 'Times New Roman',
		'size'		=> 10
	),
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
	)
);

$formatIsiRight= 
array(
	'font'    => array(
		'name'		=> 'Times New Roman',
		'size'		=> 10
	),
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
	)
);

$formatTableCenter= 
array(
	'font'    => array(
		'name'		=> 'Times New Roman',
		'size'		=> 10
	),
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, 
	),
	'borders' => array(
		'allborders' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THIN,
      			'color' => array(
      				'rgb' => '808080'
      			)
     	)
	)
);

$formatTableCenterI= 
array(
	'font'    => array(
		'name'		=> 'Times New Roman',
		'size'		=> 10,
		'italic'	=> TRUE
	),
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, 
	),
	'borders' => array(
		'allborders' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THIN,
      			'color' => array(
      				'rgb' => '808080'
      			)
     	)
	)
);

$formatTableCenterT= 
array(
	'font'    => array(
		'name'		=> 'Times New Roman',
		'size'		=> 10
	),
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
	),
	'borders' => array(
		'allborders' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THIN,
      			'color' => array(
      				'rgb' => '808080'
      			)
     	)
	)
);

$formatTableLeft= 
array(
	'font'    => array(
		'name'		=> 'Times New Roman',
		'size'		=> 10
	),
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
		'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
	),
	'borders' => array(
		'allborders' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THIN,
      			'color' => array(
      				'rgb' => '808080'
      			)
     	)
	)
);

$formatTableRight= 
array(
	'font'    => array(
		'name'		=> 'Times New Roman',
		'size'		=> 10
	),
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
		'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
	),
	'borders' => array(
		'allborders' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THIN,
      			'color' => array(
      				'rgb' => '808080'
      			)
     	)
	)
);

$AS->getStyle('A1:A2')->applyFromArray($formatJudul);
$AS->getStyle('A4:C5')->applyFromArray($formatIsi);
$AS->getStyle('A7:H8')->applyFromArray($formatTableCenter);

$AS->getStyle('A9:H9')->applyFromArray($formatTableCenterI);

$AS->getStyle('A10:A'.($start_row).'')->applyFromArray($formatTableCenterT);
$AS->getStyle('B10:D'.($start_row).'')->applyFromArray($formatTableLeft);
$AS->getStyle('E10:G'.($start_row).'')->applyFromArray($formatTableRight);
$AS->getStyle('H10:H'.($start_row).'')->applyFromArray($formatTableLeft);

//$AS->getStyle('A'.$start_row.':G'.$start_row.'')->applyFromArray($formatTableLeft);

$AS->getStyle('C10:C'.($start_row).'')->getAlignment()->setWrapText(true);
$AS->getStyle('D10:D'.($start_row).'')->getAlignment()->setWrapText(true);
$AS->getStyle('G10:G'.($start_row).'')->getAlignment()->setWrapText(true);

$AS->getStyle('E10:G'.($start_row).'')->getNumberFormat()->applyFromArray(
	array(
		'code' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1	
	)
);

$AS->getStyle('E'.$tgl_row.'')->applyFromArray($formatIsiCenter);
$AS->getStyle('A'.$ttd_row.':A'.($ttd_row + 5).'')->applyFromArray($formatIsiCenter);
$AS->getStyle('E'.$ttd_row.':E'.($ttd_row + 5).'')->applyFromArray($formatIsiCenter);

/*
$AS->getStyle('A'.$ttd_row2.':A'.($ttd_row2 + 5).'')->applyFromArray($formatJudul);
$AS->getStyle('E'.$ttd_row2.':E'.($ttd_row2 + 5).'')->applyFromArray($formatJudul);
*/

// Set column widths
$AS->getColumnDimension('A')->setWidth(4);
$AS->getColumnDimension('B')->setWidth(20);
$AS->getColumnDimension('C')->setWidth(30);
$AS->getColumnDimension('D')->setWidth(25);
$AS->getColumnDimension('E')->setWidth(13);
$AS->getColumnDimension('F')->setWidth(13);
$AS->getColumnDimension('G')->setWidth(13);
$AS->getColumnDimension('H')->setWidth(20);


// Set page orientation and size
$AS->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$AS->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$AS->getPageSetup()->setFitToPage(TRUE);
$AS->getPageSetup()->setHorizontalCentered(true);

$AS->getPageMargins()->setLeft(0.5);
$AS->getPageMargins()->setRight(0.5);

$AS->setTitle('Uang');

// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1);
$AS2 = $objPHPExcel->getActiveSheet();

// Header
$AS2->mergeCells('A1:H1');
$AS2->setCellValue('A1', 'DAFTAR NOMINATIF CALON PENERIMA BELANJA HIBAH (DNC-PBH)');
$AS2->mergeCells('A2:H2');
$AS2->setCellValue('A2', 'TAHUN ANGGARAN '.$tahun.'');
$AS2->mergeCells('A4:B4');
$AS2->setCellValue('A4', 'Nama OPD');
$AS2->setCellValue('C4', ': '.$opd_nama.'');
$AS2->mergeCells('A5:B5');
$AS2->setCellValue('A5', 'Jenis Hibah');
$AS2->setCellValue('C5', ': Barang / Jasa');

// Main Table
$AS2->mergeCells('A7:A8');
$AS2->setCellValue('A7', 'No.');
$AS2->mergeCells('B7:B8');
$AS2->setCellValue('B7', 'Nama Jelas Calon Penerima');
$AS2->getStyle('B7')->getAlignment()->setWrapText(true); 
$AS2->mergeCells('C7:C8');
$AS2->setCellValue('C7', 'Alamat Lengkap');
$AS2->getStyle('C7')->getAlignment()->setWrapText(true);
$AS2->mergeCells('D7:D8');
$AS2->setCellValue('D7', 'Rencana Penggunaan');
$AS2->getStyle('D7')->getAlignment()->setWrapText(true);
$AS2->mergeCells('E7:G7');
$AS2->setCellValue('E7', 'Besaran Hibah (Rp)');
$AS2->setCellValue('E8', 'Permohonan');
$AS2->setCellValue('F8', 'Hasil Evaluasi OPD');
$AS2->setCellValue('G8', 'Pertimbangan TAPD');
$AS2->getStyle('F8')->getAlignment()->setWrapText(true);
$AS2->getStyle('G8')->getAlignment()->setWrapText(true);
$AS2->mergeCells('H7:H8');
$AS2->setCellValue('H7', 'Keterangan');

$AS2->setCellValue('A9', '1');
$AS2->setCellValue('B9', '2');
$AS2->setCellValue('C9', '3');
$AS2->setCellValue('D9', '4');
$AS2->setCellValue('E9', '5');
$AS2->setCellValue('F9', '6');
$AS2->setCellValue('G9', '7');
$AS2->setCellValue('H9', '8');

$sql = "SELECT nama,alamat,kelurahan,kecamatan,kota,propinsi,rencana_penggunaan,permohonan,hasil_evaluasi_opd,hasil_evaluasi_tapd,keterangan FROM v_dncpbh_tapd WHERE kode='$kode' and (jenis='Barang' or jenis='Jasa')";
$result=$db->Execute($sql);

$total_rows = $result->NumRows();
$start_row=10;
$i=0;

if($total_rows > 0)
{
while($row=$result->Fetchrow()){
	foreach($row as $key => $val){
		$$key=$val;
	}
    $i++;
	$AS2->setCellValue('A'.$start_row.'', $i);
	$AS2->setCellValue('B'.$start_row.'', $nama);
	$AS2->setCellValue('C'.$start_row.'', ''.$alamat.' Kel. '.ucwords(strtolower($kelurahan)).' Kec. '.ucwords(strtolower($kecamatan)).' '.ucwords(strtolower($kota)).', '.ucwords(strtolower($propinsi)).'');
	$AS2->setCellValue('D'.$start_row.'', $rencana_penggunaan);
	$AS2->setCellValue('E'.$start_row.'', $permohonan);
	$AS2->setCellValue('F'.$start_row.'', $hasil_evaluasi_opd);
	$AS2->setCellValue('G'.$start_row.'', $hasil_evaluasi_tapd);
	$AS2->setCellValue('H'.$start_row.'', $keterangan);
	
	$start_row++;
}

$AS2->mergeCells('A'.$start_row.':D'.$start_row.'');
$AS2->setCellValue('A'.$start_row.'', 'Total');
$AS2->setCellValue('E'.$start_row.'', '=SUM(E10:E'.($start_row - 1).')');
$AS2->setCellValue('F'.$start_row.'', '=SUM(F10:F'.($start_row - 1).')');
$AS2->setCellValue('G'.$start_row.'', '=SUM(G10:G'.($start_row - 1).')');

}

$sql = "SELECT tgl_ba, opd FROM v_dncpbh_tapd WHERE kode='$kode'";
$result=$db->Execute($sql);
$row = $result->FetchRow();

$tgl_row = $start_row + 4;
$AS2->mergeCells('E'.$tgl_row.':G'.$tgl_row.'');
$AS2->setCellValue('E'.$tgl_row.'', 'Bogor, '.$f->convertdatetime(array("datetime"=>$row['tgl_ba'])).'');

$ttd_row = $start_row + 6;
$ttd_row2 = $start_row + 15;

$AS2->mergeCells('A'.$ttd_row.':C'.$ttd_row.'');
$AS2->setCellValue('A'.$ttd_row.'', 'Ketua TAPD,');

$AS2->mergeCells('E'.$ttd_row.':G'.$ttd_row.'');
$AS2->setCellValue('E'.$ttd_row.'', 'Kepala '.$row['opd'].',');

/*
$AS2->mergeCells('A'.$ttd_row2.':C'.$ttd_row2.'');
$AS2->setCellValue('A'.$ttd_row2.'', 'SEKRETARIS DAERAH KOTA BOGOR,');

$AS2->mergeCells('E'.$ttd_row2.':G'.$ttd_row2.'');
$AS2->setCellValue('E'.$ttd_row2.'', 'WALIKOTA BOGOR,');
*/


$sql2 = "SELECT nama, nip FROM tbl_penandatanganan WHERE jabatan='KETUA TAPD'";
$result2=$db->Execute($sql2);
$row2 = $result2->FetchRow();

$sql3 = "SELECT opd_kepala, opd_nip FROM tbl_opd WHERE opd_nama='".$row['opd']."'";
$result3=$db->Execute($sql3);
$row3=$result3->Fetchrow();

$AS2->mergeCells('A'.($ttd_row + 5).':C'.($ttd_row + 5).'');
$AS2->setCellValue('A'.($ttd_row + 5).'', '( '.$row2['nama'].' / NIP. '.$row2['nip'].' )');

$AS2->mergeCells('E'.($ttd_row + 5).':G'.($ttd_row + 5).'');
$AS2->setCellValue('E'.($ttd_row + 5).'', '( '.strtoupper($row3['opd_kepala']).' / NIP. '.$row3['opd_nip'].' )');

/*
$sql4 = "SELECT nama FROM tbl_penandatanganan WHERE jabatan='SEKRETARIS DAERAH KOTA BOGOR'";
$result4=$db->Execute($sql4);
$row4 = $result4->FetchRow();

$sql5 = "SELECT nama FROM tbl_penandatanganan WHERE jabatan='WALIKOTA BOGOR'";
$result5=$db->Execute($sql5);
$row5 = $result5->FetchRow();

$AS2->mergeCells('A'.($ttd_row2 + 5).':C'.($ttd_row2 + 5).'');
$AS2->setCellValue('A'.($ttd_row2 + 5).'', ''.$row4['nama'].'');

$AS2->mergeCells('E'.($ttd_row2 + 5).':G'.($ttd_row2 + 5).'');
$AS2->setCellValue('E'.($ttd_row2 + 5).'', ''.$row5['nama'].'');
*/




$AS2->getStyle('A1:A2')->applyFromArray($formatJudul);
$AS2->getStyle('A4:C5')->applyFromArray($formatIsi);
$AS2->getStyle('A7:H8')->applyFromArray($formatTableCenter);

$AS2->getStyle('A9:H9')->applyFromArray($formatTableCenterI);

$AS2->getStyle('A10:A'.($start_row).'')->applyFromArray($formatTableCenterT);
$AS2->getStyle('B10:D'.($start_row).'')->applyFromArray($formatTableLeft);
$AS2->getStyle('E10:G'.($start_row).'')->applyFromArray($formatTableRight);
$AS2->getStyle('H10:H'.($start_row).'')->applyFromArray($formatTableLeft);

//$AS2->getStyle('A'.$start_row.':G'.$start_row.'')->applyFromArray($formatTableLeft);

$AS2->getStyle('C10:C'.($start_row).'')->getAlignment()->setWrapText(true);
$AS2->getStyle('D10:D'.($start_row).'')->getAlignment()->setWrapText(true);
$AS2->getStyle('G10:G'.($start_row).'')->getAlignment()->setWrapText(true);

$AS2->getStyle('E10:G'.($start_row).'')->getNumberFormat()->applyFromArray(
	array(
		'code' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1	
	)
);

$AS2->getStyle('E'.$tgl_row.'')->applyFromArray($formatIsiCenter);
$AS2->getStyle('A'.$ttd_row.':A'.($ttd_row + 5).'')->applyFromArray($formatIsiCenter);
$AS2->getStyle('E'.$ttd_row.':E'.($ttd_row + 5).'')->applyFromArray($formatIsiCenter);

/*
$AS2->getStyle('A'.$ttd_row2.':A'.($ttd_row2 + 5).'')->applyFromArray($formatJudul);
$AS2->getStyle('E'.$ttd_row2.':E'.($ttd_row2 + 5).'')->applyFromArray($formatJudul);
*/

// Set column widths
$AS2->getColumnDimension('A')->setWidth(4);
$AS2->getColumnDimension('B')->setWidth(20);
$AS2->getColumnDimension('C')->setWidth(30);
$AS2->getColumnDimension('D')->setWidth(25);
$AS2->getColumnDimension('E')->setWidth(13);
$AS2->getColumnDimension('F')->setWidth(13);
$AS2->getColumnDimension('G')->setWidth(13);
$AS2->getColumnDimension('H')->setWidth(20);







$AS2->setTitle('Barang');

$objPHPExcel->setActiveSheetIndex(0);
?>