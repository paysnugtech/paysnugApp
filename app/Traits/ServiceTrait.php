<?php 

namespace App\Traits;
use App\Interfaces\Repositories\IServiceRepository;

trait ServiceTrait{

    
    protected function getChargesByName($name){

        $charges = $this->serviceRepository->fetchChargesByName($name);

        return $charges;

    }
    
    
    protected function getDiscountByName($name){

        $discount = $this->serviceRepository->fetchDiscountByName($name);

        return $discount;

    }
    
    
    protected function getFeeByName($name){

        $fee = $this->serviceRepository->fetchFeeByName($name);

        return $fee;

    }
}