<?php

namespace Moveon\Setting\Imports;

use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Moveon\Setting\Models\Lead;

class LeadImport implements ToCollection, WithChunkReading, WithHeadingRow, ShouldQueue
{
    private $originId;

    public function __construct()
    {
        $this->originId = Auth::user()->origin->id;
    }

    /**
     * @param Collection $collections
     * @throws ValidationException
     */
    public function collection(Collection $collections): void
    {
        $validator = Validator::make($collections->toArray(), [
            '*.customer_id' => 'required',
            '*.first_name'  => 'nullable',
            '*.last_name'   => 'nullable',
            '*.name'        => 'required',
            '*.gender'      => 'required',
            '*.email'       => 'required|email',
            '*.phone'       => 'required',
            '*.post_code'   => 'required',
            '*.created_at'  => 'required|date',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        foreach ($collections as $collection) {
            Log::info($collection['created_at']);
            Lead::create([
                'user_id'          => $this->originId,
                'customer_id'      => $collection['customer_id'],
                'first_name'       => $collection['first_name'],
                'last_name'        => $collection['last_name'],
                'name'             => $collection['name'],
                'gender'           => $collection['gender'],
                'email'            => $collection['email'],
                'phone'            => $collection['phone'],
                'post_code'        => $collection['post_code'],
                'event_created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $collection['created_at']),
            ]);
        }
    }

    public function chunkSize(): int
    {
        return 300;
    }
}
