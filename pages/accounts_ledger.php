<?php require_once 'php_header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $_SESSION[company]; ?> | Ledger</title>



    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
    <SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.reportlevelname.options[form.reportlevelname.options.selectedIndex].value;
	self.location='accounts_ledger.php?rlid=' + val ;
}


function reload2(form)
{
        var val=form.reportlevelname.options[form.reportlevelname.options.selectedIndex].value;
	var val2=form.maingroup.options[form.maingroup.options.selectedIndex].value;
	self.location='accounts_ledger.php?rlid=' + val +'&mgid='+ val2  ;
}

function reload3(form)
{
    var val=form.reportlevelname.options[form.reportlevelname.options.selectedIndex].value;
	var val2=form.maingroup.options[form.maingroup.options.selectedIndex].value;
	var val3=form.subgroup.options[form.subgroup.options.selectedIndex].value;
	self.location='accounts_ledger.php?rlid=' + val +'&mgid='+ val2 +'&sgid='+ val3 ;
}

</script>
    </head>



  <body class="nav-md">
  <div class="container body">
  <div class="main_container">
  <div class="col-md-3 left_col">
  <div class="left_col scroll-view">
  <div class="navbar nav_title" style="border: 0;">
  <a href="dashboard.php" class="site_title"><i class="fa fa-paw"></i> <span>Raresoft</span></a>
  </div>
  
  <div class="clearfix"></div>
  
  <!-- menu profile quick info -->
  <?php include ("pro.php");  ?> <br />
  <!-- /menu profile quick info -->


<!-- sidebar menu -->
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
<?php include("sidebar_menu.php"); ?>
</div>
<!-- /sidebar menu -->



<!-- /menu footer buttons -->
<?php include("menu_footer.php"); ?>
<!-- /menu footer buttons -->

</div>
</div>


<!-- top navigation -->
<div class="top_nav">
<?php include("top.php"); ?>
</div>

<!-- /top navigation -->



<!-- page content -->
<div class="right_col" role="main">
<div class="">
<div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
              <div class="x_title">
              <h2>Create Ledger</h2>
              <div class="clearfix"></div>
              </div>

                  

              <div class="x_content">
               <div class="col-md-9 col-sm-9 col-xs-12">
               <div class="" role="tabpanel" data-example-id="togglable-tabs">
               <div id="myTabContent" class="tab-content">
               <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">



 

 <?php 

 ////////// data Insert code start from here

    
	$getstarted3=$_POST[getstarted3];
	$rresult=mysql_query("Select * from accounts_reportlevel where rlid='$_GET[rlid]' and companyid='$_SESSION[companyid]'");
	$rrow=mysql_fetch_array($rresult);
	$reportlevelname= $rrow['reportlevelname'];
	
	$mresult=mysql_query("Select * from accounts_maingroup where mgid='$_GET[mgid]' and companyid='$_SESSION[companyid]'");
	$mrow=mysql_fetch_array($mresult);
	$maingroup=$mrow['maingroup'];
	
	$sresult=mysql_query("Select * from accounts_subsidiary where subsidiaryid='$_GET[sgid]' and companyid='$_SESSION[companyid]'");
	$srow=mysql_fetch_array($sresult);
	$subgroup=$srow['subsidiary'];
	
	
	$reporttype=getSVALUE("accounts_reportlevel", "accountreporttype", "where reportlevelname='$reportlevelname' and companyid='$_SESSION[companyid]'");

	
    $inputby=$_SESSION['login_email'];
    $companyid=$_SESSION['companyid'];
    $create_date=date('Y-m-d');
    $ledgerid=$_SESSION['ledger_id'];
	$ledgerids=$_POST[ledgerids];
	$ledger=$_POST[ledger];
	
    $rlid=$_GET[rlid];
    $maingroupid=$_GET[mgid];
	



	
