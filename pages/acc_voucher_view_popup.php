<?php
require_once 'support_file.php';
$vdate		= @$_REQUEST['vdate'];
$jvdate=date('Y-m-d' , strtotime($vdate));
$jv_no =  $_REQUEST['v_no'];
$v_type 		= find_a_field('journal','distinct tr_from','jv_no='.$_REQUEST['v_no']);
$v_type = strtolower($v_type);
$no 		= $v_type."_no";
$v_no = getSVALUE('journal','tr_no','where jv_no='.$_REQUEST['v_no']);
echo '<title>'.$v_no.'</title>';
$sub_ledger_id= @$_POST["sub_ledger_id"];
$cost_center= @$_POST["cost_center"];
$cheq_no = @$_POST["cheq_no"];
$cheq_date = strtotime(@$_POST["cheq_date"]);
$date=@$_POST["vdate"];
$now=time();
$page = 'acc_voucher_view_popup.php';
if($v_type=='receipt'){$voucher_name='RECEIPT VOUCHER';$vtype='receipt';$tr_from='receipt';$dtype='receiptdate';$olddtype='receipt_date';}
elseif($v_type=='payment'){$voucher_name='PAYMENT VOUCHER';$vtype='payment';$tr_from='payment';$dtype='paymentdate';$olddtype='payment_date';}
elseif($v_type=='Purchase'){$voucher_name='Purchase VOUCHER';$vtype='secondary_journal';$tr_from='Purchase';$dtype='jvdate';$olddtype='jv_date';}
elseif($v_type=='journal_info'){$voucher_name='JOURNAL VOUCHER';$vtype='journal_info';$tr_from='journal_info';$dtype='j_date';$olddtype='journal_info_date';}
elseif($v_type=='Contra'){$voucher_name='CONTRA VOUCHER';$vtype='coutra';$tr_from='Contra';$dtype='coutradate';$olddtype='coutra_date';}
else{$v_type=='Contra';$voucher_name='CONTRA VOUCHER';$vtype='coutra';$tr_from='Contra';$dtype='coutradate';$olddtype='coutra_date';}

$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$todaysss=$dateTime->format("d M Y,  h:i A");


