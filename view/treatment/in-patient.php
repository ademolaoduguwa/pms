<?php
require_once '../../_core/global/_require.php';

Crave::requireAll(GLOBAL_VAR);
Crave::requireAll(UTIL);

if (!isset($_SESSION[UserAuthTable::userid])) {
    header("Location: ../index.php");
}
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Treatment</title>

    <!-- Bootstrap core CSS -->
    <link href="../../css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/bootstrap/jquery-ui.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../css/master.css" rel="stylesheet">
    <link href="../../css/bootstrap/jquery.dataTables.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="../dashboard.php">
                <?php
                if(is_null(CxSessionHandler::getItem('hospital_name'))){
                    echo "Patient Management System";
                }else{
                    echo ucwords(CxSessionHandler::getItem('hospital_name'));
                }
                ?>
            </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right nav-pills">
                <li><a href="out-patient.php">Out-Patients</a> </li>
                <li class="active"><a>In-Patients</a> </li>
                <li>
                    <a href="../mails.php">
                        <span class="fa fa-envelope"></span>
                        <sup class="badge notification message_unread"></sup>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
                        <img src="../../images/profile.png">
                        <?php echo ucwords(CxSessionHandler::getItem(ProfileTable::surname).' '.CxSessionHandler::getItem(ProfileTable::firstname))?>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                        <li role="presentation"><a href="../dashboard.php">Dashboard</a></li>
                        <li class="divider"></li>
                        <li role="presentation"><a href="../view-profile.php">View Profile</a></li>
                        <li role="presentation"><a href="#" id="sign-out">Sign out</a></li>
                    </ul>
                </li>
            </ul>
            <form class="treatment navbar-form">
                <div class="search form-inline">
                    <input type="text" class="form-control" name="search" placeholder="Search Patients...">
                </div>
            </form>
        </div>
    </div>
</nav>

<script id="tmplPatients" type="text/html">
    <div class="panel {{status}} patient pointer">
        <div class="panel-heading" role="tab" id="heading{{patient_id}}">
            <h4 class="panel-title">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordion{{userid}}"
                   href="#collapse{{patient_id}}" aria-expanded="false" aria-controls="collapse{{patient_id}}">
                    {{regNo}}
                </a>
            </h4>
        </div>
        <div id="collapse{{patient_id}}" class="panel-collapse collapse" role="tabpanel"
             aria-labelledby="heading{{patient_id}}">
            <div class="panel-body">
                <p>{{name}}</p>
                <p>Admission requested by: {{doctor}}</p>
                <span class="ward_id" hidden>{{ward_id}}</span>
                <span class="admission_id" hidden>{{admission_id}}</span>
                <span class="patient_id" hidden>{{patient_id}}</span>
                <span class="doctor" hidden>{{doctor}}</span>
                <span class="patientName" hidden>{{name}}</span>
                <span class="treatment_id" hidden>{{treatment_id}}</span>
                <span class="patientRegNo" hidden>{{regNo}}</span>
                <span class="bed_id">Bed {{bed}}</span>
            </div>
        </div>
    </div>
</script>

<script id="tmplTreatmentHistory" type="text/html">
    <li class="list-group-item">
        <a class="collapsed treatment-history-template" data-toggle="collapse" data-parent="#accordion{{userid}}"
           href="#collapse{{treatmentid}}" aria-expanded="false" aria-controls="collapse{{treatmentid}}">
            <b>Diagnosis:</b> {{diagnosis}}
        </a>
        <div id="collapse{{treatmentid}}" class="panel-collapse collapse" role="tabpanel"
             aria-labelledby="heading{{treatmentid}}">
            <div class="panel-body">
                <p><b>Comments:</b> {{comments}}</p>
                <p><b>Title:</b> {{consultation}}</p>
                <span class="treatmentid" hidden>{{treatmentid}}</span>
                <span class="doctorid" hidden>{{doctorid}}</span>
                <p><b>Complaint:</b> {{symptoms}}</p>
                <p><b>Date:</b> {{date}}</p>
                <div class="accordion-inner">
                    <div class="accordion" id="encounteraccordion{{treatmentid}}"></div>
                </div>
            </div>
        </div>
    </li>
