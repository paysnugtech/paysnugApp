<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IPasswordResetRepository;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\DB;

class PasswordResetRepository implements IPasswordResetRepository{


    public function create($data){
        
        $create = DB::table('password_reset_tokens')->insert($data);

        return $create;
    }
    
    public function getAll()
    {

        // return User::with(['role', 'manager'])->get();
    }

    public function getByEmail($email)
    {
        return PasswordReset::where('email', $email);
    }

    public function getByEmailAndToken($data)
    {
        return PasswordReset::where('email', $data['email'])
            ->where('token', $data['token']);
    }

    public function store($data)
    {
        $obj = $data->save();

        return $obj;
    }
    
    public function update($data)
    {
        $update = PasswordReset::where('email', $data['email'])->update($data);

        return $update;
    }
    
    public function delete($obj)
    {
        return $obj->delete();
    }
    
}