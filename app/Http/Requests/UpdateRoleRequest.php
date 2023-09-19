<?php

namespace App\Http\Requests;

use App\Interfaces\Repositories\IRoleRepository;
use App\Models\V1\Role;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{

    protected $roleRepository;
    protected $id;

    public function __construct(Request $request, IRoleRepository $roleRepository)
    {
        $this->id = $request->route()->role;

        $this->roleRepository = $roleRepository;
    }


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', Rule::unique('roles', 'name')->ignore($this->id), 'string', 'max:25'],
            'description' => 'required|string|max:255'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function after(): array
    {

        return [
            function (Validator $validator) {

                $role = $this->roleRepository->get($this->id);

                if (!$role) {
                    $validator->errors()->add(
                        'id',
                        'Role not found!'
                    );
                }
            }
        ];
    }


    public function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(response()->json([

            'status'   => 'failed',
            'message'   => 'Validation errors',
            'error'      => $validator->errors()

        ], 422));

    }
}
