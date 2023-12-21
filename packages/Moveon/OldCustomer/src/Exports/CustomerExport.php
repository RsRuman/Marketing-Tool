<?php

namespace Moveon\Customer\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromCollection, WithHeadings, ShouldQueue
{

    private mixed $customers;

    public function __construct($customers)
    {
        $this->customers = collect($customers);
    }

    /**
     * Returning collection of customer
     */
    public function collection()
    {
        return $this->customers;
    }

    /**
     * Define headings
     * @return string[]
     */
    public function headings(): array
    {
        return ["#", "Name", "Email", "Phone"];
    }
}
