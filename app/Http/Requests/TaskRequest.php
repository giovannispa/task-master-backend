<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'description' => 'required',
            'created_by' => 'required',
            'assigned_to' => 'required',
            'status_id' => 'required',
            'project_id' => 'required',
            'priority_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ];
    }
}
