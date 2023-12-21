<?php

namespace Moveon\Customer\Imports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Moveon\Customer\Models\Customer;

class CustomerImport implements ToCollection, WithChunkReading, WithHeadingRow, ShouldQueue
{
    /**
     * @param Collection $collections
     * @throws ValidationException
     */
    public function collection(Collection $collections): void
    {
        $validator = Validator::make($collections->toArray(), [
            '*.name'  => 'required',
            '*.email' => 'required|email',
            '*.phone' => 'required',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        foreach ($collections as $collection) {
            Customer::create([
                'first_name'    => $collection['name'],
                'email'         => $collection['email'],
                'phone'         => $collection['phone'],
                'customer_type' => 'imported',
            ]);
        }
    }

    public function chunkSize(): int
    {
        return 300;
    }
}
