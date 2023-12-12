<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IDataRepository;
use App\Models\Data;
use App\Traits\EncryptionTrait;
use App\Traits\StatusTrait;
use App\Traits\WhereHouseTrait;

class DataRepository implements IDataRepository{

    use EncryptionTrait, 
        StatusTrait,
        WhereHouseTrait;

    protected $data_commission;
    protected $data_list_url;
    protected $data_package_url;
    protected $x_secret_key;


    public function __construct()
    {
        $this->data_commission = env('DATA_COMMISSION');
        $this->data_list_url = env('DATA_LIST_ENDPOINT');
        $this->data_package_url = env('DATA_PACKAGE_ENDPOINT');
        $this->x_secret_key = env('PSG_SECRET_KEY');
    }


    public function create($data){

        /* $obj = Airtime::create($data);
        return $obj; */
    }
    
    public function fetch(string $id){

        return Data::with(['user'])->where('id', $id);

    }
    
    public function fetchAll(){

        return Data::with(['user'])->get();
    }

    public function fetchByName($name)
    {
        
        /* $objs = Airtime::with([])->where('name', $name)->get();

        return $objs; */
    }


    public function fetchPackages($data)
    {

        $apiData = [
            'data' => json_encode([
                "_id" => $data->_id,
                "name" => $data->name,
                "code" => $data->code,
                "provider_id" => $data->provider_id,
                "others" => $data->others
            ]),
            'header' => [
                'Accept: application/json',
                'Content-Type: application/json',
                'X-Secret-Key: '. $this->x_secret_key,
            ],
            'url' => $this->data_package_url,
        ];


        $response = $this->curlPostApi($apiData);

        return $response;
    }



    public function fetchProvider(string $id)
    {
        
        $response = $this->fetchProviders();
        
        if(empty($response) )
        {
            return NULL;
        }


        $providers = collect($response);

        

        $provider = $providers->where('warehouse_id', $id);

        return $provider;
    }

    public function fetchProviders()
    { 
        
        $requestData = [
            'header' => [
                'Accept: application/json',
                'Content-Type: application/json',
                'X-Secret-Key: '. $this->x_secret_key,
            ],
            'url' => $this->data_list_url
        ];

        $response = $this->curlGetApi($requestData);
        // $response = $this->httpClientGetApi($requestData);


        return $response;
    }

    public function store(Data $data){

        return $data->save();

    }



    
    public function update(Data $obj, $data){
        
        $obj->update($data);

        return $obj;
    }
    
    public function delete(Data $data){

        return $data->delete();
    }
    
}