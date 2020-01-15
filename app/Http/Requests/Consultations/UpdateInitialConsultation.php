<?php

namespace App\Http\Requests\Consultations;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInitialConsultation extends FormRequest
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
            'complain' => 'required',
            'history_presenting_complaint' => '',
            'past_medical_history' => '',
            'drug_history' => '',
            'drug_allergies' => '',
            'family_history' => '',
            'social_history' => '',
            'examination' => '',
            'diagnosis' => '',
            'treatment' => '',

            'user_id' => 'required'
        ];
    }
}
