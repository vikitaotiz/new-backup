<?php

namespace App\Http\Requests\Drugs;

use Illuminate\Foundation\Http\FormRequest;

class CreateDrugs extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dosage' => 'required',
            'duration_quantity' => 'required',
            // 'dispensed_by' => 'required',
            'user_id' => 'required',
            'medication_id' => 'required'
        ];
    }
}
