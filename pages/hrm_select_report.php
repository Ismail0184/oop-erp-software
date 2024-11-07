<?php
require_once 'support_file.php';
$title='Report';
$page='hrm_select_report.php';
$report_id = @$_REQUEST['report_id'];
?>


<?php require_once 'header_content.php'; ?>
<SCRIPT language=JavaScript>
    function reload(form)
    {
        var val=form.report_id.options[form.report_id.options.selectedIndex].value;
        self.location='<?=$page;?>?report_id=' + val ;
    }
    function reload1(form)
    {
        var val=form.report_id.options[form.report_id.options.selectedIndex].value;
        var val2=form.ledgercode.options[form.ledgercode.options.selectedIndex].value;
        self.location='<?=$page;?>?report_id=' + val +'&ledgercode=' + val2 ;
    }

</script>
    <style>
        input[type=text]{
            font-size: 11px;
        }
        input[type=date]{
            font-size: 11px;
        }


    </style>

<?php require_once 'body_content_nva_sm.php'; ?>

    <form class="form-horizontal form-label-left" method="POST" action="hrm_reportview.php" style="font-size: 11px" target="_blank">
        <div class="col-md-5 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <?=$crud->select_a_report(10);?>
                </div>
            </div>
        </div>

        <div class="col-md-7 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><small class="text-danger">field marked with * are mandatory</small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?php if ($report_id=='1000101'): ?>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Designation</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="PBI_DESIGNATION" >
                                    <option></option>
                                    <?=foreign_relation('designation', 'DESG_ID', 'CONCAT(DESG_ID," : ", DESG_DESC)','', '1'); ?>
                                </select>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Department</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="department" >
                                    <option></option>
                                    <?=foreign_relation('department', 'DEPT_ID', 'CONCAT(DEPT_ID," : ", DEPT_DESC)','', '1'); ?>
                                </select>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Service Status <span class="required text-danger">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="select2_single form-control" style="width:100%" tabindex="-1"  name="PBI_JOB_STATUS"  id="PBI_JOB_STATUS">
                                    <option></option>
                                    <option value="In Service" selected>In Service</option>
                                    <option value="Not In Service">Not In Service</option>
                                </select>
                            </div>
                        </div>



                    <?php elseif ($report_id=='1000201' || $report_id=='1000202' || $report_id=='1000203' || $report_id=='1000204' || $report_id=='1000205'): ?>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Department</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="select2_single form-control" style="width:100%" tabindex="-1"   name="department" >
                                    <option></option>
                                    <?=foreign_relation('department', 'DEPT_ID', 'CONCAT(DEPT_ID," : ", DEPT_DESC)','', '1'); ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Employee Name</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select style="width: 100%;" class="select2_single form-control" name="PBI_ID" id="PBI_ID">
                                    <option></option>
                                    <?=foreign_relation('personnel_basic_info', 'PBI_ID', 'CONCAT(PBI_ID_UNIQUE," : ", PBI_NAME)','', '1'); ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date From <span class="required text-danger">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="date"  required="required" name="f_date"   class="form-control col-md-7 col-xs-12" placeholder="From Date" autocomplete="off"></td>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Date to <span class="required text-danger">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="date"  required="required" name="t_date"  class="form-control col-md-7 col-xs-12"  placeholder="to Date" autocomplete="off"></td>
                            </div>
                        </div>


                    <?php  else:  ?>
                        <h5 class="text-danger" style="text-align: center">Please select a report from left</h5>
                    <?php endif; ?>

                    <?php if ($report_id>0): ?>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                            <a href="<?=$page;?>"  class="btn btn-danger" style="font-size: 12px">Cancel</a>
                            <button type="submit" class="btn btn-primary" name="getstarted" style="font-size: 12px">Generate Report</button>
                        </div>
                    </div>
                    <?php  else:  ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </form>

<?=$html->footer_content();?>