<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ODUGUWA A
 * Date: 3/17/15
 * Time: 2:36 PM
 * To change this template use File | Settings | File Templates.
 */

require_once '../_core/global/_require.php';

Crave::requireAll(GLOBAL_VAR);
Crave::requireFiles(UTIL, array('SqlClient', 'JsonResponse', 'CxSessionHandler'));
Crave::requireFiles(MODEL, array('BaseModel', 'TreatmentModel', 'ChemicalPathologyModel', 'HaematologyModel', 'MicroscopyModel', 'ParasitologyModel', 'VisualModel', 'RadiologyModel', 'PharmacistModel'));
Crave::requireFiles(CONTROLLER, array('TreatmentController', 'LaboratoryController', 'PharmacistController'));


if (isset($_REQUEST['intent'])) {
    $intent = $_REQUEST['intent'];
} else {
    echo JsonResponse::error('Intent not set!');
    exit();
}

if ($intent == 'getPatientQueue') {

    $treat = new TreatmentController();

    if (isset($_REQUEST['doctorid'])){
        $doctor_id = $_REQUEST['doctorid'];
    }
    else{
        echo JsonResponse::error("Doctor_id not Set");
        exit();
    }
    $patient_queue =$treat->getPatientQueue($doctor_id);

    if(is_array($patient_queue)){
        echo JsonResponse::success($patient_queue);
        exit();
    } else {
        echo JsonResponse::error("Could not Find any Patient queue. Please try again!");
        exit();
    }
}

elseif ($intent == 'getInpatientQueue') {

    $treat = new TreatmentController();
    $inpatient_queue = $treat->getInpatientQueue();

    if(is_array($inpatient_queue)){
        echo JsonResponse::success($inpatient_queue);
        exit();
    } else {
        echo JsonResponse::error("Could not Find any In Patient queue. Please try again!");
        exit();
    }

}

elseif ($intent == 'getAdmittedPatientQueue') {

    $treat = new TreatmentController();
    $adpatient_queue = $treat->getAdmittedPatientQueue();

    if(is_array($adpatient_queue)){
        echo JsonResponse::success($adpatient_queue);
        exit();
    } else {
        echo JsonResponse::error("Could not Find any adPatient queue. Please try again!");
        exit();
    }

}

elseif  ($intent == 'requestAdmission') {

    if (isset($_REQUEST['treatment_id'])){
        $treatment_id = $_REQUEST['treatment_id'];
    }
    else{
        echo JsonResponse::error("treatment_id not Set");
        exit();
    }

    $treat = new TreatmentController();
    $request_adm = $treat->requestAdmission($treatment_id);

    if(is_array($request_adm)){
        echo JsonResponse::success($request_adm);
        exit();
    } else {
        echo JsonResponse::error("Could not admission. Please try again!");
        exit();
    }


}

