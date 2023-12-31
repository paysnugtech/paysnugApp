<?php 

namespace App\Interfaces\Services;


interface IProfileService{

    public function storeProfile($request);
    public function storeProfilePicture($request);
    public function getAllProfile();
    public function getProfile(string $id);
    public function getProfileByUserId(string $user_id);
    public function updateProfile($request, $id);
    public function deleteProfile(string $id);
}

