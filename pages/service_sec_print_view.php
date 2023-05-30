<?php


require_once 'support_file.php';
require_once ('../page/common/class.numbertoword.php');

$jv_no=$_GET['jv_no'];
$bill_no=$_REQUEST['bill_no'];
$bill_date=$_REQUEST['bill_date'];
$tdates = date("Y-m-d");
$day = date('l', strtotime($idatess));
$dateTime = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$timess = $dateTime->format("d-m-y  h:i A");

$js=find_all_field('secondary_journal','','tr_from ="Service" and dr_amt>0 and jv_no='.$jv_no);
$prm=find_all_field('purchase_receive_master','','custom_grn_no='.$js->tr_id.'');


if($_POST['check']=='CHECK')

{

$time_now = date('Y-m-d H:s:i');
$voucher_date = strtotime($_POST['voucher_date']);
$cc_code = $_POST['cc_code'];

$jvold = find_a_field('secondary_journal','tr_id','tr_from = "Purchase" and jv_no='.$jv_no);
$prold = find_all_field('purchase_receive','pr_no','id='.$jvold);
	
	$narration = 'SRN#'.$js->tr_id.', Chalan / Invoice No # '.$prm->ch_no.', Remarks #'.$prm->Remarks.', Service Teken for # '.$prm->advertisers.')(Bill#'.$bill_no.'/Dt:'.$bill_date.')';

	$ssql='update secondary_journal set narration="'.$narration.'",jvdate="'.$voucher_date.'", cc_code="'.
	$cc_code.'", checked_at="'.$time_now.'", checked_by="'.$_SESSION['userid'].'", checked="YES" , 	final_jv_no="'.$jv.'" where tr_from = "Service" and jv_no="'.$jv_no.'"';
	mysqli_query($conn, $ssql);

	$jv=next_journal_voucher_id();
	if(prevent_multi_submit()) {
	sec_journal_journal($jv_no,$jv,'Service',$c_no,$c_date,$create_date,$ip,$now,$day,$thisday,$thismonth,$thisyear,$tdates);
	$update=mysqli_query($conn, "Update purchase_receive_master set status='COMPLETED' where custom_grn_no=".$js->tr_id."");
    unset($_POST);
	}
}




if($_POST['check']=='RE-CHECK'){
$time_now = date('Y-m-d H:s:i');
$voucher_date = strtotime($_POST['voucher_date']);
$cc_code = $_POST['cc_code'];
$jvold = find_a_field('secondary_journal','tr_id','tr_from = "Purchase" and jv_no='.$jv_no);
$prold = find_all_field('purchase_receive','pr_no','id='.$jvold);
	$ssql='update purchase_receive set bill_no="'.$bill_no.'", bill_date="'.$bill_date.'" where pr_no="'.$prold->pr_no.'"';
	mysqli_query($conn, $ssql);
	$narration = 'GR#'.$prold->id.'/'.$prold->pr_no.'(PO#'.$prold->po_no.')(Bill#'.$bill_no.'/Dt:'.$bill_date.')';
	$ssql='update secondary_journal set narration="'.$narration.'",jv_date="'.$voucher_date.'", cc_code="'.
	$cc_code.'", checked_at="'.$time_now.'", checked_by="'.$_SESSION['userid'].'", checked="YES" , 	final_jv_no="'.$jv.'" where tr_from = "Purchase" and jv_no="'.$jv_no.'"';
	mysqli_query($conn, $ssql);
	$sssql = 'delete from journal where group_for="'.$_SESSION['usergroup'].'" and tr_from ="Purchase" and tr_no="'.$prold->pr_no.'"';
	mysqli_query($conn, $sssql);
	$jv=next_journal_voucher_id();
    sec_journal_journal($jv_no,$jv,'Purchase',$c_no,$c_date,$create_date,$ip,$now,$day,$thisday,$thismonth,$thisyear,$tdates);

}


