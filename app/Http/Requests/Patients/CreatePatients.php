<?php

namespace App\Http\Requests\Patients;

use Illuminate\Foundation\Http\FormRequest;

class CreatePatients extends FormRequest
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
            'title' => '',
            'firstname' => 'required',
            'lastname' => 'required',
            'phone' => '',
            'email' => 'nullable|string|email|max:255',
            'gender' => '',
            'date_of_birth' => 'required',
            'nhs_number' => '',
            'emergency_contact' => '',
            'gp_details' => '',
            'occupation' => '',
            'height' => '',
            'weight' => '',
            'address' => '',
            'privacy_policy' => 'nullable|integer',
            'communication_preference' => 'nullable|integer',
            'doctor_id' => '',
            'profile_photo' => 'sometimes|file|image|max:10000',
        ];
    }
}