</script>

<script id="tmplEncounterHistory" type="text/html">
    <li class="list-group-item">
        <a class="collapsed" data-toggle="collapse" data-parent="#encounteraccordion{{userid}}"
           href="#encountercollapse{{treatmentid}}" aria-expanded="false" aria-controls="encountercollapse{{treatmentid}}">
            <b>Diagnosis:</b> {{diagnosis}}
        </a>
        <div id="encountercollapse{{treatmentid}}" class="panel-collapse collapse" role="tabpanel"
             aria-labelledby="heading{{treatmentid}}">
            <div class="panel-body">
                <p><b>Comments:</b> {{comments}}</p>
                <p><b>Title:</b> {{consultation}}</p>
                <span class="treatmentid" hidden>{{treatmentid}}</span>
                <span class="doctorid" hidden>{{doctorid}}</span>
                <p><b>Complaint:</b> {{symptoms}}
                <p><b>Date:</b> {{date}}</p>
            </div>
        </div>
    </li>
</script>

<script id="tmplVitals" type="text/html">
    <li class="list-group-item">
        <a class="collapsed" data-toggle="collapse" data-parent="#vitals{{userid}}"
           href="#collapse{{id}}" aria-expanded="false" aria-controls="collapse{{id}}">
            <b>Date:</b> {{created_date}}
        </a>
        <div id="collapse{{id}}" class="panel-collapse collapse" role="tabpanel"
             aria-labelledby="heading{{id}}">
            <div class="panel-body">
                <p><b>Blood Pressure:</b> {{blood_pressure}}</p>
                <p><b>BMI:</b> {{bmi}}</p>
                <p><b>Pulse:</b> {{pulse}}</p>
                <p><b>Respiratory Rate:</b> {{respiratory_rate}}</p>
                <p><b>Temperature:</b> {{temp}}</p>
                <p><b>Weight:</b> {{weight}}</p>
                <p><b>Height:</b> {{height}}</p>
                <span class="encounter_id" hidden>{{encounter_id}}</span>
                <span class="patient_id" hidden>{{patient_id}}</span>
            </div>
        </div>
    </li>
</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9">
            <br>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="pull-left patient-name">Please Select a Patient from the Queue <span class="fa fa-long-arrow-right"></span> <b>or</b> search for patient <span class="fa fa-long-arrow-up"></span></h4>
                    <span class="pull-right">
                        <button id="end" class="hidden btn btn-warning pull-right">End Session <span class="fa fa-close"></span></button>
                    </span>
                    <span class="clearfix"></span>
                </div>
                <div class="panel-body">
                    <p class="patient-RegNo"></p>
                    <span class="patient-Sex"></span>
                    <span class="patient-ID hidden"></span>
                    <span class="treatment-ID hidden"></span>
                    <span class="patient-Age"></span>
                    <div class="alert hidden alert-danger alert-dismissable" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <span class="alertMSG"></span>
                    </div>
                    <div class="alert hidden alert-success alert-dismissable" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <span class="successMSG"></span>
                    </div>
                </div>
            </div>

            <div class="well">
                <ol class="treatment-nav nav nav-pills nav-justified">
                    <li class="at active" onclick="switchTabs('add-treatment', 'at')"><a href="#">Treat Patient</a></li>
                    <li class="rt" onclick="switchTabs('request-test', 'rt')"><a href="#">Request Test</a></li>
                    <li class="vi" onclick="switchTabs('vitals', 'vi')"><a href="#">Vitals</a></li>
                    <li class="th" onclick="switchTabs('treatment-history', 'th')"><a href="#">Treatment History</a></li>
                    <li class="lh" onclick="switchTabs('lab-history', 'lh')"><a href="#">Lab History</a></li>
                </ol>
                <div class="add-treatment">
                    <form name="addTreatmentForm" class="form">
                        <div class="row">
                            <br/>
                            <div class="col-sm-12">
                                <div class="center-block">
                                    <label>Title:</label>
                                    <textarea type="text" class="form-control" name="consultation"></textarea>
                                </div>
                            </div>
                            <br/>
                            <div class="col-sm-6">
                                <br>
                                <div class="center-block"><label>Comments:</label>
                                    <textarea class="form-control" name="comment"></textarea>
                                </div>
                                <br/>

                                <div class="center-block"><label>Prescriptions:</label>
                                    <input list="drugNames" type="text" id="prescriptionInput" class="form-control" placeholder="">

                                    <p>
                                        <small>Type and press the Enter key to add to prescription list</small>
                                    </p>

                                    <datalist id="drugNames">

                                    </datalist>

                                    <ul class="list-group" id="prescriptions">
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <br>
                                <div class="center-block">
                                    <label>Complaint:</label>
                                    <textarea type="text" class="form-control" name="symptoms"></textarea>
                                </div>
                                <br/>

                                <div class="center-block"><label>Diagnosis:</label>
                                    <input type="text" class="form-control" name="diagnosis">
                                </div>
                                <br/>
                                <div>
