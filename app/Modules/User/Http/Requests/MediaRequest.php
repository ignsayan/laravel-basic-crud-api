<?php

namespace App\Modules\User\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class MediaRequest extends FormRequest
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
        $allowedAvatarMimeTypes = implode(',', User::SUPPORTED_IMAGE_MIME_TYPES);
        return [
            'key' => ['required', 'string', 'max:50'],
            'file' => ['required', 'file', 'mimetypes:' . $allowedAvatarMimeTypes]
        ];
    }
}
