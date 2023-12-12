<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IUserRepository;
use App\Models\User;

class UserRepository implements IUserRepository{


    public function create($data){

        $obj = User::create($data);
        return $obj;
    }
    
    public function get(string $id){

        $user = User::with([
            'device', 
            'level', 
            'log', 
            'role', 
            'profile' =>[
                'address'
            ], 
            'manager', 
            'notification', 
            'services', 
            'verification' => [
                'bill',
                'bvn',
                'card'
            ], 
            'wallets' =>[
                'type'
            ], 
            'wallets.accounts.bank.country'
        ])->where('id', $id);

        return $user;
    }
    
    public function getAll(){

        return User::with([
            'device', 
            'level',
            'log', 
            'role', 
            'profile' =>[
                'address'
            ], 
            'manager', 
            'notification', 
            'services', 
            'verification' => [
                'bill',
                'bvn',
                'card'
            ], 
            'wallets' =>[
                'type'
            ], 
            'wallets.accounts.bank.country'
        ])->orderBy('created_at', 'DESC')->get();
    }

    public function getByEmail($email)
    {
        return User::with([
            'device', 
            'level',
            'log', 
            'role', 
            'profile' =>[
                'address'
            ], 
            'manager', 
            'notification', 
            'services', 
            'verification' => [
                'bill',
                'bvn',
                'card'
            ], 
            'wallets' =>[
                'type'
            ], 
            'wallets.accounts.bank.country'
        ])->where('email', $email);
    }

    public function store(User $obj)
    {

        $save = $obj->save();

        return $save;
    }
    
    public function update(User $obj, $data){
        
        $update = $obj->update($data);

        return $update;
    }
    
    public function delete(User $obj){

        $delete = $obj->delete();

        return $delete;
    }
    
}