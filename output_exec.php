<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
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
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** Error reporting */
error_reporting( E_ALL );
ini_set( 'display_errors', true );
ini_set( 'display_startup_errors', true );



define( 'EOL', ( PHP_SAPI == 'cli' ) ? PHP_EOL : '<br />' );

date_default_timezone_set( 'Asia/Kolkata' );

/** PHPExcel_IOFactory */
//require_once 'Classes/PHPExcel/Writer/Excel2007.php';
require_once dirname( __FILE__ ) . '/Classes/PHPExcel/IOFactory.php';
//require_once 'Classes/PHPExcel.php';

$rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
$rendererLibrary = 'DomPDF.php';
$rendererLibraryPath = dirname(__FILE__).'/Classes/PHPExcel/Writer/PDF/' . $rendererLibrary;

if (!PHPExcel_Settings::setPdfRenderer(
        $rendererName,
        $rendererLibraryPath
    )) {
    die(
        'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
        '<br />' .
        'at the top of this script as appropriate for your directory structure'
    );
}


//$rendererLibraryPath = dirname(__FILE__). 'Classes/PHPExcel/Writer/' . $rendererLibrary;

echo date( 'H:i:s' ), " Load from Excel5 template", EOL;
$objReader = PHPExcel_IOFactory::createReader( 'Excel5' );
$objPHPExcel = $objReader->load( "templates/Contract_Labour_Register.xls" );
/* Read sheet name from workbook*/
$excelReader = PHPExcel_IOFactory::createReaderForFile( "templates/Contract_Labour_Register.xls" );
$excelObj = $excelReader->load( "templates/Contract_Labour_Register.xls" );
//$worksheet = $excelObj->getSheet(0);
$worksheet = $excelObj->getSheetNames();
echo "<pre>";
print_r( $worksheet );
//die();

// ob_start();
// session_start();
include_once("dbcon.php");

if(isset($_POST['submit']))
{
	echo $customer_code=$_POST['company_name'];
	$query = "SELECT * FROM customer_master where customer_code='$customer_code'";
	$customer_info = mysqli_query($con,$query);
	while($row= mysqli_fetch_array($customer_info))
		{
			 $company_name=$row['customer_name'];
			 $company_address=$row['customer_address'];
			 $company_branch=$row['customer_branch'];	
		}
}
$Date_of_commencement_of_employment=$_POST['Date_of_commencement_of_employment'];
$company=$company_name.",".$company_address;
//$date = date( 'MY' );
$csv_file = $_FILES[ "file" ][ "tmp_name" ];

if ( ( $getfile = fopen( $csv_file, "r" ) ) !== FALSE ) {
			$data = fgetcsv( $getfile, 5000, "," );
			$entries = array();
			$num = 0;
			$start_row = 5; //define start row
			$i = 1; //define row count flag
			while ( ( $data = fgetcsv( $getfile, 5000, "," ) ) !== FALSE ) {
				if ( $i >= $start_row ) {
					$result4[] = $data;
				}
				$i++;
			}
			echo $date=$result4[1][11];
}
$file_name= $stripped = str_replace(' ', '_', $company_name)."-".str_replace(' ', '_', $company_branch)."-".$date."-".time();
echo $created_file_name="Download/".$file_name.".xls";

//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C22:G22');  // marge cell 

if($worksheet[0] == "PF IW1 Return Slip"){
		$objPHPExcel->getSheet(0)
			->getCell( 'C22' )
			->setValue( "NIL for the month of " . $date );
		// $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
		// $objWriter->save( $created_file_name );
		   $objWriter = new PHPExcel_Writer_PDF($objPHPExcel);
		   $objWriter->save("05featuredemo.pdf");
			// $objWriter->writeAllSheets();
			// $outputFileName  = 'sample';
			// header('Content-type:Application/pdf');
			// header("Content-Disposition:attachment;filename=".$outputFileName.".pdf" );
			// header('Cache-Control: max-age=0');
			// $objWriter->save('php://output');
	}
