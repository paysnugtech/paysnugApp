<?php 


namespace App\Implementations\Services;

use App\Http\Resources\ManagersResource;
use App\Http\Resources\UsersResource;
use App\Interfaces\Repositories\IPasswordResetRepository;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Services\IPasswordService;
use App\Models\User;
use App\Traits\ErrorResponse;
use App\Traits\SuccessResponse;
use Illuminate\Support\Facades\Auth;


class PasswordService implements IPasswordService
{
    use ErrorResponse, SuccessResponse;


    protected $passwordResetRepository;
    protected $userRepository;

    public function __construct(IPasswordResetRepository $passwordResetRepository, IUserRepository $userRepository){

        $this->passwordResetRepository = $passwordResetRepository;
        $this->userRepository = $userRepository;
    }
    
    
    public function storeUser($data)
    {

    }
    
    public function getUser(string $id)
    {

    }
    
    public function getAllUser()
    {

    }
    
    public function processForgotPassword($request)
    {
        $validated = $request->validated();
        
        // $token = Str::random(64);
        $token = random_int(101010, 999999);
        $created_at = now();

        $obj = $this->passwordResetRepository->getByEmail($validated['email']);
        
        if($obj->exists())
        {
            // print_r($obj->first());

            // update into the database
            $this->passwordResetRepository->delete($obj->first());

            $validated['token'] = $token;
            $validated['created_at'] = $created_at;

            // print_r($validated);

            // update into the database
            $this->passwordResetRepository->create($validated);

            $data = [
                'email' => $validated['email']
            ];
            
            return $this->successResponse($data, 
                "New Password Reset token sent successful"
            );
        }


        $validated['token'] = $token;
        $validated['created_at'] = $created_at;


        // Insert into the database
        $create = $this->passwordResetRepository->create($validated);


        if(!$create)
        {
            return $this->errorResponse([], "Something went wrong, Try again");
        }


        //Send token email
        

        $data = [
            'email' => $validated['email']
        ];

        return $this->successResponse($data, 
            "Password Reset token sent successful"
        );

    }
    
    public function resetPassword($validateData)
    {

        $data = ['password' => bcrypt($validateData['password'])];

        $user = $this->userRepository->getByEmail($validateData['email'])->firstOrFail();

        $updated = $this->userRepository->update($user, $data);

        // Delete Token
        $obj = $this->passwordResetRepository->getByEmailAndToken($validateData);

        $this->passwordResetRepository->delete($obj);

        return $this->successResponse([], 'Reset Successful');
    }
    
    public function updatePassword($request, string $id)
    {
        $validated = $request->validated();

        $user_id = Auth::id();

        $user = $this->userRepository->get($user_id)->firstOrFail();
        
        $user->password = bcrypt($validated['password']);
        
        $user->save();

        return $this->successResponse([], 'Password Changed Successful');
    } 
}