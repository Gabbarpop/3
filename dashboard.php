<?php
ob_start();
session_start();
if(isset($_SESSION))
{
include_once("dbcon.php");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Welcome to Briny Group Pvt. Ltd.</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
<link href="css/master.css" rel="stylesheet" type="text/css">
<link href="css/tab.css" rel="stylesheet" type="text/css">
<link href="css/style_demo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
    <script type="text/javascript"> 
		$(document).ready( function() {
        $('#error_box').delay(2000).fadeOut();
      });
 	document.getElementById("upload_File").onsubmit = function() {
				submitForm();
		};
      function submitForm() {
  		return confirm('Do you really want to Upload File?');
	  	}
		
	if(form.input_file.value.toLowerCase().lastIndexOf(".csv")==-1) 
	 {
		alert("Please upload only .csv extention file");
		form.input_file.focus(); 
		return false;
	}
    </script>
</head>

<body>
<div id="updiv"><!--updiv start-->
<div id="header-main"><!--header-main start-->
<div id="master_header"><!--master_header start-->
<div class="top_menu">
<a href="logout.php">Logout</a>
</div>
</div><!--master_header end-->
</div><!--header-main end-->
</div><!--updiv end-->
<div  id="middle_info_main"><!--middle_info_main  start-->
<div id="middle_info"><!--middle_info start-->
<div class="middle_info_left"><!--middle_info_left start-->
<div class="profile_pic"><!--profile_pic start-->
<img src="images/logo.png" >
</div><!--profile_pic end-->
<?php
include_once("left_side_bar.php");
?>
</div><!--middle_info_left end-->
<div class="middle_info_middle"><!--middle_info_middle start-->
<h1>Briny Group Pvt. Ltd.</h1>
<h2>
Compliance Report
</h2>
<div class="post"><!--post start-->
<ul style="list-style:none;">
</ul>
	<form  action="op-exec.php" method="post" enctype="multipart/form-data" class="registration_form" name="upload_File">
	 <ul>
		    <li>
            	<label for="company_name" style="width: 350px;">Select Customer Code&nbsp;<span class="red" >*</span></label>
				<select name="company_name" id="company_name" required >
				 <option value="-1">None</option>
				 <?php
				  	$qry = "SELECT * FROM customer_master";
					$result = mysqli_query($con,$qry);
					//echo $qry_grade;
					//exit();
					while($row= mysqli_fetch_array($result))
					{
						//echo "<option value='".$row_grade['customer_code']."'>".$row_grade['customer_name']."</option>";
						 echo "<option value='" . $row['customer_code'] . "'>" . $row['customer_code'] . " - ".$row['customer_name']."</option>";
					}
				 ?>

				<!-- <input type="text" value="<?php echo $row['customer_name']; ?>"  name="customer_code" id="customer_code">-->
				 </select>
            </li>
		 
			   <li>
				  <label for="no_of_student" style="width: 350px;">Date on which overtime wages paid &nbsp;<span class="red">*</span></label>
				   <input type="date" name="Date_of_commencement_of_employment" id="Date_of_commencement_of_employment" required>
				</li>
			  
				<li>
					  <label for="Company_code"style="width: 350px;">Upload file &nbsp;<span class="red">*</span></label>
					   <input type="file" accept=".csv" name="file" id="file" required  >
						<span class="red">Note : Upload only .csv file.</span>
						
				</li>
				<li>
					<!--	<h4>Click on Get Status & wait for 3 Min to Download file.</h4>-->
					   <button class="submit" type="submit" name="submit">Validate File</button>
				</li>
        </ul>
				<?php
				if(@$_REQUEST['error']=="empty_file_name")
				{
				?>
				 <li class="registration_form_error" style="list-style: none;"  id="error_box">
    				<?php echo "File input should not be empty.";?>
    			</li>
				
			<?php	
				}
				if(@$_REQUEST['error']=="no_of_coloum")
				{
				?>
				 <li class="registration_form_error" style="list-style: none;"  id="error_box">
    				<?php echo "Invalid CSV:  Numbar of coloum missmatch.";?>
    			</li>
				
			<?php	
				}
				if(@$_REQUEST['error']=="file_extension")
				{
				?>
				 <li class="registration_form_error" style="list-style: none;"  id="error_box">
    				<?php echo "Invalid CSV: File must have .csv extension.";?>
    			</li>
				
			<?php	
				}
				if(@$_REQUEST['error']=="duplicate_file_name")
				{
				?>
				 <li class="registration_form_error" style="list-style: none;"  id="error_box">
    				<?php echo "File Name allready Exists.";?>
    			</li>
				
			<?php	
				}
					if(@$_REQUEST['error']=="no_of_row")
				{
						?>
				 <li class="registration_form_error" style="list-style: none;"  id="error_box">
    				<?php echo "Invalid CSV:  Numbar of rows missmatch.";?>
    			</li>
			<?php
					}
					if(@$_REQUEST['error']=="low_blance")
				{
						?>
				 <li class="registration_form_error" style="list-style: none;"  id="error_box">
    				<?php echo "Youe Account blance is low. So Kindly recharge your account.";?>
    			</li>
			<?php
					}
				if(@$_REQUEST['error']=="format_missmatch")
				{
						?>
				 <li class="registration_form_error" style="list-style: none;"  id="error_box">
    				<?php echo "Uploaded file format missmatch";?>
    			</li>
			<?php
				}
			if(@$_REQUEST['error']=="IFSC_notfound")
				{
						?>
				 <li class="registration_form_error" style="list-style: none;"  id="error_box">
    			<?php
					echo "IFSC code not found";
				$ErrorFileName=basename($_SESSION["ErrorFileName"]).PHP_EOL;
					?>
					 <a href="Invalide/<?php echo $ErrorFileName;?>" download>click here to download</a>
    			</li>
			<?php
				}
			if(@$_REQUEST['sucessful']=="filevalidate")
				{
						?>
				 <li class="registration_form_sucessfully" style="list-style: none;"  id="error_box">
    			<?php
					echo "File Validate Sucessfully.";
					?>
			<?php
				}
			?>
	</form>
</div>
<!--post start-->
</div><!--middle_info_middle end-->


</div><!--middle_info_main  end-->

<div id="footer-main"><!--footer-main star-->
<div id="footer" align="center"><!--footer start-->
*Privacy Policy: By Selecting this box you Agree that Briny Group Pvt. Ltd., may use your name, email address, telephone number or other data to communicate with you either by itself or through any of its designates. To have this information modified or deleted from our records at any time, please write to info@svsoft.in Talensetu Services Pvt. Ltd. assures you that it will not sell this information to any other third party. © Copyright 2020. Briny Group Pvt. Ltd. 
</div><!--footer end-->
</div><!--footer-main end-->
</body>
</html>
<?php
}
else
header("location:index.php");
?>
