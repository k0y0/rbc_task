<?php

namespace App\Utils\Import\MedicineOrder;

class MedicineOrderData
{
    public function __construct(
        public $customer,
        public $country,
        public $order,
        public $status,
        public $group,
        public $file
    ) {
    }
}