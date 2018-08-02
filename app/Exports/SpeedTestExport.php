<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Collection;

class SpeedTestExport implements FromCollection
{
	protected $data;

	public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
    	$exportData = $this->data;
        return new Collection([
        	['Ping', 'Download Speed', 'Upload Speed'],
        	$exportData,
        ]);
    }
}
