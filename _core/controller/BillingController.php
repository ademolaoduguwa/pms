<?php
/**
 * Created by PhpStorm.
 * User: Olaniyi
 * Date: 5/18/15
 * Time: 11:21 AM
 */

class BillingController {
    private $systemBilling;

    public function __construct() {
        $this->systemBilling = new BillingModel();
    }

    public function unbilledTreatment() {
        return $this->systemBilling->unbilledTreatment();
    }

    public function billTreatment($treatment_id) {
        return $this->systemBilling->billTreatment($treatment_id);
    }

    public function getDetails($treatment_id) {
        return $this->systemBilling->getDetails($treatment_id);
    }

    public function postBills ($data) {
        return $this->systemBilling->postBills($data);
    }

    public function addBillingItems($billAmountArray){
        return $this->systemBilling->addBillingItems($billAmountArray);
    }

    public function getBillItems(){
        return $this->systemBilling->getBillItems();
    }

    public function deleteBillItem($id){
        return $this->systemBilling->deleteBillItem($id);
    }

    public function editBillItem($id, $bill, $amount){
        return $this->systemBilling->editBillItem($id, $bill, $amount);
    }
}