if($worksheet[2] == "Accident Register" ){
		$objPHPExcel->getSheet(2)
			->getCell( 'D15' )
			->setValue( "NIL for the month of " . $date );
		$objPHPExcel->getSheet(2)
			->getCell( 'H8' )
			->setValue( "Name and address of establishment in/under which contract is carried on :  " . $company);
		$objPHPExcel->getSheet(2)
			->getCell( 'H9' )
			->setValue( "Name and address of establishment in/under which contract is carried on :  " . $company);
		$objPHPExcel->getSheet(2)
			->getCell( 'A9' )
			->setValue( "Name and Location of Work:  " . $company_branch);
		$objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
		$objWriter->save($created_file_name);
	}
if ($worksheet[4] == "Maternity Register"){
	$objPHPExcel->getSheet(4)
		->getCell('C14')
		->setValue( "NIL for the month of " . $date );
	$objPHPExcel->getSheet(4)
			->getCell('H8')
			->setValue( "Name and address of establishment in/under which contract is carried on :  " . $company);
		$objPHPExcel->getSheet(4)
			->getCell('H9')
			->setValue( "Name and address of establishment in/under which contract is carried on :  " . $company);
		$objPHPExcel->getSheet(4)
			->getCell( 'A9' )
			->setValue( "Name and Location of Work:  " . $company_branch);
	$objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
	$objWriter->save( $created_file_name );
}
if ($worksheet[5] == "Deduction Register"){
	$objPHPExcel->getSheet(5)
		->getCell('C16')
		->setValue( "NIL for the month of " . $date );
	$objPHPExcel->getSheet(5)
			->getCell( 'H8' )
			->setValue( "Name and address of establishment in/under which contract is carried on :  " . $company);
	$objPHPExcel->getSheet(5)
			->getCell( 'H9' )
			->setValue( "Name and address of establishment in/under which contract is carried on :  " . $company);
	$objPHPExcel->getSheet(5)
			->getCell( 'A9' )
			->setValue( "Name and Location of Work:  " . $company_branch);
	$objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
	$objWriter->save( $created_file_name );
}
if ($worksheet[6] == "Fines Register"){
	$objPHPExcel->getSheet(6)
		->getCell( 'C15' )
		->setValue( "NIL for the month of " . $date );
	$objPHPExcel->getSheet(6)
			->getCell( 'H8' )
			->setValue( "Name and address of establishment in/under which contract is carried on :  " . $company);
	$objPHPExcel->getSheet(6)
			->getCell( 'H9' )
			->setValue( "Name and address of establishment in/under which contract is carried on :  " . $company);
	$objPHPExcel->getSheet(6)
			->getCell( 'A9' )
			->setValue( "Name and Location of Work:  " . $company_branch);
	$objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
	$objWriter->save( $created_file_name );
}
if($worksheet[7] == "Workmen Register"){
	$objPHPExcel->getSheet(7)
			->getCell( 'H9' )
			->setValue( "Name and address of establishment in/under which contract is carried on :  " . $company);
	$objPHPExcel->getSheet(7)
			->getCell( 'H10' )
			->setValue( "Name and address of establishment in/under which contract is carried on :  " . $company);
	$objPHPExcel->getSheet(7)
			->getCell( 'A10' )
			->setValue( "Name and Location of Work:  " . $company_branch);
	$objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
	$objWriter->save( $created_file_name );
}
if($worksheet[9] == "Advance Register"){
	$objPHPExcel->getSheet(9)
		->getCell( 'D16' )
		->setValue( "NIL for the month of " . $date );
	$objPHPExcel->getSheet(9)
					->getCell('G5')
					->setValue( "Name and address of establishment in/under which contract is carried on :  " . $company);
	$objPHPExcel->getSheet(9)
					->getCell('G9')
					->setValue( "Name and address of establishment in/under which contract is carried on :  " . $company);
	$objPHPExcel->getSheet(9)
					->getCell('A9')
					->setValue( "Name and Location of Work:  " . $company_branch);
	$objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
	$objWriter->save( $created_file_name );
}

