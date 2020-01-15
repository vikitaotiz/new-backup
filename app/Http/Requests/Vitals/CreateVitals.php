<?php

namespace App\Http\Requests\Vitals;

use Illuminate\Foundation\Http\FormRequest;

class CreateVitals extends FormRequest
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
            'user_id' => 'required',
            'temperature' => '',
            'pulse_rate' => '',
            'BP' => '',
            'respiratory_rate' => '',
            'oxygen_saturation' => '',
            'o2_administered' => '',
            'head_circumference' => '',
            'height' => '',
            'weight' => ''
        ];
    }
}
