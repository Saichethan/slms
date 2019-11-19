<?php
$count =1;
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['emplogin'])==0)
    {
header('location:index.php');
}
else{
if(isset($_POST['apply']))
{
$empid=$_SESSION['eid'];
$hostelno=$_POST['HostelNumber'];
 $leavetype=$_POST['leavetype'];
$fromdate=$_POST['fromdate'];
$todate=$_POST['todate'];
$description=$_POST['description'];
$status=0;
$isread=0;
if($fromdate > $todate){
                $error=" ToDate should be greater than FromDate ";
           }
$sql="INSERT INTO tblleaves(LeaveType,HostelNumber,ToDate,FromDate,Description,Status,IsRead,empid) VALUES(:leavetype,:hostelno,:fromdate,:todate,:description,:status,:isread,:empid)";
$query = $dbh->prepare($sql);
$query->bindParam(':leavetype',$leavetype,PDO::PARAM_STR);
$query->bindParam(':hostelno',$hostelno,PDO::PARAM_STR);
$query->bindParam(':fromdate',$fromdate,PDO::PARAM_STR);
$query->bindParam(':todate',$todate,PDO::PARAM_STR);
$query->bindParam(':description',$description,PDO::PARAM_STR);
$query->bindParam(':status',$status,PDO::PARAM_STR);
$query->bindParam(':isread',$isread,PDO::PARAM_STR);
$query->bindParam(':empid',$empid,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
$temp1=$_SESSION['emplogin'];
if($lastInsertId)
{
$msg=" Leave applied successfully";
//$msg1=" Leave applied successfully $temp1";
//mail($temp1,$msg,$msg1);
$msg11=1;

$msga =" Request for Leave approval";
$msga1 = "$temp1 is requesting for leave from $fromdate to $todate";
//mail("",$msga,$msga1);
}
else
{
$error="Something went wrong. Please try again";
}


$em=$_SESSION['emplogin'];
//$sql = "SELECT FirstName,LastName,EmpId,id from  tblemployees and tbleaves where EmailId=:em";
$sql = "SELECT FirstName,LastName,tblemployees.EmpId as EmpId,tblleaves.id as id from tblemployees join tblleaves on tblemployees.id = tblleaves.empid and tblemployees.EmailId=:em order by tblleaves.id desc limit 1";

$query = $dbh -> prepare($sql);
$query->bindParam(':em',$em,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{
        //echo $result->FirstName." ".$result->LastName;
        //echo $result->id." ".$result->EmpId;
        $fn = $result->FirstName;
        $ln = $result->LastName;
        $EmpId = $result->EmpId;
        $refno = $result->id;
    }
}

//echo $fn." ".$ln." ".$EmpId." ".$refno;

if($EmpId>170000000){
    if($EmpId<180000000){ $wardenEmail="miriyala.cse.1725@iiitbh.ac.in";}
    else if($EmpId>180000000){
        if($EmpId<190000000){$wardenEmail="hemant.cse.1715@iiitbh.ac.in";}
        else if($EmpId>190000000){
            if($EmpId<200000000){$wardenEmail="3@iiitbh.ac.in";}
        }
    }
}


if($hostelno==1){$wardenEmail="iiitbh.ac.in";}
if($hostelno==2){$wardenEmail="iiitbh.ac.in";}
if($hostelno==3){$wardenEmail="iiitbh.ac.in";}
if($hostelno==4){$wardenEmail="iiitbh.ac.in";}
if($hostelno==5){$wardenEmail="iiitbh.ac.in";}
if($hostelno==6){$wardenEmail="iiitbh.ac.in";}
if($hostelno==7){$wardenEmail="iiitbh.ac.in";}
//if($hostelno==){$wardenEmail="iiitbh.ac.in";}


$s = " ";
$msga =" Request for Leave approval";
$br = "<br>";
$msga1 = "$fn$s$ln$s$EmpId$s is requesting for leave from $fromdate to $todate \n\n Reference Number $refno \n\n Description $description \n\n This is computer generated mail please dont reply.";
mail($wardenEmail,$msga,$msga1);

$msg1=" Leave applied successfully Reference Number $refno";
mail($temp1,$msg,$msg1);
}
 ?>




<!DOCTYPE html>
<html lang="en">
    <head>

        <!-- Title -->
        <title> Apply Leave</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="Steelcoders" />

        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css"/>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
        <link href="assets/css/alpha.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/custom.css" rel="stylesheet" type="text/css"/>
  <style>
        .errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #dd3d36;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
        </style>



    </head>
    <body>
  <?php include('includes/header.php');?>

       <?php include('includes/sidebar.php');?>
   <main class="mn-inner">
                <div class="row">
                    <div class="col s12">
                        <div class="page-title">Apply for Leave</div>
                    </div>
                    <div class="col s12 m12 l8">
                        <div class="card">
                            <div class="card-content">
                                <form id="example-form" method="post" name="addemp">
                                    <div>
                                        <h3>Apply for Leave</h3>
                                        <section>
                                            <div class="wizard-content">
                                                <div class="row">
                                                    <div class="col m12">
                                                        <div class="row">
     <?php if($error){?><div class="errorWrap"><strong>ERROR </strong>:<?php echo htmlentities($error); ?> </div><?php }
                else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>


 <div class="input-field col s12">
<select  name="leavetype" autocomplete="off" required>
<option value="">Select leave type...*</option>
<?php $sql = "SELECT  LeaveType from tblleavetype";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{   ?>
<option value="<?php echo htmlentities($result->LeaveType);?>"><?php echo htmlentities($result->LeaveType);?></option>
<?php }} ?>
</select>

</div>
<div class="input-field col s12">
<select  name="HostelNumber" autocomplete="off" required>
<option value="">Select the Hostel....*</option>
<option value="1">HostelNumber1</option>
<option value="2">HostelNumber2</option>
<option value="3">HostelNumber3</option>
<option value="4">HostelNumber4</option>
<option value="5">HostelNumber5</option>
<option value="6">Girlshostel1</option>
<option value="7">Girlshostel2</option>
</select>
<div class="input-field col m6 s12">
From Date
<!--label for="fromdate">From  Date</label-->
<input placeholder="" id="mask1" name="fromdate" class="masked" type="date" data-inputmask="'alias': 'date'"  required>
</div>
<div class="input-field col m6 s12">
To Date
<!--label for="todate">To Date</label-->
<input placeholder="" id="mask1" name="todate" class="masked" type="date" data-inputmask="'alias': 'date'" required>
</div>
<div class="input-field col m12 s12">
<label for="birthdate">Description</label>

<textarea id="textarea1" name="description" class="materialize-textarea" length="500" required></textarea>
</div>
</div>
      <button type="submit" name="apply" id="apply" class="waves-effect waves-light btn indigo m-b-xs">Apply</button>

      <p> * fields are required</p>

                                                </div>
                                            </div>
                                        </section>


                                        </section>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div class="left-sidebar-hover"></div>

        <!-- Javascripts -->
        <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
        <script src="assets/plugins/materialize/js/materialize.min.js"></script>
        <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
        <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="assets/js/alpha.min.js"></script>
        <script src="assets/js/pages/form_elements.js"></script>
          <script src="assets/js/pages/form-input-mask.js"></script>
                <script src="assets/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>
    </body>
</html>
<?php } ?>
