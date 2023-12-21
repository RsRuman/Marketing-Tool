<?php

namespace Moveon\Customer\Services;

use Maatwebsite\Excel\Facades\Excel;
use Moveon\Customer\Exports\CustomerExport;
use Moveon\Customer\Imports\CustomerImport;
use Moveon\Customer\Models\Customer;

class ExportService
{
    /**
     * Export customers
     * @return string
     */
    public function exportCustomers(): string
    {
        $filename = 'customers-' . now()->format('Y-m-d-H-i-s') . '.xlsx';

        $customers = Customer::query()->select('first_name', 'email', 'phone')->cursor();

        Excel::store(new CustomerExport($customers), 'exports/' . $filename, 'public', 'Xlsx');

        return $filename;
    }

    /**
     * Import customers
     * @param $file
     * @return void
     */
    public function importCustomers($file): void
    {
        Excel::import(new CustomerImport(), $file, 'exports', 'Xlsx');
    }
}
