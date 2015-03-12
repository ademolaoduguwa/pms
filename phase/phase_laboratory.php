<?php

require_once '../_core/global/_require.php';

Crave::requireAll(GLOBAL_VAR);
Crave::requireFiles(UTIL, array('SqlClient', 'JsonResponse', 'CxSessionHandler'));
Crave::requireFiles(MODEL, array('BaseModel', 'ChemicalPathologyModel', 'HaematologyModel', 'MicroscopyModel', 'ParasitologyModel', 'VisualModel', 'RadiologyModel'));
Crave::requireFiles(CONTROLLER, array('LaboratoryController'));

if (isset($_REQUEST['intent'])) {
    $intent = $_REQUEST['intent'];
} else {
    echo JsonResponse::error('Intent not set!');
    exit();
}

if ($intent == 'getPatientQueue') {
    if (isset($_REQUEST['labType'])) {
        $labType = $_REQUEST['labType'];

        $lab = new LaboratoryController();
        $queue = $lab->getPatientQueue($labType);
        if (is_array($queue) && !empty($queue)) {
            echo JsonResponse::success($queue);
            exit();
        } else {
            echo JsonResponse::error("No patient on queue");
            exit();
        }
    } else {
        echo JsonResponse::error("No lab type chosen");
        exit();
    }
} elseif ($intent == 'getLabDetails') {
    if (isset($_REQUEST['labType']) && isset($_REQUEST['treatmentId'])) {
        $labType = $_REQUEST['labType'];
        $treatmentId = $_REQUEST['treatmentId'];

        $lab = new LaboratoryController();
        $details = $lab->getLabDetails($labType, $treatmentId);
        if (is_array($details) && !empty($details)) {
            echo JsonResponse::success($details);
            exit();
        } else {
            echo JsonResponse::error("This patient has no lab data yet!");
            exit();
        }
    } else {
        echo JsonResponse::error("No lab type or treatment id chosen");
        exit();
    }
} elseif ($intent == 'setLabDetails') {
    if (isset($_REQUEST['labType']) && isset($_REQUEST['data'])) {
        $labType = $_REQUEST['labType'];
        $data = $_REQUEST['data'];

        $lab = new LaboratoryController();
        $response = $lab->setLabDetails($labType, $treatmentId);
        if ($response) {
            echo JsonResponse::success("Successfully added");
            exit();
        } else {
            echo JsonResponse::error("Could not add the lab details");
            exit();
        }
    } else {
        echo JsonResponse::error("No lab type or data to add");
        exit();
    }
} elseif ($intent == 'updateLabDetails') {
    if (isset($_REQUEST['labType']) && isset($_REQUEST['data'])) {
        $labType = $_REQUEST['labType'];
        $data = $_REQUEST['data'];

        $lab = new LaboratoryController();
        $response = $lab->updateLabDetails($labType, $treatmentId);
        if ($response) {
            echo JsonResponse::success("Successfully updated");
            exit();
        } else {
            echo JsonResponse::error("Could not update the lab details");
            exit();
        }
    } else {
        echo JsonResponse::error("No lab type or data to update");
        exit();
    }
} else {
    echo JsonResponse::error("Invalid intent!");
    exit();
}