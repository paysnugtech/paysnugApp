<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\ITokenRepository;
use App\Models\Token;
use Illuminate\Support\Facades\DB;

class TokenRepository implements ITokenRepository{


    public function create($data)
    {
        $token = DB::table('tokens')->insert($data);

        return $token;
    }
    
    public function fetch(string $id){

        return Token::with([])->where('id', $id);
    }

    
    public function fetchAll(){

        return Token::with([])->get();
    }
    
    public function fetchByEmail($email){

        return Token::with([])->where('email', $email);
    }
    
    public function fetchByEmailNumber($email, $number)
    {
        return Token::with([])
            ->where('email', $email)
            ->where('number', $number);
    }
    
    public function fetchByEmailNumberType($email, $number, $type)
    {
        return Token::with([])
            ->where('email', $email)
            ->where('number', $number)
            ->where('type', $type);
    }
    
    public function fetchByEmailType($email, $type)
    {
        return Token::with([])
            ->where('email', $email)
            ->where('type', $type);
    }
    
    public function fetchByType($type)
    {
        return Token::with([])->where('type', $type);
    }

    public function store(Token $token)
    {

        $save = $token->save();

        return $save;
    }

    public function update(Token $token, $data){
        
        $update = $token->update($data);

        return $update;
    }
    
    public function delete(Token $token){

        $delete = $token->delete();

        return $delete;
    }
    
}