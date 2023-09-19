<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\ICountryRepository;
use App\Models\Country;

class CountryRepository implements ICountryRepository{


    public function create($data){

        return Country::create($data);
    }
    
    public function get(string $id){

        return Country::where('id', $id);
    }
    
    public function getAll(){

        return Country::all();
    }

    public function getByName(string $name)
    {
        return Country::where('name', $name);
    }

    public function getByStatus($status)
    {
        return Country::where('is_available', $status);
    }
    
    public function update(Country $country, $data){
        
        $country->update($data);

        return $country;
    }
    
    public function delete(Country $country){

        return $country->delete();
    }
    
}