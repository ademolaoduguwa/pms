<?php
/**
 * Created by PhpStorm.
 * User: olajuwon
 * Date: 2/16/2015
 * Time: 1:10 PM
 */
require_once '../_core/global/_require.php';

Crave::requireAll(GLOBAL_VAR);
Crave::requireAll(UTIL);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8' />
    <title>Admin Dashboard</title>
    <link href="../css/bootstrap/bootstrap.min.css" rel="stylesheet">

<!--    <link href='../css/libs/fullCalendar/fullcalendar.css' rel='stylesheet' />-->
<!--    <link href='../css/libs/fullCalendar/fullcalendar.print.css' rel='stylesheet' media='print' />-->
    <link href="../css/master.css" rel="stylesheet">
</head>
<body>
<!--page header-->
<?php Crave::requireFiles(HEADERS, array('main')) ?>

<script id="tmplPatients" type="text/html">
    <div class="panel {{status}} patient pointer">
        <div class="panel-heading" role="tab" id="heading{{id}}">
            <h4 class="panel-title">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordion{{id}}"
                   href="#collapse{{id}}" aria-expanded="false" aria-controls="collapse{{id}}">
                    {{regNo}}
                </a>
            </h4>
        </div>
        <div id="collapse{{id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{patientid}}">
            <div class="panel-body">
                <p>{{name}}</p>
                <p class="name hidden">{{name}}</p>
                <p class="regNo hidden">{{regNo}}</p>
                <span class="treatment_id hidden">{{treatment_id}}</span>
                <span class="treatment_status hidden">{{treatment_status}}</span>
                <span class="bill_status hidden">{{bill_status}}</span>
            </div>
        </div>
    </div>
</script>

<div class="container-fluid">
    <div class="row">
        <!--   Bill management -->
        <div>
            <div class="col-sm-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <form id="search-patient-form">
                            <input id="patient_query" class="form-control" placeholder="Search...">
                        </form>
                    </div>
                    <div class="panel-body in-patient-list">
                        <div id="unbilled-patients">
                            <h2 class="text-muted text-center">No Unbilled Patient</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="well in-patient-content">
                    <br>
                    <div class="div-rounded encounter-icon">
                        <span class="fa fa-money"></span>
                    </div>
                    <div id="print-header">
                        <p id="patientRegNo" class="text-center h3"></p>
                        <p id="patientName" class="text-center h4"></p>
                    </div>
                    <div class="h2 none text-center">
                        <span class="fa fa-arrow-circle-o-left"></span> Please select a patient to bill
                    </div>
                    <form id="bill" name="bill" class="bill hidden table-responsive">
                        <table class="table table-stripped">
                            <thead>
                            <tr>
                                <th>Item</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="2">
                                    <button type="button" id="add_more" class="btn btn-primary btn-sm pull-right">
                                        Add More
                                        <span class="fa fa-angle-down"></span>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <th id="total">0.00</th>
                            </tr>
                            </tfoot>
                        </table>

                    </form>
                    <div class="clearfix"></div>
                    <div id="log_encounter_loading" class="text-center hidden"><span class="fa fa-spinner fa-spin"></span> </div>
                    <div class="text-center" id="log_encounter_response"></div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="room-overview">
                    <div class="room-overview__heading">
                        <h2 class="text-center"><span class="fa fa-database"></span></h2>
                        <h3 class="text-center">Overview</h3>
                    </div>
                    <div class="one hidden">
                        <br>
                        <p class="text-center">
                            <span class="fa fa-history"></span>
                            <span id="days_spent"></span>
                        </p>
                        <p>
                            <p class="h4 text-center">Prescribed Drugs</p>
                            <div id="prescription"></div>
                        </p>
                        <p class="text-center">
                            <p class="h4 text-center">Lab Tests Performed</p>
                            <p id="test" class="test text-center"></p>
                        </p>
                    </div>
                    <div>
                        <br/><br/>
                        <div class="text-center">
                            <button class="btn btn-success bill hidden" id="print">Print</button>
                            <div id="print_error" class="text-danger"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--  MODAL FORM-->
<div class="modal fade" id="room-modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="fa fa-2x text-danger" aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--  -->
<script src='../js/bootstrap/jquery.min.js'></script>
<script src='../js/bootstrap/bootstrap.min.js'></script>
<script src="../js/constants.js"></script>
<script src="../js/billing.js"></script>
<?php include('footer.php'); ?>

</body>
</html>
