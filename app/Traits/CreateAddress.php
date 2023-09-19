<?php 

namespace App\Traits;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

trait CreateAddress{
    
    protected function CreateAddress($user, $request)
    {

        $validated = $request->validated();

        $newAddress = $this->user->address()->create($validated);

        return $newAddress;
    }
}