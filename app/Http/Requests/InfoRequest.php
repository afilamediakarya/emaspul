<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama_documents' => 'required',
            'nomor_perbub' => 'required',
            'tanggal_perbub' => 'required',
            'file' => 'required|mimes:pdf|max:30000',
            'periode_awal' => 'required',
            'periode_akhir' => 'required',
        ];
    }
}