if($worksheet[1] == "Leave Register")
{
		$objPHPExcel->getSheet(1)
					->getCell('J7')
					->setValue( "Name and address of establishment in/under which contract is carried on :  " . $company);
		$objPHPExcel->getSheet(1)
					->getCell('J8')
					->setValue( "Name and address of establishment in/under which contract is carried on :  " . $company);
		$objPHPExcel->getSheet(1)
					->getCell('C8')
					->setValue( "Name and Location of Work:  " . $company_branch);
		if ( ( $getfile = fopen( $csv_file, "r" ) ) !== FALSE ) {
			$data = fgetcsv( $getfile, 5000, "," );
			$entries = array();
			$start_row = 5; //define start row
			$i = 1; //define row count flag
			while ( ( $data = fgetcsv( $getfile, 5000, "," ) ) !== FALSE ) {
				//$company_info[]=$data;
				if ( $i >= $start_row ) {
					$result[] = $data;
				}
				$i++;
			}
			
		$baseRow = 13;
		$row=$baseRow;
		foreach ( $result as $entry ){
			$datas = array( array( 'sr_no' => $entry[ 0 ],
				'emp_id' => $entry[ 1 ],
				'name' => $entry[ 2 ],
				'Date_of_entry_into_service' => $entry[ 10],
				'Month_Year' => $entry[ 11 ],
				'No_of_days_work_of_performed' => $entry[ 12 ],
				'No_of_days_Lay' => 0,
				'No_of_days_of_maternity_leave_with_wages' => 0,
				'No_of_days_Leave_with_wages_enjoyed' => 0,
				'Balance_of_leave_with_wages_from_preceding_year' => $entry[ 13 ],
				'Leave_with_wages_earned_during_the_year_mentioned_in_col-9' => 0,
				'Whether_leave_with_wages_refused_in_accordance_with_scheme_under_Sec_79(8)' => 0,
				'Whether_leave_with_wages_not_desired_during_the_next_calendar_year' => 0,
				'Leave_with_wages_enjoyed_From' => 0,
				'Leave_with_wages_enjoyed_To' => 0,
				'Balance_to_credit' => 0,
				'Normal_rate_of_wages' => '',
				'Cash_equivalent_or_accruing_through_concessional_sale_of_food_grains_or_other_articles' => 0,
				'Date_of_Discharge' => 0,
				'Date_of_amount_of_payment_made_in_lieu_of_leave_with_wages_due' => 'Monthly Paid',
				'Remarks' => ''
			) );
			
			foreach ( $datas as $dataRow ){
				$objPHPExcel->getSheet(1)->insertNewRowBefore( $row, 1);
				$objPHPExcel->getSheet(1)->setCellValue( 'A' . $row, $dataRow[ 'sr_no' ] )
					->setCellValue( 'B' . $row, $dataRow[ 'emp_id' ] )
					->setCellValue( 'C' . $row, $dataRow[ 'name' ] )
					->setCellValue( 'D' . $row, $dataRow[ 'Date_of_entry_into_service' ] )
					->setCellValue( 'E' . $row, $dataRow[ 'Month_Year' ] )
					->setCellValue( 'F' . $row, $dataRow[ 'No_of_days_work_of_performed' ] )
					->setCellValue( 'G' . $row, $dataRow[ 'No_of_days_Lay' ] )
					->setCellValue( 'H' . $row, $dataRow[ 'No_of_days_of_maternity_leave_with_wages' ] )
					->setCellValue( 'I' . $row, $dataRow[ 'No_of_days_Leave_with_wages_enjoyed' ] )
					->setCellValue( 'J' . $row, $dataRow[ 'No_of_days_work_of_performed' ] )
					->setCellValue( 'K' . $row, $dataRow[ 'Balance_of_leave_with_wages_from_preceding_year' ] )
					->setCellValue( 'L' . $row, $dataRow[ 'Leave_with_wages_earned_during_the_year_mentioned_in_col-9' ] )
					->setCellValue( 'M' . $row, $dataRow[ 'Balance_of_leave_with_wages_from_preceding_year' ] )
					->setCellValue( 'N' . $row, $dataRow[ 'Whether_leave_with_wages_refused_in_accordance_with_scheme_under_Sec_79(8)' ] )
					->setCellValue( 'O' . $row, $dataRow[ 'Whether_leave_with_wages_not_desired_during_the_next_calendar_year' ] )
					->setCellValue( 'P' . $row, $dataRow[ 'Leave_with_wages_enjoyed_From' ] )
					->setCellValue( 'Q' . $row, $dataRow[ 'Leave_with_wages_enjoyed_To' ] )
					->setCellValue( 'R' . $row, $dataRow[ 'Balance_to_credit' ] )
					->setCellValue( 'S' . $row, $dataRow[ 'Normal_rate_of_wages' ] )
					->setCellValue( 'T' . $row, $dataRow[ 'Cash_equivalent_or_accruing_through_concessional_sale_of_food_grains_or_other_articles' ] )
					->setCellValue( 'U' . $row, $dataRow[ 'Cash_equivalent_or_accruing_through_concessional_sale_of_food_grains_or_other_articles' ] )
					->setCellValue( 'V' . $row, $dataRow[ 'Date_of_Discharge' ] )
					->setCellValue( 'W' . $row, $dataRow[ 'Date_of_amount_of_payment_made_in_lieu_of_leave_with_wages_due' ] )
					->setCellValue( 'X' . $row, $dataRow[ 'Remarks' ] );
				}
			 	$row=$row+1;
		}
		$objPHPExcel->getSheet(1)->removeRow( $baseRow - 1, 1 );
		}
		fclose($getfile);
		$objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
		$objWriter->save( $created_file_name);
}

