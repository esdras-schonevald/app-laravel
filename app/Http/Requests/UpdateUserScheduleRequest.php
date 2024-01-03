<?php

namespace App\Http\Requests;

use App\Http\Services\DateHelper;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    protected function prepareForValidation()
    {
        $this->merge([
            'start_date' => DateHelper::brToDb($this->start_date),
            'deadline_date' => DateHelper::brToDb($this->deadline_date),
            'end_date' => DateHelper::brToDb($this->end_date)
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $notCoincidRule =   sprintf('not_coincide:%s,%s,%s', $this->route('userId'), $this->input('start_date'), $this->input('end_date'));

        return [
            'title' => ['nullable', 'min:3', 'max:50'],
            'description' => ['nullable', 'max:255'],
            'start_date' => [
                'nullable',
                'date',
                'not_weekend',
                $notCoincidRule
            ],
            'deadline_date' => [
                'nullable',
                'date',
                'not_weekend',
                $notCoincidRule
            ],
            'end_date' => [
                'nullable',
                'date',
                'not_weekend',
                $notCoincidRule
            ],
            'status' => ['nullable'],
        ];
    }
}
