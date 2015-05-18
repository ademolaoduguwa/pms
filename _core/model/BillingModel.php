<?php

class BillingModel extends BaseModel {

    private function getDaysSpent($treatment_id){
        $data = array(TreatmentTable::treatment_id => $treatment_id);
        $admitted = $this->conn->fetch(TreatmentSqlStatement::DAYS_SPENT, $data);

        if ($admitted){
            return $admitted;
        } else {
            return "No admission";
        }
    }

    private function getPrescription($treatment_id){
        $data = array(TreatmentTable::treatment_id => $treatment_id);
        $prescription = $this->conn->fetchAll(TreatmentSqlStatement::PRESCRIPTION, $data);
        if($prescription) {
            return $prescription;
        } else {return 'No Drug Prescribed';}
    }

    private function getTest($treatment_id) {
        $data = array(TreatmentTable::treatment_id => $treatment_id);
        $blood = $this->conn->fetch(TreatmentSqlStatement::BLOODTEST, $data);
        $urine = $this->conn->fetch(TreatmentSqlStatement::URINETEST, $data);
        $visual = $this->conn->fetch(TreatmentSqlStatement::VISUALTEST, $data);
        $chemical = $this->conn->fetch(TreatmentSqlStatement::CHEMICALTEST, $data);
        $para = $this->conn->fetch(TreatmentSqlStatement::PARATEST, $data);
        $radiology = $this->conn->fetch(TreatmentSqlStatement::RADIOLOGYTEST, $data);

        $test = array();
        if ($blood && !empty($blood)){
            $test['blood_test'] = $blood;
        }
        if ($urine && !empty($urine)){
            $test['urine_test'] = $urine;
        }
        if ($visual && !empty($visual)){
            $test['visual_test'] = $visual;
        }
        if ($chemical && !empty($chemical)){
            $test['chemical_test'] = $chemical;
        }
        if ($para && !empty($para)){
            $test['parasitology_test'] = $para;
        }
        if ($radiology && !empty($radiology)){
            $test['radiology_test'] = $radiology;
        }

        if($test){
            return $test;
        } else {
            return 'No test';
        }
    }

    public function unbilledTreatment() {
        return $this->conn->fetchAll(TreatmentSqlStatement::UNBILLED_TREATMENT, array());
    }

    public function getDetails($treatment_id) {
        $details = $this->getDaysSpent($treatment_id);
        $details = $this->getPrescription($treatment_id);
        $details['test'] = $this->getTest($treatment_id);

        return $details;
    }
}
