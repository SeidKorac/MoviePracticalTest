<?php

namespace App\Http\Requests;

class CreateMovieRequest extends ApiFormRequest
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
            'title' => 'required|string|unique:movies',
            'synopsis' => 'string',
            'director' => 'string',
            'duration' => 'numeric',
            'releaseDate' => 'date',
            'movieGenres' => 'array',
        ];
    }

}