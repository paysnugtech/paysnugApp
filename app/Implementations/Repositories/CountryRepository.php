<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\ICountryRepository;
use App\Models\Country;

class CountryRepository implements ICountryRepository{


    public function create($data)
    {
        return Country::create($data);
    }
    
    public function get(string $id)
    {

        $obj = Country::where('id', $id);

        return $obj;
    }
    
    public function getAll()
    {
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
    
    public function store(Country $country)
    {
        $obj = $country->save();

        return $obj;
    }
    
    public function update(Country $obj, $data)
    {
        $obj->update($data);

        return $obj;
    }
    
    public function delete(Country $obj)
    {
        return $obj->delete();
    }
    
}