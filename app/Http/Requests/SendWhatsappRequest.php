<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendWhatsappRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna memiliki otorisasi untuk membuat request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk pengiriman pesan WhatsApp.
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'max:20'],
            'message' => ['required_without:template_code', 'nullable', 'string', 'max:2000'],
            'template_code' => ['required_without:message', 'nullable', 'exists:whatsapp_templates,code'],
            'data' => ['nullable', 'array'],
            'user_id' => ['nullable', 'exists:users,id'],
        ];
    }

    /**
     * Pesan kustom validasi dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'phone.required' => 'Nomor WhatsApp tujuan wajib diisi.',
            'phone.string' => 'Nomor WhatsApp harus berupa teks/angka yang valid.',
            'phone.max' => 'Nomor WhatsApp tidak boleh lebih dari 20 karakter.',
            'message.required_without' => 'Isi pesan wajib diisi jika Anda tidak memilih template.',
            'message.string' => 'Isi pesan WhatsApp harus berupa teks.',
            'message.max' => 'Isi pesan WhatsApp maksimal 2000 karakter.',
            'template_code.required_without' => 'Pilih salah satu template atau tulis pesan manual.',
            'template_code.exists' => 'Template WhatsApp yang dipilih tidak ditemukan dalam database.',
            'data.array' => 'Data variabel template harus berupa format array / objek.',
            'user_id.exists' => 'Pengguna yang dipilih tidak terdaftar di sistem.',
        ];
    }
}
