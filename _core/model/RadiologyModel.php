<?php
class RadiologyModel extends BaseModel{

    public function getPatientQueue($status = PENDING, $activeFg = 1){
        $data = array(RadiologyTable::status_id => $status, RadiologyRequestTable::active_fg => $activeFg);
        return $this->conn->fetchAll(RadiologyRequestSqlStatement::GET_PATIENT_QUEUE, $data);
    }

    public function getAllTest($activeFg = 1){
        $data = array(RadiologyRequestTable::active_fg => $activeFg);
        return $this->conn->fetchAll(RadiologyRequestSqlStatement::GET_ALL_TEST, $data);
    }

    public function getTestDetails($treatmentId){
        $data = array(RadiologyTable::treatment_id => $treatmentId);
        return $this->conn->fetch(RadiologyRequestSqlStatement::GET_DETAILS, $data);
    }

    public function setTestDetails($data){
        return $this->updateTestDetails($data);
    }

    public function updateTestDetails($data){
        try{
            $this->conn->beginTransaction();
            $this->conn->execute(RadiologyRequestSqlStatement::UPDATE_DETAILS, $data['details']);
            $this->conn->execute(RadiologySqlStatement::UPDATE, $data['radiology']);
            $this->conn->commit();
        } catch(Exception $e){
            $this->conn->rollBack();
            echo $e->getMessage();
            return false;
        }

        return true;
    }

}