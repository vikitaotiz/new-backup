<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointment extends FormRequest
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
            'description' => 'nullable|string',
            'appointment_date' => 'required|date_format:Y-m-d',
            'from' => 'required|date_format:h:iA',
            'service_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'user_id' => 'required|integer',
        ];
    }
}
