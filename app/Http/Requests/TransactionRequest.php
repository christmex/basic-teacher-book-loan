<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // $request = json_decode(request('book_id'), true);
        // $find = request('book_id') ? dd($request) : 1;
        // $find = request('book_id') ? \App\Models\Book::whereIn('id', $request)->get() : 1;
        // $find = request('book_id') ? dd(\App\Models\Book::whereIn('book_id',request('book_id'))->book_stock) : 1;
        $find = request('book_id') ? \App\Models\Book::find(request('book_id'))->book_stock : 1;
        // dd(request('loaned_at'));
        return [
            'school_year_id' => 'required',
            'semester_id' => 'required',
            // 'member_id' => 'required',
            'book_id' => 'required',
            // Cek apakah guru sudah meminjam di tahun ajaran dan semester ini
            'member_id' => [
                'required',
                Rule::unique('transactions')->where(fn ($query) => $query->where('school_year_id', request('school_year_id'))->where('semester_id', request('semester_id'))->where('book_id', request('book_id'))->where('returned_at', NULL))->ignore(request()->id)
            ],
            'qty' => 'required|integer|min:1|max:'.$find,
            'loaned_at' => 'required',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'member_id.unique' => 'Guru sudah meminjam buku ini di tahun ajaran dan semester yang dipilih, silahkan cek data transaksi'
        ];
    }
}
