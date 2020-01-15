<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;

class CreateInvoices extends FormRequest
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
            'product_service' => 'required',
            'description' => 'required',
            // 'code_serial' => 'required',
            'quantity' => 'required',
            'charge_id' => 'required',
            'due_date' => '',
            'doctor_id' => '',
            'user_id' => 'required',
            'insurance_name' => ''
        ];
    }
}
