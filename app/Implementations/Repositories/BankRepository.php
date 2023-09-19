<?php 

namespace App\Implementations\Repositories;

use App\Enums\BankStatusEnum;
use App\Enums\VirtualAccountEnum;
use App\Interfaces\Repositories\IBankRepository;
use App\Models\Bank;

class BankRepository implements IBankRepository{


    public function create($data){

        $bank = Bank::create($data);
        return $bank;
    }
    
    public function get(string $id){

        return Bank::with(['accounts'])->where('id', $id);
    }
    
    public function getAll(){

        return Bank::with(['accounts'])->get();
    }

    public function getByName($name)
    {
        
        $banks = Bank::with([])->where('name', $name)->get();

        return $banks;
    }


    public function getVirtualAccountBanks()
    {
        
        $banks = Bank::with([])
            ->where('is_virtual_account', VirtualAccountEnum::True->value)
            ->where('is_active', BankStatusEnum::Active->value)->get();

        return $banks;
    }



    
    public function update(Bank $bank, $data){
        
        $bank->update($data);

        return $bank;
    }
    
    public function delete(Bank $bank){

        return $bank->delete();
    }
    
}