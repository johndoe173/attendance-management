<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StampCorrectionRequest extends FormRequest
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
            'requested_start_time' => 'required|date_format:H:i',
            'requested_end_time' => 'required|date_format:H:i|after:requested_start_time',
            'requested_note' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'requested_start_time.required' => '出勤時間を入力してください',
            'requested_end_time.required' => '退勤時間を入力してください',
            'requested_end_time.after' => '出勤時間もしくは退勤時間が不適切な値です',
            'requested_note.required' => '備考を記入してください',
        ];
    }

}