if($worksheet[8] == "Overtime Register")
{
		$objPHPExcel->getSheet(8)
					->getCell( 'I7' )
					->setValue( "Name and address of establishment in/under which contract is carried on :  " . $company);
		$objPHPExcel->getSheet(8)
					->getCell( 'I8' )
					->setValue( "Name and address of establishment in/under which contract is carried on :  " . $company);
		$objPHPExcel->getSheet(8)
					->getCell( 'B8' )
					->setValue( "Name and Location of Work:  " . $company_branch);
		if ( ( $getfile = fopen( $csv_file, "r" ) ) !== FALSE ) {
			$data = fgetcsv( $getfile, 5000, "," );
			$entries = array();
			$num = 0;
			$start_row = 5; //define start row
			$i = 1; //define row count flag
			while ( ( $data = fgetcsv( $getfile, 5000, "," ) ) !== FALSE ) {
				if ( $i >= $start_row ) {
					$result2[] = $data;
				}
				$i++;
			}
			$baseRow = 12;
			$rs=$baseRow;
			$current_date=date("Y-m-d");
			foreach ( $result2 as $entry ){
				$datas = array( array( 'sr_no' => $entry[ 0 ],
					'emp_id' => $entry[ 1 ],
					'Name_of_Workman' => $entry[ 2 ],
					'Husbands_Name' => $entry[ 5],
					'Gender' => $entry[ 4 ],
					'Designation' => $entry[ 6 ],
					'Dates_of_which_Overtime_Worked' => 0,
					'Total_Overtime_worked_or_production_in_case_of_piece_rated' => $entry[ 17 ],
					'Normal_rates_of_wages' => $entry[ 18 ],
					'Overtime_rate_of_wages' => $entry[ 19 ],
					'Overtime_Earnings' => $entry[ 20 ],
					'Date_on_which_overtime_wages_paid' => $current_date,
					'Remarks' => ''
				) );
			foreach ( $datas as $dataRow ){
			$objPHPExcel->getSheet(8)->insertNewRowBefore( $rs, 1 );
			$objPHPExcel->getSheet(8)->setCellValue( 'B' . $rs, $dataRow[ 'sr_no' ] )
				->setCellValue( 'C' . $rs, $dataRow[ 'emp_id' ] )
				->setCellValue( 'D' . $rs, $dataRow[ 'Name_of_Workman' ] )
				->setCellValue( 'E' . $rs, $dataRow[ 'Husbands_Name' ] )
				->setCellValue( 'F' . $rs, $dataRow[ 'Gender' ] )
				->setCellValue( 'G' . $rs, $dataRow[ 'Designation' ] )
				->setCellValue( 'H' . $rs, $dataRow[ 'Dates_of_which_Overtime_Worked' ] )
				->setCellValue( 'I' . $rs, $dataRow[ 'Total_Overtime_worked_or_production_in_case_of_piece_rated' ] )
				->setCellValue( 'J' . $rs, $dataRow[ 'Normal_rates_of_wages' ] )
				->setCellValue( 'K' . $rs, $dataRow[ 'Overtime_rate_of_wages' ] )
				->setCellValue( 'L' . $rs, $dataRow[ 'Overtime_Earnings' ] )
				->setCellValue( 'Z' . $rs, $dataRow[ 'Remarks' ] );	
				}
				$rs = $rs + 1;
			}
			$objPHPExcel->getSheet(8)->removeRow( $baseRow - 1, 1 );
		}
		fclose($getfile);
		$objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
		$objWriter->save( $created_file_name);
}