elseif  ($intent == 'startTreatment') { //working


    $treat = new TreatmentController();

    $doctorid ="";
    $patientid ="";
//    $consultation ="";
//    $symptoms ="";
//    $comments= "";
//    $diagnosis ="";

    if (isset($_REQUEST['doctor_id']) && isset($_REQUEST['patient_id'])){  // change surname to what you thin should be set.

        $doctorid =$_REQUEST[TreatmentTable::doctor_id];
        $patientid =$_REQUEST[TreatmentTable::patient_id];
        $consultation =" ";
        $symptoms =" ";
        $comments= " ";
        $diagnosis =" ";



    }
    else {
        echo JsonResponse::error("things are not set");
        exit();
    }

    $admission_add = null;

    if (empty($doctorid) || empty ($patientid) ){

//        print_r($_REQUEST);
        echo JsonResponse::error("Some fields are not filled, Ensure All fields are filled");
        exit();
    }
    else{

        $newaddm = new TreatmentController();

        // check if patient has treatment before, if so return existing treatment_id, otherwise, create ne treament id.
        $hasTreatmentbefore = $newaddm->doesTreatmentExist ($patientid);

        if ($hasTreatmentbefore == 0)
        {


            $admission_add = $newaddm->addTreatment1($doctorid, $patientid, $consultation, $symptoms, $diagnosis, $comments);
        }
//        $admission_add = $newaddm->addTreatment1($doctorid, $patientid);
        else{
            //   echo print_r($hasTreatmentbefore);
            //  echo print_r($hasTreatmentbefore [1]);
            //  echo print_r($hasTreatmentbefore [0]);
            //  echo print_r($hasTreatmentbefore [1]);
            $admission_add= array(TreatmentTable::treatment_id => $hasTreatmentbefore);


        }
    }


    if($admission_add){
        //var_dump($admission_add);
        echo JsonResponse::success($admission_add);
        exit();
    } else {
//        ($_REQUEST);
//        echo'admission';
        echo $admission_add;
        echo JsonResponse::error("Error starting treatment process");
        exit();
    }

}
elseif  ($intent == 'endTreatment') { //working

    $treat = new TreatmentController();

    $treatment_id ="";
    $patientid ="";
//    $consultation ="";
//    $symptoms ="";
//    $comments= "";
//    $diagnosis ="";

    if (isset($_REQUEST['treatment_id']) && isset($_REQUEST['patient_id'])){  // change surname to what you thin should be set.

        $treatment_id =$_REQUEST[TreatmentTable::treatment_id];
        $patientid =$_REQUEST[TreatmentTable::patient_id];
        $consultation ="";
        $symptoms ="";
        $comments= "";
        $diagnosis ="";

    }
    else {
        echo JsonResponse::error("things are not set");
        exit();
    }

    $admission_end = null;

    if (empty($treatment_id) || empty ($patientid) ){

//        print_r($_REQUEST);
        echo JsonResponse::error("Some fields are not filled, Ensure All fields are filled");
        exit();
    }
    else{

        $newaddm = new TreatmentController();

        $admission_end = $newaddm->endTreatment($treatment_id);

    }

    if(! $admission_end ){
        echo print_r($admission_end);
        echo JsonResponse::success(!$admission_end);
        exit();

    }
    else {
        echo print_r($admission_end);
        echo JsonResponse::error("Error ending treatment process");
        exit();

    }



}


elseif  ($intent == 'submitTreatment') { //working
    $treat = new TreatmentController();

    $doctorid ="";
    $patientid ="";
    $treatment_id="";
    $consultation ="";
    $symptoms ="";
    $comments= "";
    $diagnosis ="";
    $prescription ="";


    if (isset($_REQUEST['doctor_id']) && isset($_REQUEST['patient_id'])){  // change surname to what you thin should be set.

        $doctorid =$_REQUEST[TreatmentTable::doctor_id];
        $patientid =$_REQUEST[TreatmentTable::patient_id];
        $consultation =$_REQUEST[TreatmentTable::consultation];
        $symptoms =$_REQUEST[TreatmentTable::symptoms];
        $comments= $_REQUEST[TreatmentTable::comments];
        $diagnosis =$_REQUEST[TreatmentTable::diagnosis];
        $treatment_id =$_REQUEST['treatment_id'];
        $prescription = $_REQUEST['prescription'];
    }
    else {
        echo JsonResponse::error("things are not set");
        exit();
    }

    $admission_add = null;

    if (empty($treatment_id) || empty($doctorid) || empty ($patientid) || empty ($consultation) || empty ($symptoms) || empty ($diagnosis) || empty ($comments)){

        //print_r($_REQUEST);
        echo JsonResponse::error("MANY filled, Ensure All fields are filled");
        exit();
    }
    else{
//      print_r($_REQUEST);

        $newaddm = new TreatmentController();
        $admission_add = $newaddm->addTreatment2($doctorid, $patientid, $consultation, $symptoms, $diagnosis, $comments, $treatment_id);

        if ($admission_add){

            foreach ($prescription as $somepre) {
                $status = ACTIVE;
                $mod = DOCTOR;
                $pre  = new PharmacistController();
                $pre->AddPrescription($somepre, $treatment_id, $status, $mod);
                if(!$pre){
                    exit;
                }
            }

        }

    }
    if($admission_add && $pre){
        echo JsonResponse::success($admission_add);
        exit();
    } else {
//        print_r($_REQUEST);
        echo JsonResponse::error("Automatic Error creating treatment and prescription");
        exit();
        //otu
    }


}
// for starting a treatment for an emergency patient however, an emergency patient already has a patient id
//it will return an emergency_id for the emergency patient.

