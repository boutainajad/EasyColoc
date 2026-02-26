<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
  
        public function authorize(): bool
            {
                return true;
            }


    public function rules(): array
    {
        return [
            'title'       => 'sometimes|string|max:255',
            'amount'      => 'sometimes|numeric|min:0',
            'date'        => 'sometimes|date',
            'category_id' => 'sometimes|exists:categories,id',
            'paid_by'     => 'sometimes|exists:users,id',
        ];
    }
        public function messages(): array
    {
        return [
            'title.max'       => 'Le titre ne doit pas depasser 255 caracteres.',
            'amount.numeric'  => 'Le montant doit etre un nombre.',
            'amount.min'      => 'Le montant doit etre positif.',
            'date.date'       => 'La date n\'est pas valide.',
        ];
    }

}
