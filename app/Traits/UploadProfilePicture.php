<?php 

namespace App\Traits;
use Illuminate\Support\Facades\File;

trait UploadProfilePicture{
    
    protected function UploadProfilePicture($request, $path = 'upload/images/profile')
    {

        /* if (File::exists(public_path($path))) {

            // $image_path = $request->file('image')->store($path, 'public');
            $path = $request->file('profile_image')->storeAs(
                $path, $request->user()->id
            );

            return $path;
        
        }*/
        
        if (!File::exists(public_path($path))) {

            File::makeDirectory($path);
        
        }
        
        

        $path = $request->file('profile_image')->storeAs(
            $path, $request->user()->id
        );

        return $path;
    }
}