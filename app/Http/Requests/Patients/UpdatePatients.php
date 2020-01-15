<?php

namespace App\Http\Requests\Patients;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatients extends FormRequest
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
            'username' => ['required', 'string', 'max:191' , \Illuminate\Validation\Rule::unique('users')->ignore($this->id)],
            'phone' => '',
            'email' => 'nullable',
            'gender' => '',
            'date_of_birth' => 'required',
            'nhs_number' => '',
            'emergency_contact' => '',
            'gp_details' => '',

            'occupation' => '',
            'height' => '',
            'weight' => '',

            'address' => '',
            'doctor_id' => '',
            'profile_photo' => 'sometimes|file|image|max:10000'
        ];
    }
}
