<?php

namespace Moveon\Setting\Services;

use Maatwebsite\Excel\Facades\Excel;
use Moveon\Setting\Imports\EventImport;
use Moveon\Setting\Imports\LeadImport;
use Moveon\Setting\Repositories\ImportRepository;

class ImportService
{
    private ImportRepository $importRepository;

    public function __construct(ImportRepository $importRepository)
    {
        $this->importRepository = $importRepository;
    }

    /**
     * Import private platform data
     * @param $request
     * @return void
     */
    public function importPrivatePlatformData($request): void
    {
        $type = $request->input('type');

        if ($type === 'customers') {
            Excel::import(new LeadImport(), $request->file('file'), 'public', 'Xlsx');
        } else {
            Excel::import(new EventImport($type), $request->file('file'), 'public', 'Xlsx');
        }

    }
}