/*elseif  ($intent == 'startEmergencyTreatment') { // for starting a treatment for an emergency patient however, an emergency patient already has a patient id

    $treat = new TreatmentController();

    $doctorid ="";
    $patientid ="";
//    $consultation ="";
//    $symptoms ="";
//    $comments= "";
//    $diagnosis ="";

    if (isset($_REQUEST['doctor_id']) && isset($_REQUEST['patient_id'])){  // change surname to what you thin should be set.

        $doctorid =$_REQUEST[TreatmentTable::doctor_id];
        $patientid =$_REQUEST[TreatmentTable::patient_id];
        $consultation ="";
        $symptoms ="";
        $comments= "";
        $diagnosis ="";

    }
    else {
        echo JsonResponse::error("No data from view, needs patient and doctor id");
        exit();
    }

    $startTreatment= null;

    if (empty($doctorid) || empty ($patientid) ){

//        print_r($_REQUEST);
        echo JsonResponse::error("patient_id and doctor_id are not set");
        exit();
    }
    else{

        $startTreatment = new TreatmentController();
        $startTreatment = $startTreatment->addTreatment1($doctorid, $patientid, $consultation, $symptoms, $diagnosis, $comments);
//        $admission_add = $newaddm->addTreatment1($doctorid, $patientid);
    }


    if($startTreatment){
        //var_dump($admission_add);
        echo JsonResponse::success($admission_add);
        exit();
    } else {
//        print_r($_REQUEST);
//        echo'admission';
//        var_dump($admission_add);
        echo JsonResponse::error("Error starting treatment process");
        exit();
    }

}*/

elseif  ($intent == 'getTreatmentHistory') {

    if (isset($_REQUEST['patient_id'])){
        $patientid = $_REQUEST['patient_id'];
    }
    else{
        echo JsonResponse::error("patient_id not Set");
        exit();
    }

    $treat = new TreatmentController();
    $request_adm = $treat->getTreatmentHistory($patientid);

    if(is_array($request_adm)){
        //echo JsonResponse::success($request_adm);
        echo JsonResponse::success(array($request_adm));
        //echo array($request_adm);
        exit();
    } else {
        echo JsonResponse::error("Could not get history. Please try again!");
        exit();
    }
}

elseif  ($intent == 'requestLabTest') {

    $treat = new TreatmentController();

    //$doctorId, $treatmentId, $labTestType, $comment

    $treatmentId ="";
    $doctorId ="";
    $labTestType ="";
    $comment= "";

    if (isset($_REQUEST['treatment_id']) && isset($_REQUEST['doctor_id'])){  // change surname to what you thin should be set.

        // var_dump($_REQUEST);

        $doctorId =$_REQUEST[TreatmentTable::doctor_id];
        $treatmentId =$_REQUEST[TreatmentTable::treatment_id];
        $labTestType = $_REQUEST['test_id'];
        $comment= $_REQUEST[TreatmentTable::comments];
    }
    else {
        echo JsonResponse::error("things are not set");
        exit();
    }

    $admission_add = null;

    if (empty($treatmentId) || empty ($doctorId) || empty ($labTestType) || empty ($comment)){

        echo JsonResponse::error("Some fields are not filled, Ensure All fields are filled");
        exit();
    }
    else{

        $newaddm = new TreatmentController();
        $admission_add = $newaddm->requestLabTest($doctorId, $treatmentId, $labTestType, $comment);
    }

    if($admission_add){
        echo JsonResponse::success($admission_add);
        exit();
    } else {
        print_r($_REQUEST);
        echo JsonResponse::error("Error requesting lab test");
        exit();
    }


}

elseif  ($intent == 'logEncounter') {
    $treat = new TreatmentController();

    $doctorId ="";
    $patientId="";
    $admissionId="";
    $comments="";


    if (/*isset($_REQUEST['admissionId']) &&*/ isset($_REQUEST['patient_id'])){

        // var_dump($_REQUEST);

        $doctorId =$_REQUEST[TreatmentTable::doctor_id];
        $patientId=$_REQUEST[TreatmentTable::patient_id];
        $admissionId=$_REQUEST[AdmissionTable::admission_id];
        $comments=$_REQUEST[TreatmentTable::comments];

    }
    else {
        echo JsonResponse::error("things are not set");
        exit();
    }

    $admission_add = null;

    if (empty($doctorId) || empty ($patientId) || empty ($admissionId) || empty ($comments)){


        //print_r($_REQUEST);
        echo JsonResponse::error("Some fields are not filled, Ensure All fields are filled");
        exit();
    }
    else{

        $newaddm = new TreatmentController();
        $admission_add = $newaddm->logEncounter($doctorId, $patientId , $admissionId, $comments);
    }

    if($admission_add){
        echo JsonResponse::success($admission_add);
        exit();
    } else {
//        print_r($_REQUEST);
        echo JsonResponse::error("Error logging Encounter");
        exit();
    }


}

