<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IUserRepository;
use App\Models\User;

class UserRepository implements IUserRepository{


    public function create($data){

        $user = User::create($data);
        return $user;
    }
    
    public function get(string $id){

        return User::with([
            'role', 
            'profile.address', 
            'manager', 
            'notification', 
            'service', 
            'verification' => [
                'bill',
                'bvn',
                'card'
            ], 
            'wallets.type', 
            'wallets.accounts.bank.country'
        ])->where('id', $id);
    }
    
    public function getAll(){

        return User::with(['role', 'profile.address', 'manager', 'notification', 'service', 'verification', 'wallets.type', 'wallets.accounts.bank.country'])->get();
    }

    public function getByEmail($email)
    {
        return User::with(['role', 'profile.address', 'manager', 'notification', 'service', 'verification', 'wallets.type', 'wallets.accounts.bank.country'])->where('email', $email);
    }
    
    public function update(User $user, $data){
        
        $user->update($data);

        return $user;
    }
    
    public function delete(User $user){

        return $user->delete();
    }
    
}