<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendancePunchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $action = $this->input('action');

        $rules = [
            'action' => 'required|in:start,end,break_start,break_end',
            'status' => 'required|string',
        ];

        $statusRules = [
            'start' => '勤務外',
            'end' => '出勤中',
            'break_start' => '出勤中',
            'break_end' => '休憩中',
        ];

        if (isset($statusRules[$action])) {
            $rules['status'] .= "|in:{$statusRules[$action]}";
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'action.required' => 'アクションが不正です。',
            'action.in' => '指定できないアクションです。',
            'status.required' => 'ステータスが必要です。',
            'status.in' => 'ステータスの値が正しくありません。',
        ];
    }

}