elseif  ($intent == 'getEncounterHistory') {

    if (isset($_REQUEST['admission_id'])){
        $admissionId = $_REQUEST['admission_id'];
    }
    else{
        echo JsonResponse::error("patient_id not Set");
        exit();
    }

    $treat = new TreatmentController();
    $request_adm = $treat->getTreatmentHistory($admissionId);

    if(is_array($request_adm)){
        echo JsonResponse::success($request_adm);
        exit();
    } else {
        echo JsonResponse::error("Could not get history. Please try again!");
        exit();
    }
}


elseif  ($intent == 'searchPatient') {

    $treat = new TreatmentController();


    if (isset($_REQUEST['treatment_id']) ){  // change surname to what you thin should be set.

        $patientName =$_REQUEST[''];

    }
    else {
        echo JsonResponse::error("things are not set");
        exit();
    }

    $admission_add = null;

    if (empty($patientName) ){

        echo JsonResponse::error("Some fields are not filled, Ensure All fields are filled");
        exit();
    }
    else{

        $newaddm = new TreatmentController();
        $admission_add = $newaddm->searchPatient($patientName);
    }

    if($admission_add){
        echo JsonResponse::success($admission_add);
        exit();
    } else {
//        print_r($_REQUEST);
        echo JsonResponse::error("No patient found");
        exit();
    }


}
elseif  ($intent == 'getLabHistory') {

    $treat = new TreatmentController();

    $patientId ="";
    $labTestType ="";

    if (isset($_REQUEST['treatment_id']) && isset($_REQUEST['patient_id'])){  // change surname to what you thin should be set.

        // var_dump($_REQUEST);

        $patientId =$_REQUEST[TreatmentTable::patient_id];
        $labTestType = $_REQUEST['test_id'];  // no tbale for this variable.

    }
    else {
        echo JsonResponse::error("things are not set");
        exit();
    }

    $admission_add = null;

    if (empty ($patientId) || empty ($labTestType) ){

        //print_r($_REQUEST);
        echo JsonResponse::error("Some fields are not filled, Ensure All fields are filled");
        exit();
    }
    else{

        $newaddm = new TreatmentController();
        $admission_add = $newaddm->getLabHistory($patientId, $labTestType);
    }

    if($admission_add){
        echo JsonResponse::success($admission_add);
        exit();
    } else {
//        print_r($_REQUEST);
        echo JsonResponse::error("Error getting lab history");
        exit();
    }


}

elseif($intent == 'labHistory'){
    if(isset($_REQUEST['labType'])){
        $type = $_REQUEST['labType'];
        $patientId = intval($_REQUEST['patientId']);
        $lab = new LaboratoryController();

        $result = $lab->getLabHistory($type, $patientId);
        if($result){
            echo JsonResponse::success($result);
            exit();
        } else {
            echo JsonResponse::error("No test found for this patient");
            exit();
        }
    } else {
        echo JsonResponse::error("Please select a lab type");
        exit();
    }
}
elseif($intent == 'labRequest'){
    if(isset($_REQUEST['labType'])){
        $type = $_REQUEST['labType'];
        $doctorId = intval(CxSessionHandler::getItem('userid'));
//        $doctorId = 1;
        $treatmentId = intval($_REQUEST['treatmentId']);
        $description = isset($_REQUEST['description']) ? $_REQUEST['description'] : "";
        $lab = new LaboratoryController();

        $result = $lab->requestLabTest($type, $doctorId, $treatmentId, $description);
        if($result){
            echo JsonResponse::success("Request successful");
            exit();
        } else {
            echo JsonResponse::error("Request unsuccessful. Try again!");
            exit();
        }
    } else {
        echo JsonResponse::error("Please select a lab type");
        exit();
    }
}
else{
    JsonResponse::error("No intent set");
}