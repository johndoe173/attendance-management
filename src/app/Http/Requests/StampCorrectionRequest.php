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
            'requested_start_time.date_format' => '出勤時間は HH:MM 形式で入力してください',
            'requested_end_time.required' => '退勤時間を入力してください',
            'requested_end_time.date_format' => '退勤時間は HH:MM 形式で入力してください',
            'requested_end_time.after' => '出勤時間より後の時刻を指定してください',
            'requested_note.required' => '備考を記入してください',
            'requested_note.string' => '備考には文字列を入力してください',
        ];
    }
}
