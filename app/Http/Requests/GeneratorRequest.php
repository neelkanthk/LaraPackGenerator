<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class GeneratorRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {

        return [
            'namespace' => 'required|min:2',
            'name' => 'required|min:2',
            //'controllers' => 'required',
            //'models' => 'required',
            //'middlewares' => 'required',
            //'requests' => 'required',
            //'events' => 'required'
        ];
    }

    public function messages() {
        return [
//            'namespace.required' => 'I cannot generate a package without a name.',
//            'namespace.min' => 'Would you please put 2 or more characters',
//            'name.required' => 'I cannot generate a package without a name.',
//            'name.min' => 'Would you please put 2 or more characters',
        ];
    }

}