if (isset($_POST['getstarted'])){


$valid = true;
$flag=mysql_query("Select ledger from accounts_ledger where ledger='$ledger' and companyid='$_SESSION[companyid]'");
	if ( mysql_num_rows($flag)>0)
{echo "<script> alert('This Ledger is Already Input!! Please Try Another Name') </script>";
        $valid = false;}


$flag=mysql_query("Select ledgercode from accounts_ledger where ledgercode='$ledgercode' and companyid='$_SESSION[companyid]'");
	if ( mysql_num_rows($flag)>0)
{echo "<script> alert('This Ledger Code is Already Input!! Please Try Another Name') </script>";
        $valid = false;}
		
		

if ($valid){


$result=mysql_query("INSERT INTO `accounts_ledger` 
(accountreporttype,rlid,reportlevelname,mgid,maingroup,subsidiaryid,subsidiary,ledgercode,ledger,companyid,createby,createdate,ip,mac) VALUES('$reporttype','$rlid','$reportlevelname','$maingroupid','$maingroup','$_SESSION[sub_id]','$subgroup','$ledgerid','$ledger','$companyid','$inputby','$create_date','$ip','$mac')");

	ledgerid(); ?>
    <!---meta http-equiv="refresh" content="0;accounts_ledger.php"---><?php }}  ?>




<?php
////////////////////////////////////// data edit function start from here-----------------------------------------
$edit=$_POST[edit];
if(isset($edit)){
	
mysql_query("Update `accounts_ledger` SET 
ledgercode='$ledgerids', ledger='$ledger', modifiredby='$inputby', modifieddate='$create_date' where rlid='$_GET[rleditid]' and mgid='$_GET[mgroupeditid]' and subsidiaryid='$_GET[subsidiaryeditid]' and ledgercode='$_GET[legdereditid]' and companyid='$_SESSION[companyid]'");
?>
<meta http-equiv="refresh" content="0;accounts_ledger.php">
<?php } 





////////////////////////////////////// data Delete function start from here-----------------------------------------

if($_GET[legderdeleteid]){
	$deleteid=$_GET[legderdeleteid];
		$delete=mysql_query("Delete from accounts_ledger where rlid='$_GET[rleditdeleteid]' and mgid='$_GET[mgroupdeleteid]' and subsidiaryid='$_GET[subsidiarydeleteid]' and 	ledgercode='$deleteid' and companyid='$_SESSION[companyid]'"); ?>
        <meta http-equiv="refresh" content="0;accounts_ledger.php"> 
		<?php  } ?>
        
        
        
        
  <?php
if($_GET[legdereditid]){  
$result=mysql_query("Select * from accounts_ledger where rlid='$_GET[rleditid]' and mgid='$_GET[mgroupeditid]' and subsidiaryid='$_GET[subsidiaryeditid]' and ledgercode='$_GET[legdereditid]' and companyid='$_SESSION[companyid]'");
$rowledgers=mysql_fetch_array($result);
}
?>       
        
        
        
        
        


               <form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">
               
               
               
               
               
               <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Report Level<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						<?php if($_GET[type]){ ?>
                        <input type="text" id="first-name" required="required" value="<?php echo $rowledgers[reportlevelname] ?>" name="reportlevelname" class="form-control col-md-7 col-xs-12" readonly><?php } else { ?>
                        <select id="first-name" required="required" onchange="javascript:reload(this.form)"   name="reportlevelname" class="form-control col-md-7 col-xs-12">
                        <option value="">Choose ......</option>
                        
                        <?php $result=mysql_query("Select * from accounts_reportlevel where companyid='$_SESSION[companyid]'");
						while($rowmaingroup=mysql_fetch_array($result)){
						if(($_GET[rlid])==$rowmaingroup[rlid]){?> 
                                         
                 
                 
                 
                 <option selected value="<?php echo $rowmaingroup[rlid]; ?>"><?php echo $rowmaingroup[reportlevelname]; ?></option>
                        <?php } else { ?>
                  <option  value="<?php echo $rowmaingroup[rlid]; ?>"><?php echo $rowmaingroup[reportlevelname]; ?></option>
                    <?php }} ?></select><?php } ?></div></div>
               
               
               
               
               
               
               
               <?php
			   if($_GET[rlid]){
			    ?>
                <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Group<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						<?php if($_GET[type]){ ?>
                        <input type="text" id="first-name" required="required" value="<?php echo $rowledgers[reportlevelname] ?>" name="reportlevelname" class="form-control col-md-7 col-xs-12" readonly><?php } else { ?>
                        <select id="first-name" required="required" onchange="javascript:reload2(this.form)"   name="maingroup" class="form-control col-md-7 col-xs-12">
                        <option value="">Choose ......</option>
                        
                        <?php $result=mysql_query("Select * from accounts_maingroup where rlid='$_GET[rlid]' and companyid='$_SESSION[companyid]'");
						while($rowmaingroup=mysql_fetch_array($result)){
						if(($_GET[mgid])==$rowmaingroup[mgid]){?> 
                                         
                 <option selected value="<?php echo $rowmaingroup[mgid]; ?>"><?php echo $rowmaingroup[maingroup]; ?></option>
                        <?php } else { ?>
                  <option  value="<?php echo $rowmaingroup[mgid]; ?>"><?php echo $rowmaingroup[maingroup]; ?></option>
                    <?php }} ?></select><?php } ?></div></div>
                    
                    
                    <?php } ?>
                    
                    
                    
                    
                       <?php if($_GET[type]=='edit'){ ?>
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Group<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
					
                        <input type="text" id="first-name" required="required" value="<?php echo $rowledgers[maingroup] ?>" name="maingroup" class="form-control col-md-7 col-xs-12" readonly>
                        </div></div> <?php } ?>
               
               
               
 <!------------------------------------------------subgroup ---------------------------------------------->              
               
                <?php
			   if($_GET[mgid]){
			    ?>
                <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Sub Group<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						<?php if($_GET[type]){ ?>
                        <input type="text" id="first-name" required="required" value="<?php echo $rowledgers[reportlevelname] ?>" name="reportlevelname" class="form-control col-md-7 col-xs-12" readonly><?php } else { ?>
                        <select id="first-name" required="required" onchange="javascript:reload3(this.form)"   name="subgroup" class="form-control col-md-7 col-xs-12">
                        <option value="">Choose ......</option>
                        
                        <?php $result=mysql_query("Select * from accounts_subsidiary where rlid='$_GET[rlid]' and mgid='$_GET[mgid]' and companyid='$_SESSION[companyid]'");
						while($rowsubmaingroup=mysql_fetch_array($result)){
						if(($_GET[sgid])==$rowsubmaingroup[subsidiaryid]){?> 
                                         
                 <option selected value="<?php echo $rowsubmaingroup[subsidiaryid]; ?>"><?php echo $rowsubmaingroup[subsidiary]; ?></option>
                        <?php } else { ?>
                  <option  value="<?php echo $rowsubmaingroup[subsidiaryid]; ?>"><?php echo $rowsubmaingroup[subsidiary]; ?></option>
                    <?php }} ?></select><?php } ?></div></div>
                    
                    
                    <?php } ?>
                    
                    
                    
                    
                       <?php if($_GET[type]=='edit'){ ?>
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Sub Group<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
					
                        <input type="text" id="first-name" required="required" value="<?php echo $rowledgers[maingroup] ?>" name="subgroup" class="form-control col-md-7 col-xs-12" readonly>
                        </div></div> <?php } ?>
               

               
               
<!------------------------------------------------- ledger---------------------------------------------------------->			   
			   
			   <?php if($_GET[sgid]){ ?>
               
               <div class="form-group">
               <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" >Ledger Code<span class="required">*</span></label>
               
               <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="first-name" required="required" value="<?php echo $ledgerid ?>" name="ledgercode" class="form-control col-md-7 col-xs-12"></div></div>
               
               <div class="form-group">
               <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" >Ledger<span class="required">*</span></label>
               
               <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="first-name" required="required" value="<?php echo $rowledgers[ledger] ?>" name="ledger" class="form-control col-md-7 col-xs-12"></div></div>
			   
			   
			   
			   
			   
			   
			   <?php } ?>
                      



               <?php if($_GET[type]=='edit'){ ?>
               
               <div class="form-group">
               <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" >Ledger Code<span class="required">*</span></label>
               
               <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="first-name" required="required" value="<?php echo $rowledgers[ledgercode] ?>" name="ledgerids" class="form-control col-md-7 col-xs-12"></div></div>
               
               
               <div class="form-group">
               <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" >Ledger<span class="required">*</span></label>
               
               
               
               <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id="first-name" required="required" value="<?php echo $rowledgers[ledger] ?>" name="ledger" class="form-control col-md-7 col-xs-12"></div></div><?php } ?>














                      

                   <div class="ln_solid"></div>
                   <div class="form-group">
                   <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                     <?php if($_GET[type]){ ?>

                        <a type="cancel" href="accounts_ledger.php" class="btn btn-primary">Cancel</a>
						<button type="submit" name="edit" class="btn btn-success">Edit</button>

                        <?php } else { ?>

                        <a type="cancel" href="accounts_ledger.php" class="btn btn-primary">Cancel</a>
						<button type="submit" name="getstarted" class="btn btn-success">Save</button>
							<?php } ?>

                        </div></div></form><br>
                        </div></div></div></div></div></div></div>

              

              

              

              

              

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                <div class="x_title">
                <h2>List of Ledger</h2>
                <div class="clearfix"></div>
                </div>

                  <div class="x_content">
                  <table id="datatable-buttons" class="table table-striped table-bordered">
                   <thead>
                    <tr>
                     <th style="width: 5%">SL</th>
                     <th style="width: 5%">Code</th>
                     <th style="">Ledger</th>
                     <th style="width: 20%">Sub Group</th>
                     <th style="width: 15%">Group</th>
                     <th style="width:20%" align="center">Option</th>

                        </tr>

                      </thead>





                      <tbody>

                       <?php 

					   $today=date("Y-m-d");

				$result=mysql_query("Select * from accounts_ledger where companyid='$_SESSION[companyid]'  order by ledger");

				while($row=mysql_fetch_array($result)){ 

				$i=$i+1; ?>

                      <tr>

                        
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row[ledgercode]; ?></td>
                        <td><?php echo $row[ledger]; ?></td>
                        <td><?php echo $row[subsidiary]; ?></td>
                        <td><?php echo $row[maingroup]; ?></td>
                        <td align="center">


<a href="accounts_ledger.php?type=edit&rleditid=<?php echo $row[rlid] ?>&mgroupeditid=<?php echo $row[mgid] ?>&subsidiaryeditid=<?php echo $row[subsidiaryid] ?>&legdereditid=<?php echo $row[ledgercode] ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Edit </a>



<a href="accounts_ledger.php?type=delete&rleditdeleteid=<?php echo $row[rlid] ?>&mgroupdeleteid=<?php echo $row[mgid] ?>&subsidiarydeleteid=<?php echo $row[subsidiaryid] ?>&legderdeleteid=<?php echo $row[ledgercode] ?>"class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>                           
                            
 
                           
                           
</td>
</tr>
<?php } ?></tbody></table>

       </div></div></div></div></div></div>

        <!-- /page content -->



        <!-- footer content -->

        <footer>

          <div class="pull-right">

          </div>

          <div class="clearfix"></div>

        </footer>

        <!-- /footer content -->

      </div>

    </div>



    <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <!-- Datatables -->
    <script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="../vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
    <script src="../vendors/jszip/dist/jszip.min.js"></script>
    <script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../vendors/pdfmake/build/vfs_fonts.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
    <!-- Datatables -->
    <script>

      $(document).ready(function() {

        var handleDataTableButtons = function() {

          if ($("#datatable-buttons").length) {

            $("#datatable-buttons").DataTable({

              dom: "Bfrtip",

              buttons: [{
                  extend: "copy",
                  className: "btn-sm"
                   },
                  {

                  extend: "csv",
                  className: "btn-sm"
                    },
                    {

                  extend: "excel",
                  className: "btn-sm"
			},
			{

                  extend: "pdfHtml5",
                  className: "btn-sm"
                   },
                   {

                  extend: "print",
                  className: "btn-sm"

                },

              ],

              responsive: true

            });

          }

        };



        TableManageButtons = function() {

          "use strict";

          return {

            init: function() {

              handleDataTableButtons();

            }

          };

        }();



        $('#datatable').dataTable();



        $('#datatable-keytable').DataTable({

          keys: true

        });



        $('#datatable-responsive').DataTable();



        $('#datatable-scroller').DataTable({

          ajax: "js/datatables/json/scroller-demo.json",

          deferRender: true,

          scrollY: 380,

          scrollCollapse: true,

          scroller: true

        });



        $('#datatable-fixed-header').DataTable({

          fixedHeader: true

        });



        var $datatable = $('#datatable-checkbox');



        $datatable.dataTable({

          'order': [[ 1, 'asc' ]],

          'columnDefs': [

            { orderable: false, targets: [0] }

          ]

        });

        $datatable.on('draw.dt', function() {

          $('input').iCheck({

            checkboxClass: 'icheckbox_flat-green'

          });

        });



        TableManageButtons.init();

      });

    </script>

    <!-- /Datatables -->

  </body>