<!--                                    <input  name="admit" type="checkbox">-->
<!--                                    <small>Request Admission? <span class="fa fa-bed"></span><span class="fa fa-spinner fa-spin hidden" id="loader"></span></small>-->
                                    <button id="treatmentSubmit" type="submit" class="btn btn-sm btn-primary pull-right">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="request-test hidden">
                    <form name="requestTestForm" class="form">
                        <div class="row">
                            <br/>
                            <div class="col-sm-5 col-md-3 form-inline">
                                <label>Test Type</label>
                                <select class="form-control" name="test_id">
                                    <option value="radiology">RADIOLOGY</option>
                                    <option value="haematology">HAEMATOLOGY</option>
                                    <option value="microscopy">MICROSCOPY</option>
                                    <option value="visual">VISUAL SKILL PROFILE</option>
                                    <option value="chemical_pathology">CHEMICAL PATHOLOGY</option>
                                    <option value="parasitology">PARASITOLOGY</option>
                                </select>
                                <br/>
                                <br/>
                                <button type="submit" class="btn btn-primary">Request Test</button>
                            </div>

                            <div class="col-sm-7 col-md-9">
                                <label>Description</label>
                                <textarea id="description" class="form-control" type="text"
                                          name="description"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="treatment-history hidden">
                    <br/>
                    <ul class="list-group history">
                    </ul>
                    <!--                    <button class="btn btn-info">Load More</button>-->
                </div>
                <div class="lab-history hidden">
                    <div class="table">
                        <br/>
                        <div class="center-block form-inline">
                            <label>Select a test: </label>
                            <select id="type" class="form-control" name="test_id">
                                <option value="radiology">RADIOLOGY</option>
                                <option value="haematology">HAEMATOLOGY</option>
                                <option value="microscopy">MICROSCOPY</option>
                                <option value="visual">VISUAL SKILL PROFILE</option>
                                <option value="chemical_pathology">CHEMICAL PATHOLOGY</option>
                                <option value="parasitology">PARASITOLOGY</option>
                            </select>
                        </div>
                        <br/>
                        <table class="table table-stripped table--bordered dataTable">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Diagnosis</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Acton</th>
                            </tr>
                            </thead>
                            <tbody class="table-data">
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>S/N</th>
                                <th>Diagnosis</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Acton</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="vitals hidden">
                    <br/>
                    <ul class="list-group">
                    </ul>
                    <!--                    <button class="btn btn-info">Load More</button>-->
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <br/>
            <div class="panel panel-primary doctor">
                <div class="panel-heading">
                    <p style="display: none;" id="doctorid"><?php echo ucwords(CxSessionHandler::getItem(ProfileTable::userid)); ?></p>
                    <h2 class="panel-title">Patient Queue</h2>
                </div>
                <div class="panel-body patients">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="../../js/bootstrap/jquery-1.10.2.min.js"></script>
<script src="../../js/bootstrap/jquery.dataTables.js"></script>
<script src="../../js/bootstrap/bootstrap.min.js"></script>
<script src="../../js/bootstrap/jquery-ui.min.js"></script>
<script src="../../js/constants.js"></script>
<script src="../../js/treatment/in-patient.js"></script>

<?php include('../footer.php'); ?>

</body>
</html>