if($worksheet[3] == "HRA Register")
{
		$objPHPExcel->getSheet(3)
					->getCell( 'D8' )
					->setValue( "Name and address of establishment in/under which contract is carried on :  " . $company);
		$objPHPExcel->getSheet(3)
					->getCell( 'D9' )
					->setValue( "Name and address of establishment in/under which contract is carried on :  " . $company);
		$objPHPExcel->getSheet(3)
					->getCell( 'A9' )
					->setValue( "Name and Location of Work:  " . $company_branch);
		if ( ( $getfile = fopen( $csv_file, "r" ) ) !== FALSE ) {
			$data = fgetcsv( $getfile, 5000, "," );
			$entries = array();
			$num = 0;
			$start_row = 5; //define start row
			$i = 1; //define row count flag
			while ( ( $data = fgetcsv( $getfile, 5000, "," ) ) !== FALSE ) {
				if ( $i >= $start_row ) {
					$result3[] = $data;
				}
				$i++;
			}
			$baseRow = 13;
			$sr=$baseRow;
			$current_date=date("Y-m-d");
			foreach ( $result3 as $entry ){
				$HRA_register = array( array( 'sr_no' => $entry[ 0 ],
					'emp_id' => $entry[ 1 ],
					'Name_of_Workman' => $entry[ 2 ],
					'Month_Year' => $entry[ 11],
					'HRA' => $entry[ 16 ],
					'Method_of_Payment' => 'ACCOUNT PAID',
					'Signature_of_Workmen' => 'ACCOUNT PAID',
					'Remarks' => ''
				) );
			foreach ( $HRA_register as $HRA ){
			$objPHPExcel->getSheet(3)->insertNewRowBefore( $sr, 1 );
			$objPHPExcel->getSheet(3)->setCellValue( 'A' . $sr, $HRA[ 'sr_no' ]  )
				->setCellValue( 'B' . $sr, $HRA[ 'Name_of_Workman' ] )
				->setCellValue( 'C' . $sr, $HRA[ 'Month_Year' ] )
				->setCellValue( 'D' . $sr, $HRA[ 'HRA' ] )
				->setCellValue( 'E' . $sr, $HRA[ 'Method_of_Payment' ] )
				->setCellValue( 'F' . $sr, $HRA[ 'Signature_of_Workmen' ] )
				->setCellValue( 'G' . $sr, $HRA[ 'Remarks' ] );	
				}
				$sr = $sr+1;
			}
			$objPHPExcel->getSheet(3)->removeRow( $baseRow - 1, 1 );
		}
		fclose($getfile);
		$objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
		//$objWriter->save( str_replace( '.php', '.xls', __FILE__ ) );
	$objWriter->save( $created_file_name );
}