if(isset($_REQUEST['delete']))
{
mysqli_query($conn, "INSERT INTO `journal_deleted` (jvdate,proj_id,jv_no,jvno,jv_date,ledger_id,PBI_ID,narration,dr_amt,cr_amt,tr_from,custom_no,tr_no,tr_no_custom,sub_ledger,cc_code,user_id,tr_id,group_for,entry_at,relavent_cash_head,cheq_no,cheq_date,sub_ledger_id,create_date,ip,time,day,month,year,delete_at,delete_by,purpose_of_edit_delete)

SELECT jvdate,proj_id,jv_no,jvno,jv_date,ledger_id,PBI_ID,narration,dr_amt,cr_amt,tr_from,custom_no,tr_no,tr_no_custom,sub_ledger,cc_code,user_id,tr_id,group_for,entry_at,relavent_cash_head,cheq_no,cheq_date,sub_ledger_id,create_date,ip,time,day,month,year,1,$userid FROM `journal` WHERE tr_no='$v_no' AND tr_from='$tr_from','".$_POST['purpose_of_edit_delete']."'");
mysqli_query($conn, "UPDATE journal_deleted SET delete_at='$todaysss' WHERE tr_no='$v_no' AND tr_from='$tr_from'");


    ///////////////////////// to admin

    //$to = 'neazjhs@icpbd.com';
    $subject = "A VOUCHER has been moved to trash!!";
    $txt1 = "<p>Dear Sir,</p>
				<p>A $tr_from VOUCHER has been move to trash!! The VOUCHER no. is <strong>$v_no</strong>. If necessary you will be able to restore the voucher with the help of MIS team.</p>							
				<p><strong>Purpose of Deleted :</strong> <strong style='color:red'>".$_POST['purpose_of_edit_delete']."</strong></p>
				<p><strong>Deleted By :</strong> ".$_SESSION['username']."</p>	
				<p><strong>Deleted From IP: </strong> ".$ip."</p>							
				<p><b><em>This EMAIL is automatically generated by ERP Software</em>.</b></p>";
    $txt=$txt1;
    $from = 'erp@icpbd.com';
    $headers = "";
    $headers .= "From: ICP ERP <erp@icpbd.com> \r\n";
    $headers .= "Reply-To:" . $from . "\r\n" ."X-Mailer: PHP/" . phpversion();
    $headers .= 'MIME-Version: 1.0' . "\r\n";
    //$headers .= 'Cc: ismail@icpbd.com,a.sobahan@icpbd.com' . "\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    mail($to,$subject,$txt,$headers);
    $sqlDel1 = mysqli_query($conn, "DELETE FROM coutra WHERE coutra_no=".$_GET['v_no']." and 1");
	$sqlDel1 = "DELETE FROM $vtype WHERE $no='$v_no' and 1";
    $sqlDel2 = "DELETE FROM journal WHERE tr_no='$v_no' AND tr_from='$tr_from'";
    if(mysqli_query($conn, $sqlDel1)){}
    if(mysqli_query($conn, $sqlDel2)){}
    if($_GET['in']=='Journal_info')	echo "<script>self.opener.location = 'journal_note.php'; self.blur(); </script>";
    elseif($_GET['in']=='Contra')	echo "<script>self.opener.location = 'contra_note.php'; self.blur(); </script>";
    elseif($_GET['in']=='Credit')	echo "<script>self.opener.location = 'credit_note.php'; self.blur(); </script>";
    elseif($_GET['in']=='Debit')	echo "<script>self.opener.location = 'debit_note.php'; self.blur(); </script>";
    //else	echo "<script>self.opener.location = 'acc_voucher_view.php'; self.blur(); </script>";
    echo "<script>window.close(); </script>";
}

if($v_type=='coutra') $v_type='Contra'; else $v_type=$v_type;
if(isset($_POST['check']))
{
    $sqldate1 = mysqli_query($conn, "UPDATE journal SET status='CHECKED' WHERE jv_no=".$_GET['v_no']);
    $tr_no=find_a_field('journal','tr_no','jv_no='.$_GET['v_no']);
    $sqldate2 = mysqli_query($conn, "UPDATE journal_voucher_master SET entry_status='CHECKED' and checked_by='".$_SESSION['userid']."',checked_at='".$now."' WHERE voucherno='$tr_no'");
}

if(isset($_POST['update']))
{

    $vdate = strtotime($_POST["vdate"]);
    $vdateji = date('Y-m-d' , strtotime($_REQUEST['vdate']));
    $sqldate1 = "UPDATE $vtype SET {$v_type}_date='$vdate' and $dtype='$vdateji' WHERE $no='$v_no' and 1";
    $sqldate2 = "UPDATE journal SET jv_date='$vdate' and jvdate='$vdateji' WHERE jv_no='$jv_no' AND tr_from='$tr_from'";
    @mysqli_query($conn, $sqldate1);
    @mysqli_query($conn, $sqldate2);
    $count = $_POST["count"];
    $sql2="select a.id, a.tr_id,a.jv_no,a.jv_date,a.tr_from,a.tr_no,a.jvdate from journal a where a.tr_from = '$tr_from' and a.jv_no='$jv_no' and 1";
    $data2=mysqli_query($conn, $sql2);
    while($datas=mysqli_fetch_row($data2)){
        $jv_no =  $datas[2];
        $jv_date =   $datas[3];
        $tr_id =  $datas[1];
        $tr_from =  $datas[4];
        $tr_no =  $datas[5];
        $ledger = $_POST['ledger_'.$datas[0]];
        $c_no= @$_POST['c_no'];
        $c_date= @$_POST['c_date'];
        $vdateji = date('Y-m-d' , strtotime($_REQUEST['vdate']));
        $narration= @$_POST['narration_'.$datas[0]];
        $CC= $_POST['cc_'.$datas[0]];
        $dr_amt=$_POST['dr_amt_'.$datas[0]];
        $cr_amt=$_POST['cr_amt_'.$datas[0]];
        if($dr_amt==0&&$cr_amt==0){
            $sqldate1 = "delete from $vtype WHERE id = ".$datas[1];
            $sqldate2 = "delete from journal WHERE id = ".$datas[0];
            if(isset($sqldate1))@mysqli_query($conn, $sqldate1);@mysqli_query($conn, $sqldate2);
        } else {

            ////// for data update
            $sqldate1 = "UPDATE $vtype SET cheq_no='$cheq_no',cheq_date='$cheq_date',ledger_id='$ledger',cc_code='$CC',sub_ledger_id='$sub_ledger_id',narration='$narration',dr_amt='$dr_amt',cr_amt='$cr_amt',$dtype='$vdateji',$olddtype='$date' WHERE id = ".$datas[1];
            $sqldate2 = "UPDATE journal SET cheq_no='$cheq_no',cheq_date='$cheq_date',ledger_id='$ledger',cc_code='$CC',sub_ledger_id='$sub_ledger_id',narration='$narration',dr_amt='$dr_amt',cr_amt='$cr_amt',jvdate='$vdateji',jv_date='$date' WHERE id = ".$datas[0];
            if(isset($sqldate1))@mysqli_query($conn, $sqldate1);@mysqli_query($conn, $sqldate2);
        }}

    if(($_POST['dr_amt_new1']>0||$_POST['cr_amt_new1'])&&($_POST['ledger_new1']!=''))
    {
        $ledger = $_POST['ledger_new1'];
        $sql = "INSERT INTO $vtype 
({$v_type}_no, {$v_type}_date, `narration`, `ledger_id`, `dr_amt`, `cr_amt`,`cc_code`,ip,section_id,company_id,entry_by,create_date,`time`,`day_name`,
									`day`,
									`month`,
									`year`) 
VALUES 
('$tr_no', '$jv_date',  '".$_POST['narration_new1']."', '$ledger', '".$_POST['dr_amt_new1']."', '".$_POST['cr_amt_new1']."','".$_POST['cc_new1']."','$ip',
'".$_SESSION['sectionid']."','".$_SESSION['companyid']."','".$_SESSION['userid']."','$tdates','$now','$day','$thisday','$thismonth','$thisyear')";
        if(mysqli_query($conn, $sql)){
            $tr_id = mysqli_insert_id();
            add_to_journal($jvdate,$proj_id, $jv_no, $jv_date, $ledger, $_POST['narration_new1'], $_POST['dr_amt_new1'], $_POST['cr_amt_new1'],$tr_from, $tr_no, '', $tr_id, '',$_POST['cc_new1'],$create_date,$ip,$now,$day,$thisday,$thismonth,$thisyear);}
    }



    if(($_POST['dr_amt_new2']>0||$_POST['cr_amt_new2'])&&($_POST['ledger_new2']!=''))
    {   $ledger = $_POST['ledger_new2'];
        $sql = "INSERT INTO $vtype 
({$v_type}_no, {$v_type}_date, `narration`, `ledger_id`, `dr_amt`, `cr_amt`,`cc_code`,ip,section_id,company_id,entry_by,create_date,`time`,`day_name`,
									`day`,
									`month`,
									`year`) 
VALUES 
('$tr_no', '$jv_date','".$_POST['narration_new2']."', '$ledger', '".$_POST['dr_amt_new2']."', '".$_POST['cr_amt_new2']."','".$_POST['cc_new2']."','$ip',
'$_SESSION[sectionid]','$_SESSION[companyid]','$_SESSION[userid]','$tdates','$now','$day','$thisday','$thismonth','$thisyear'
)";
        if(mysqli_query($conn, $sql)){
            $tr_id = mysqli_insert_id();
            add_to_journal($jvdate,$proj_id, $jv_no, $jv_date, $ledger, $_POST['narration_new2'], $_POST['dr_amt_new2'], $_POST['cr_amt_new2'],$tr_from, $tr_no, '', $tr_id, '',$_POST['cc_new2'],$create_date,$ip,$now,$day,$thisday,$thismonth,$thisyear);}
    }
$update_journal_master=mysqli_query($conn, "Update journal_voucher_master set purpose_of_edit_delete='".$_POST[purpose_of_edit_delete]."',edited_by='".$_SESSION[userid]."',edited_at='".$todaysss."' where voucherno='".$v_no."'");
} // end of modify






if(isset($_REQUEST['v_no']))
{
$sql1="select narration,cheq_no,cheq_date,' ',jv_date,cc_code,sub_ledger_id,jvdate from journal where jv_no='$jv_no' and tr_from ='$tr_from' limit 1";
$data1=mysqli_fetch_row(mysqli_query($conn, $sql1));
$sql1."<br>";
?>
    <?php require_once 'header_content.php'; ?>
    <?php require_once 'body_content_without_menu.php'; ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
    <div class="x_content">
    <form  name="form2" id="form2" class="form-horizontal form-label-left" method="post" onsubmit="return validate_total()" style="font-size: 11px">
    <? require_once 'support_html.php';?>
    <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
    <tr>
                        <td align="right"><strong>Date:</strong></td>
                        <td align="left"><input style="width: 150px" name="vdate" type="date" value="<?=$data1[7];?>" max="<?=date('Y-m-d');?>"> </td>
                        <td height="20" align="right"><strong>Cq No:</strong> </td>
                        <td height="20" align="left"><input name="cheq_no" id="cheq_no" type="text" value="<?php echo $data1[1];?>" style="width:100px" /></td>
                        <td width="20%" align="right" valign="middle"><strong>Cq Date: </strong></td>
                        <td width="" align="left" valign="middle" width="25%"><input name="cheq_date" id="cheq_date" type="date" value="<?=$data1[2];?>" style="" /></td>
                    </tr>
                </table>


    <table align="center" class="table table-striped table-bordered" style="width:98%; font-size: 11px">
    <tr style="background-color: bisque">
                    <th style="width: 2%">S/L</th>
                    <th>A/C Ledger</th>
                    <th style="width: 25%">Narration</th>
                    <th style="width: 20%">Cost Center</th>
                    <th style="width: 12%">Debit</th>
                    <th style="width: 12%">Credit</th>
                </tr>

                <?php
                $pi=0;
                $d_total=0;
                $c_total=0;
                $sql2="select a.dr_amt,a.cr_amt,b.ledger_name,b.ledger_id,a.narration,a.id,a.cc_code from accounts_ledger b, journal a where a.ledger_id=b.ledger_id and a.tr_from = '$tr_from' and a.jv_no='$jv_no' and a.ledger_id>0";
                $data2=mysqli_query($conn, $sql2);
                while($info=mysqli_fetch_row($data2)){ $pi++;
                    $entry[$pi] = $info[5];
                    if($info[0]==0) $type='Credit';
                    else $type='Debit';
                    $d_total=$d_total+$info[0];
                    $c_total=$c_total+$info[1];
                    ?>

                    <tr>
                        <td style="text-align: center; vertical-align: middle"><?=$pi;?></td>
                        <td style="text-align: left; vertical-align: middle">
                            <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1" required="required"  name="ledger_<?=$info[5]?>" id="ledger_<?=$info[5]?>">
                                <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)',$info[3], 'status=1'); ?>
                            </select>
                        <td style="text-align: left; vertical-align: middle">
                        <textarea  name="narration_<?=$info[5];?>" id="narration_<?=$info[5];?>" class="form-control col-md-7 col-xs-12" style="width:100%; height:37px; font-size: 11px; text-align:center"><?=$info[4];?></textarea>
                            <input type="hidden" name="l_<?=$pi;?>" id="l_<?=$pi;?>" value="<?=$info[3];?>" />		  </td>
                        <td style="text-align: left; vertical-align: middle"><select class="select2_single form-control" style="width:99%" tabindex="-1" name="cc_<?=$info[5];?>" id="cc_<?=$info[5];?>">
                            <?php foreign_relation('cost_center', 'id', 'CONCAT(id,":", center_name)', $info[6], 'status=1'); ?>
                            </select></td>
                        <td style="text-align: center; vertical-align: middle"><input name="dr_amt_<?=$info[5];?>" type="text" id="dr_amt_<?=$info[5];?>" value="<?=$info[0]?>" class="form-control col-md-7 col-xs-12" style="width:98%; height:37px; font-size: 11px; text-align:right" onchange="add_sum()" /></td>
                        <td style="text-align: center; vertical-align: middle"><input name="cr_amt_<?=$info[5];?>" type="text" id="cr_amt_<?=$info[5];?>" value="<?=$info[1]?>" class="form-control col-md-7 col-xs-12" style="width:98%; height:37px; font-size: 11px; text-align:right" onchange="add_sum()" /></td>
                    </tr><?php } ?>

                <tr>
                    <td style="text-align: center; vertical-align: middle"><?=++$pi;?></td>
                    <td style="text-align: left; vertical-align: middle">
                        <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1"   name="ledger_new1" id="ledger_new1">
                            <option></option>
                            <?php foreign_relation('accounts_ledger', 'ledger_id', 'CONCAT(ledger_id," : ", ledger_name)',1, 'status=1'); ?>
                        </select>
                    </td>
                    <td style="text-align: center; vertical-align: middle"><textarea name="narration_new1" id="narration_new1" class="form-control col-md-7 col-xs-12" style="width:100%; height:37px; font-size: 11px; text-align:center" value=""></textarea></td>
                    <td style="text-align: left; vertical-align: middle"><select class="select2_single form-control" style="width:99%" tabindex="-1"  name="cc_new1" id="cc_new1">
                            <?php foreign_relation('cost_center', 'id', 'CONCAT(id,":", center_name)', $info[6], 'status=1'); ?>
                        </select></td>
                    <td style="text-align: right; vertical-align: middle"><input name="dr_amt_new1" type="text" id="dr_amt_new1" class="form-control col-md-7 col-xs-12" style="width:98%; height:37px; font-size: 11px; text-align:right" onchange="add_sum()" /></td>
                    <td style="text-align: right; vertical-align: middle"><input name="cr_amt_new1" type="text" id="cr_amt_new1" class="form-control col-md-7 col-xs-12" style="width:98%; height:37px; font-size: 11px; text-align:right" onchange="add_sum()" /></td>
                </tr>

                <tr>
                    <td style="text-align: center; vertical-align: middle"><?=++$pi;?></td>
                    <td style="text-align: left; vertical-align: middle">
                        <select class="select2_single form-control" style="width:100%; font-size: 11px" tabindex="-1"   name="ledger_new2" id="ledger_new2">
                            <option></option>
                            <?=foreign_relation('accounts_ledger','ledger_id', 'CONCAT(ledger_id," : ", ledger_name)',1, 'status=1'); ?>
                        </select></td>
                    <td style="text-align: center; vertical-align: middle"><textarea name="narration_new2" id="narration_new2" class="form-control col-md-7 col-xs-12" style="width:100%; height:37px; font-size: 11px; text-align:center" value="" ></textarea></td>
                    <td style="text-align: left; vertical-align: middle"><select class="select2_single form-control" style="width:99%" tabindex="-1"  name="cc_new2" id="cc_new2">
                            <?php foreign_relation('cost_center', 'id', 'CONCAT(id,":", center_name)', $info[6], 'status=1'); ?>
                        </select></td>
                    <td style="text-align: right; vertical-align: middle"><input name="dr_amt_new2" type="text" id="dr_amt_new2" class="form-control col-md-7 col-xs-12" style="width:98%; height:37px; font-size: 11px; text-align:right" onchange="add_sum()" /></td>
                    <td style="text-align: right; vertical-align: middle"><input name="cr_amt_new2" type="text" id="cr_amt_new2"  class="form-control col-md-7 col-xs-12" style="width:98%; height:37px; font-size: 11px; text-align:right" onchange="add_sum()" /></td>
                </tr>
                <tr align="center">
                    <td colspan="4" style="vertical-align:middle; font-weight: bold" align="right">Total Amount :</td>
                    <td style="vertical-align:middle; font-weight: bold"><input name="dr_amt" type="text" id="dr_amt" value="<?=$d_total?>" class="form-control col-md-7 col-xs-12" style="width:98%; height:37px; font-size: 11px; text-align:right" readonly="readonly"/></td>
                    <td style="vertical-align:middle; font-weight: bold"><input name="cr_amt" type="text" id="cr_amt" value="<?=$c_total?>" class="form-control col-md-7 col-xs-12" style="width:98%; height:37px; font-size: 11px; text-align:right" readonly="readonly" /></td>
                </tr>

    <?php
    if($vtype=='receipt'||$vtype=='Receipt') $page="credit_note.php?v_no=$v_no&v_type=$vtype&v_d=$vdate&action=edit";
    if($vtype=='payment'||$vtype=='Payment') $page="debit_note.php?v_no=$v_no&v_type=$vtype&v_d=$vdate&action=edit";
    if($vtype=='coutra'||$vtype=='Coutra') $page="coutra_note_new.php?v_no=$v_no&v_type=$vtype&v_d=$vdate&action=edit";
    if($vtype=='journal_info'||$vtype=='Journal_info') $page="journal_note_new.php?v_no=$v_no&v_type=$vtype&v_d=$vdate&action=edit";
    ?>



        <?php
        $GET_status=find_a_field('journal','distinct status','jv_no='.$_GET['v_no']);
        if($GET_status=='MANUAL' || $GET_status=='UNCHECKED'){
            $access_days=30;
            $datetime1 = date_create($data1[7]);
            $datetime2 = date_create(date('Y-m-d'));
            $interval = date_diff($datetime1, $datetime2);
            $v_d=$interval->format('%a');
            if($_SESSION['userlevel']=='2'){
                if($v_d>$access_days){ echo '<h6 style="text-align: center; color:red">Access Restricted.</h6>';} else {?>
                    <tr><td colspan="6"><textarea style="float: left; margin-left:1%; font-size: 11px; width: 250px" name="note" id="note" placeholder="Type the reason for the update or deletion" ></textarea></td></tr>
                    <tr><td colspan="2"><button style="float: left; margin-left:1%; font-size: 11px" type="submit" name="delete" id="delete" class="btn btn-danger" onclick='return window.confirm("Are you confirm to Completed?");'>Delete Voucher</button></td>
                    <td colspan="2"><button style="float: left; margin-left:30%; font-size: 11px" type="submit" name="update" id="update" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Completed?");'>Update Voucher</button></td>
                    <td colspan="2"><button style="float: right; margin-right:1%; font-size: 11px" type="submit" name="check" id="check" class="btn btn-success" onclick='return window.confirm("Are you confirm to Completed?");'>Check & Proceed to next</button></td>
                <? }}} else {echo '<h6 style="text-align: center;color: red;  font-weight: bold"><i>This voucher has been '.$GET_status.' !!</i></h6>'; } ?>
    <?php } if($_SESSION['userlevel']=='1'){ ?>
    <tr><td colspan="6"><textarea style="float: left; margin-left:1%; font-size: 11px; width: 250px" name="note" id="note" placeholder="Type the reason for the update or deletion" ></textarea></td></tr>
<tr><td colspan="2"><button style="float: left; margin-left:1%; font-size: 11px" type="submit" name="delete" id="delete" class="btn btn-danger" onclick='return window.confirm("Are you confirm to Completed?");'>Delete Voucher</button></td>
    <td colspan="2"><button style="float: left; margin-left:30%; font-size: 11px" type="submit" name="update" id="update" class="btn btn-primary" onclick='return window.confirm("Are you confirm to Completed?");'>Update Voucher</button></td>
    <td colspan="2"><button style="float: right; margin-right:1%; font-size: 11px" type="submit" name="check" id="check" class="btn btn-success" onclick='return window.confirm("Are you confirm to Completed?");'>Check & Proceed to next</button></td>
    <?php } ?>

    <script type="application/javascript">
        function add_sum()
        {
            var dr_amt_new1 = ((document.getElementById('dr_amt_new1').value)*1)+0;
            var dr_amt_new2 = ((document.getElementById('dr_amt_new2').value)*1)+0;
            var cr_amt_new1 = ((document.getElementById('cr_amt_new1').value)*1)+0;
            var cr_amt_new2 = ((document.getElementById('cr_amt_new2').value)*1)+0;
            var dr_total = dr_amt_new1;
            var cr_total = cr_amt_new1;
            if(cr_amt_new2>0){
                cr_total = cr_total + cr_amt_new2;}
            if(dr_amt_new2>0){
                dr_total = dr_total + dr_amt_new2;}
            <?
            for($i=1;$i<=$pi;$i++){
                if(@$entry[$i]>0){
                    echo "cr_total = cr_total+((document.getElementById('cr_amt_".$entry[$i]."').value)*1);";
                    echo "dr_total = dr_total+((document.getElementById('dr_amt_".$entry[$i]."').value)*1);";
                }} ?>

            document.getElementById('cr_amt').value = cr_total.toFixed(2);
            document.getElementById('dr_amt').value = dr_total.toFixed(2);
        }
        function validate_total() {
            var dr_amt = ((document.getElementById('dr_amt').value)*1);
            var cr_amt = ((document.getElementById('cr_amt').value)*1);
            if(dr_amt==cr_amt)
                return true;
            else
            {
                alert('Debit and Credit have to be equal.Please Re-Check This voucher.');
                return false;
            }
        }
        function loadinparent(url)
        {
            self.opener.location = url;
            self.blur();
        }
    </script>

    <input name="count" id="count" type="hidden" value="<?=$pi;?>" />
    </table>
    </form>
    </div>
    </div>
    </div>
<?=$html->footer_content();
mysqli_close($conn);?>