$address=find_a_field('project_info','proj_address',"1");
$jv = find_all_field('secondary_journal','jvdate','tr_from = "Service" and jv_no='.$jv_no);
//echo $jv->tr_id;
$pr = find_all_field('purchase_receive_master','','pr_no='.$jv->tr_no);
?>





<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Voucher :.</title>
<link href="../page/css/voucher_print.css" type="text/css" rel="stylesheet"/>
<link href="../page/css/pagination.css" rel="stylesheet" type="text/css" />
<link href="../page/css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<link href="../page/css/jquery.autocomplete.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../page/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../page/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="../page/js/jquery.autocomplete.js"></script>
<script type="text/javascript" src="../page/js/jquery.validate.js"></script>
<script type="text/javascript" src="../page/js/paging.js"></script>
<script type="text/javascript" src="../page/js/ddaccordion.js"></script>
<script type="text/javascript" src="../page/js/js.js"></script>
<script type="text/javascript" src="../page/js/jquery.ui.datepicker.js"></script>
<script type="text/javascript">
function hide()
{
    document.getElementById("pr").style.display="none";
}
function DoNav(theUrl)
{	var URL = 'unchecked_voucher_view_popup_purchase.php?'+theUrl;
	popUp(URL);}
function popUp(URL)
{
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=800,height=800,left = 383,top = -16');");
}</script>
  
<style type="text/css">
/* CSS Document */
body 
{
	background-color: #ffffff;	
	margin: 0px auto -1px auto; 
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:11px;
	line-height:20px;
	margin-top:20px;
	color:#000000;
	text-align:none;
	text-decoration:none;
}
.header
{
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:11px;
	color:#000000;
	text-decoration:none;
	line-height:25px;
}

.tabledesign {
	width:820px;
	border-color:000000;
	border-collapse:collapse;
	font-size:14px;
	text-align:center;
}
.tabledesign td{
	padding:3px;
	border:solid 1px;
	border-color:000000;
	
}
.tabledesign1 {
	width:860px;
	border-color:000000;
	border-collapse:collapse;
	font-size:14px;
	text-align:center;
	line-height:12px;
}
.tabledesign1 td{
	border:solid 0px;
	border-color:000000;
	
}

.title{
	font-weight:bold;
	font-size:25px;
	line-height:30px;
}
.logo{
	width:300px;
	height:50px;
}
.debit_box{
	width:380px;
	height:32px;
	font-weight:bold;
	font-size:25px;
	margin: 0px auto -1px auto; 
	}
.line{
	background:url(../images/line.jpg) repeat-x;
	height:2px;
	line-height:0px;}
	
.tabledesign_text{
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
	line-height:20px;
	margin-top:20px;
	color:#000000;
	text-align:none;
	text-decoration:none;
	}

