<?php

namespace App\Http\Requests;

use App\Interfaces\Repositories\ICardTypeRepository;
use App\Models\CardType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCardRequest extends FormRequest
{
    protected $cardTypeRepository;

    public function __construct(
        ICardTypeRepository $cardTypeRepository
    )
    {
        $this->cardTypeRepository = $cardTypeRepository;
    }


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // $model = $this->cardTypeRepository->getByUserId($this->user)->first();

        return $this->user()->can('create', CardType::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {

        $card_id = auth()->user()->verification->card->id;
    
        return [
            'card_id' => ['required', 'string', 'max:255', 'exists:card_types,id'],
            'card_number' => ['required', 'string', 'max_digits:255', 'unique:cards,number,'.$card_id],
            'front' => ['required', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
            'back' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'failed',
            'message' => 'Validation error!',
            'error' => $validator->errors(),
        ],
        422));
    }


    /* public function after(): array
    {
        return [
            function (Validator $validator) {

                $card_id = $this->card_id;
                $card = $this->cardTypeRepository->fetch($card_id)->first();
                
                if ($card->doc_no > 1) {
                    $validator->errors()->add(
                        'account',
                        'Account number ('. $account_no .') already taken by another user!'
                    );
                }
            }
        ];

    } */
}