if($worksheet[7] == "Workmen Register")
{
		$objPHPExcel->getSheet(7)
					->getCell( 'H9' )
					->setValue( "Name and address of establishment in/under which contract is carried on :  " . $company);
		$objPHPExcel->getSheet(7)
					->getCell( 'H10' )
					->setValue( "Name and address of establishment in/under which contract is carried on :  " . $company);
		$objPHPExcel->getSheet(7)
					->getCell( 'A10' )
					->setValue( "Name and Location of Work:  " . $company_branch);
		if ( ( $getfile = fopen( $csv_file, "r" ) ) !== FALSE ) {
			$data = fgetcsv( $getfile, 5000, "," );
			$entries = array();
			$num = 0;
			$start_row = 5; //define start row
			$i = 1; //define row count flag
			while ( ( $data = fgetcsv( $getfile, 5000, "," ) ) !== FALSE ) {
				if ( $i >= $start_row ) {
					$result3[] = $data;
				}
				$i++;
			}
			$baseRow = 14;
			$wr=$baseRow;
			$current_date=date("Y-m-d");
			foreach ( $result3 as $entry ){
				$Workmen_register = array( array( 'sr_no' => $entry[ 0 ],
					'emp_id' => $entry[ 1 ],
					'Name_of_Workman' => $entry[ 2 ],
					'age' => $entry[ 3],
					'father_name' => $entry[ 5],
					'Designation' => $entry[ 6],
					'Permanent_address' => $entry[ 7],
					'Local_address' => $entry[ 8],
					'Date_of_commencement_of_employment' => $Date_of_commencement_of_employment,
					'Signature_Or_thumb_Impression_of_workman' => '',
					'Date_of_termination_of_employment' => '',
					'Reasons_for_termination' => '',
					'Remarks' => ''				 
				) );
			foreach ($Workmen_register as $Workmen ){
			$objPHPExcel->getSheet(7)->insertNewRowBefore( $wr, 1 );
			$objPHPExcel->getSheet(7)->setCellValue( 'A' . $wr, $Workmen[ 'sr_no' ]  )
				->setCellValue( 'B' . $wr, $Workmen[ 'emp_id' ] )
				->setCellValue( 'C' . $wr, $Workmen[ 'Name_of_Workman' ] )
				->setCellValue( 'D' . $wr, $Workmen[ 'age' ] )
				->setCellValue( 'E' . $wr, $Workmen[ 'father_name' ] )
				->setCellValue( 'F' . $wr, $Workmen[ 'Designation' ] )
				->setCellValue( 'G' . $wr, $Workmen[ 'Permanent_address' ] )
				->setCellValue( 'H' . $wr, $Workmen[ 'Local_address' ] )
				->setCellValue( 'I' . $wr, $Workmen[ 'Date_of_commencement_of_employment' ] )
				->setCellValue( 'J' . $wr, $Workmen[ 'Signature_Or_thumb_Impression_of_workman' ] )
				->setCellValue( 'K' . $wr, $Workmen[ 'Date_of_termination_of_employment' ] )
				->setCellValue( 'L' . $wr, $Workmen[ 'Reasons_for_termination' ] )
				->setCellValue( 'M' . $wr, $Workmen[ 'Remarks' ] );	
				}
				$wr = $wr+1;
			}
			$objPHPExcel->getSheet(7)->removeRow( $baseRow - 1, 1 );
		}
		fclose($getfile);
		$objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
		//$objWriter->save( str_replace( '.php', '.xls', __FILE__ ) );
	$objWriter->save( $created_file_name );
}
 $sql="INSERT INTO `download_file`(`file_name`) VALUES ('$file_name')";

		 echo $sql."<br>";
		//$result=mysql_query($sql) or die(mysql_error());
		$result=mysqli_query($con,$sql) or die(mysqli_error($con));
header("location:download_file.php");