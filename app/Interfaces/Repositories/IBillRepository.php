<?php 

namespace App\Interfaces\Repositories;


use App\Models\Bill;



interface IBillRepository{

    public function create($data);
    public function fetch(string $id);
    public function fetchAll();
    public function fetchByUserId($user_id);
    public function store(Bill $bill);
    public function update(Bill $bill, $data);
    public function delete(Bill $bill);

}

