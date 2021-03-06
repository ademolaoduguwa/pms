<?php

class BillingModel extends BaseModel {

    private function getDaysSpent($treatment_id){
        $data = array(TreatmentTable::treatment_id => $treatment_id);
        $admitted = $this->conn->fetch(TreatmentSqlStatement::DAYS_SPENT, $data);

        if ($admitted){
            return $admitted;
        } else {
            return array('days_spent' => "No admission");
        }
    }

    private function getPrescription($treatment_id){
        $data = array(TreatmentTable::treatment_id => $treatment_id);
        $prescription = $this->conn->fetchAll(TreatmentSqlStatement::PRESCRIPTION, $data);
        if($prescription) {
            return $prescription;
        } else {
            return array('prescription'  => 'No Drug Prescribed');
        }
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
            return array('test'  => 'No Test');
        }
    }

    public function unbilledTreatment() {
        return $this->conn->fetchAll(TreatmentSqlStatement::UNBILLED_TREATMENT, array());
    }

    public function billTreatment($treatment_id) {
        return $this->conn->execute(TreatmentSqlStatement::UPDATE_BILL_TREATMENT, $treatment_id);
    }

    public function getDetails($treatment_id) {
        $details['days_spent'] = $this->getDaysSpent($treatment_id);
        $details['prescription'] = $this->getPrescription($treatment_id);
        $details['test'] = $this->getTest($treatment_id);

        return $details;
    }

    public function postBills($data) {
        $this->conn->execute(TreatmentSqlStatement::POSTBILLS, $data);
        $res = $this->conn->getLastInsertedId();

        return $res;
    }

    public function addBillingItems($billAmountArray){
        $stmt = BillingSqlStatement::ADD_BILL_ITEMS;
        foreach($billAmountArray as $obj){
            $billable = $obj['bill']; $amount = $obj['amount'];
            $stmt .= "('$billable', '$amount'), ";
        }
        $stmt = rtrim($stmt, " ,");
        return $this->conn->execute($stmt, array());
    }

    public function getBillItems(){
        return $this->conn->fetchAll(BillingSqlStatement::GET_BILL_ITEMS, array());
    }

    public function deleteBillItem($id){
        $data = array(BillablesTable::billables_id => $id);
        return $this->conn->execute(BillingSqlStatement::DELETE_BILL_ITEM, $data);
    }

    public function editBillItem($id, $bill, $amount){
        $data = array(BillablesTable::billables_id => $id, BillablesTable::bill => bill,
                      BillablesTable::amount => $amount);
        return $this->conn->execute(BillingSqlStatement::EDIT_BILL_ITEM, $data);
    }
}