.style1 {
	color: #FFFFFF;
	font-weight: bold;
}
.style3 {color: #FFFFFF; font-weight: bold; font-size: 12px; }
-->
</style>
</head>
<body><form action="" method="post">
<table width="820" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td><div class="header">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="1%">
			<? $path='../logo/'.$_SESSION['proj_id'].'.jpg';
			if(is_file($path)) echo '<img src="'.$path.'" height="80" />';?></td>
            <td width="83%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center" class="title">
				<?=$_SESSION['company_name']?>                </td>
              </tr>
              <tr>
                <td align="center"><?=$address?></td>
              </tr>

              <tr>

                <td align="center"><table  class="debit_box" border="0" cellspacing="0" cellpadding="0">

                    <tr>

                      <td>&nbsp;</td>

                      <td width="325"><div align="center">JOURNAL VOUCHER</div></td>

                      <td>&nbsp;</td>

                    </tr>

                  </table></td>

              </tr>

            </table></td>

          </tr>



        </table></td>

	    </tr>

	  <tr>

	    <td>&nbsp;</td>

	  </tr>

    </table>

    </div></td>

  </tr>

  <tr>



	<td>	</td>

  </tr>



  <tr>


    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="3" class="tabledesign_text">
<div id="pr">
<? if($jv->checked!='YES'){?>
<div align="left">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td width="1"><input name="button" type="button" onclick="hide();window.print();" value="Print" /></td>
    <td style="width:auto" align="right"><a target="_blank" href="acc_service_chalan_view.php?custom_grn_no=<?=$prm->custom_grn_no?>">GR-<?=$prm->custom_grn_no?></a></td>
    <td width="75" align="right"><table width="1" border="0" cellpadding="0" cellspacing="0" bordercolor="#F0F0F0" bgcolor="#FF0000">
      <tr>
        <td><!--span class="style3"><a target="_blank" href="chalan_view_edit.php?v_no=<?=$pr->pr_no?>">EDIT</a></span--></td>
      </tr>
    </table></td>
    <td width="0" align="right">Date :</td>
    <td width="0">
<input name="jv_no" type="hidden" value="<?=$jv_no?>" />
<input name="voucher_date" type="date" required id="voucher_date" value="<?=$js->jvdate;?>" />
<input type="button" name="Submit" value="EDIT VOUCHER"  onclick="DoNav('<?php echo '&v_no='.$jv_no.'&view=Show' ?>');" /></td>
    <td><input name="check" type="submit" id="check" value="CHECK" />
        <input type="hidden" name="req_no" id="req_no" value="<?=$jv->jv_on?>" /></td></tr></table>



<a target="_blank" href="acc_service_chalan_view.php?custom_grn_no=<?=$prm->custom_grn_no?>"></a></div><? }else{?>
<div align="left">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#00CC00">

  <tr>

    <td width="1"><input name="button" type="button" onclick="hide();window.print();" value="Print" /></td>
    <td width="auto" align="right"><a target="_blank" href="acc_service_chalan_view.php?custom_grn_no=<?=$prm->custom_grn_no?>">GR-<?=$prm->custom_grn_no?></a></td>
   <td width="75" align="right"><table width="1" border="0" cellpadding="0" cellspacing="0" bordercolor="#F0F0F0" bgcolor="#FF0000">

      <tr>

        <td><!--span class="style3"><a target="_blank" href="chalan_view_edit.php?v_no=<?=$pr->pr_no?>">EDIT</a></span--></td>

      </tr>

    </table></td>

    <td width="0" align="right">Voucher Date :</td>

    <td width="0">



<input name="jv_no" type="hidden" value="<?=$jv_no?>" />
<input name="voucher_date" type="date"  id="voucher_date" value="<?=$js->jvdate;?>" />
<!--input type="button" name="Submit" value="EDIT VOUCHER"  onclick="DoNav('<?php echo '&v_no='.$jv_no.'&view=Show' ?>');" /--></td>
    <td><!--input name="check" type="submit" id="check" value="RE-CHECK" /-->
        <input type="hidden" name="req_no" id="req_no" value="<?=$jv->jv_on?>" /></td>
          </tr>
</table>
<a target="_blank" href="<?=$link_view;?>?v_no=<?=$pr->pr_no?>"></a></div><? }?>
</div></td>

        </tr>

      <tr>
        <td class="tabledesign_text">Voucher Date : <?=$js->jvdate;?></td>
        <td class="tabledesign_text">Bill No :
          <label>
          <input name="bill_no" type="text" id="bill_no" required value="<?=$pr->bill_no?>" />
          </label></td>
        <td class="tabledesign_text">GR Date : <?=$prm->rcv_Date;?></td>
      </tr>
<tr><td style="height:5px" colspan="3"></td></tr>
      <tr>

        <td class="tabledesign_text">Voucher No  :

          <?=$jv_no?></td>

        <td class="tabledesign_text">Bill Date  :

          <input name="bill_date" type="date" id="bill_date" required="required" value="<?=$pr->bill_date?>" /></td>

        <td class="tabledesign_text">Rec No  : <?=$prm->ch_no?></td>

      </tr>
<tr><td style="height:5px" colspan="3"></td></tr>
      <tr>

        <td class="tabledesign_text">&nbsp;</td>

        <td class="tabledesign_text">&nbsp;</td>

        <td class="tabledesign_text">Checked By :

          <? if($jv->checked=='YES') echo find_a_field('users','fname','user_id='.$jv->checked_by); else echo 'Not Checked';?></td>

      </tr>

    </table></td>

  </tr>



  <tr>

    <td>&nbsp;</td>

  </tr>

  <tr>

    <td><? if($cccode>0){?>CC CODE/PROJECT NAME: <? echo find_a_field('cost_center','center_name',"id='$cccode'")?><? }?></td>

  </tr>

  <tr>

    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000" class="tabledesign">

      <tr>

        <td align="center"><div align="center">SL</div></td>

        <td align="center">A/C Code </td>

        <td align="center">Particulars</td>
        <td align="center">Narration</td>

        <td>Debit</td>

        <td>Credit</td>

      </tr>



	  <?

$sql2=mysqli_query($conn, "SELECT a.ledger_id,a.ledger_name,sum(dr_amt) as dr_amt,b.narration FROM accounts_ledger a, secondary_journal b where b.jv_no='$jv_no' and a.ledger_id=b.ledger_id and jv_no=$jv_no and dr_amt>0 group by b.ledger_id desc order by b.id");
while($info=mysqli_fetch_object($sql2)){

	  ?>

      <tr>

        <td align="left"><div align="center">

          <?=++$s;

		  ?>

        </div></td>

        <td align="left"><?=$info->ledger_id?></td>

        <td align="left"><?=$info->ledger_name;?></td>
        <td align="left"><?=$info->narration;?></td>

        <td align="right"><? echo number_format($info->dr_amt,2); $ttd = $ttd + $info->dr_amt;?></td>

        <td align="right"><? echo number_format($info->cr_amt,2); $ttc = $ttc + $info->cr_amt;?></td>

        </tr>

<?php }?>

<?
$sql2=mysqli_query($conn, "SELECT a.ledger_id,a.ledger_name,sum(cr_amt) as cr_amt,b.narration FROM accounts_ledger a, secondary_journal b where b.jv_no='$jv_no' and a.ledger_id=b.ledger_id and jv_no=$jv_no and cr_amt>0 group by b.ledger_id desc order by b.id");
while($info=mysqli_fetch_object($sql2)){

	  ?>

      <tr>
        <td align="left"><div align="center"><?=++$s;?></div></td>
        <td align="left"><?=$info->ledger_id?></td>
        <td align="left"><?=$info->ledger_name?></td>
        <td align="left"><?=$info->narration?></td>
        <td align="right"><? echo number_format($info->dr_amt,2); $ttd = $ttd + $info->dr_amt;?></td>
        <td align="right"><? echo number_format($info->cr_amt,2); $ttc = $ttc + $info->cr_amt;?></td>
        </tr>

<?php }?>



      <tr>
        <td colspan="4" align="right">Total Taka: </td>
        <td align="right"><?=number_format($ttd,2)?></td>
        <td align="right"><?=number_format($ttc,2)?></td>
        </tr>



    </table></td>

  </tr>

  <tr>

    <td>&nbsp;</td>

  </tr>

  <tr>

    <td>Amount in Word :



	 (<?=convertNumberMhafuz($ttc)?>)	 </td>

  </tr>

  <tr>

    <td>&nbsp;</td>

  </tr>

  <tr>

    <td>&nbsp;</td>

  </tr>

  <tr>

    <td>&nbsp;</td>

  </tr>

  <tr>

    <td class="tabledesign_text"><table width="100%" border="0" cellspacing="0" cellpadding="0">

      <tr>

        <td align="center" valign="bottom">................................</td>

        <td align="center" valign="bottom">................................</td>

        <td align="center" valign="bottom">................................</td>

        <td align="center" valign="bottom">................................</td>

      </tr>

      <tr>

        <td width="33%"><div align="center">Received by </div></td>

        <td width="33%"><div align="center">Prepared by </div></td>

        <td width="33%"><div align="center">Head of Accounts </div></td>

        <td width="34%"><div align="center">Approved By </div></td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td>&nbsp;</td>

  </tr>

</table></form>

</body>

</html>
