<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendancePunchRequest extends FormRequest
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
        return match (request()->input('action')) {
            'start' => ['status' => 'in:勤務外'],
            'end' => ['status' => 'in:出勤中'],
            'break_start' => ['status' => 'in:出勤中'],
            'break_end' => ['status' => 'in:休憩中'],
            default => [],
        };
    }

    public function messages()
    {
        return [
            'status.in' => '現在のステータスではこの操作は行えません',
        ];
    }
}
