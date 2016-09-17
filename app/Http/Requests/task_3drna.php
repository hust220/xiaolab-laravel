<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class task_3drna extends Request
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
            'seq'=>'required',
            'ss'=>'required'
            //
        ];
    }

    /*
    public function messages()
    {
        return [
            'seq.required' => 'A sequence is required',
            'ss.required'  => 'A 2D structure is required'
        ];
    }
    */
}

