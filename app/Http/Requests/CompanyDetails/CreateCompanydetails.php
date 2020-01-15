<?php

namespace App\Http\Requests\CompanyDetails;

use Illuminate\Foundation\Http\FormRequest;

class CreateCompanydetails extends FormRequest
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
            'name' => 'required',
            'address' => 'required',
            'email' => 'required',
            'phone' => '',
            'industry' => '',
            'status' => 'required',
            'more_info' => '',
            'logo' => 'sometimes|file|image|max:10000'
        ];
    }
}
