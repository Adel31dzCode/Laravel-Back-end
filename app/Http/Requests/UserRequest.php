<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:6|max:16',
            'email' => 'required|email|min:4|max:28|unique:users,email',
            'password' => 'required|string|min:8|max:18|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name Is Required',
            'name.string' => 'Name Must Be String',
            'name.min' => 'Name Max Character Is 6',
            'name.max' => 'Name Max Character Is 16',

            'email.required' => 'Email Is Required',
            'email.email' => 'Email Must Be Valid',
            'email.min' => 'Email Min Character Is 4',
            'email.max' => 'Email Max Character Is 28',
            'email.unique' => 'Email Is Duplicated',

            'password.required' => 'Password Is Required',
            'password.string' => 'Password Must Be Valid',
            'password.min' => 'Password Min Character Is 8',
            'password.max' => 'Password Max Character Is 16',
            'password.confirmed' => 'Password Confirmation Does Not Match',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($validator->errors()->has('email')) {
            throw new HttpResponseException(response()->json([
                'message' => 'Email Is Duplicated'
            ], 401));
        }

        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}
