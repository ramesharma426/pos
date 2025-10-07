<?php

namespace App\Traits;
use App\Models\Payment;
use SplFileInfo;
trait Helpers
{
    protected function isValidFile($file): bool
    {
        return $file instanceof SplFileInfo && $file->getPath() !== '';
    }

    public function arrayHasFile($files): bool
    {
        foreach ((array) $files as $file) {
            if ($this->isValidFile($file)) {
                return true;
            }
        }

        return false;
    }

    public function billNumberAG(): string
    {
        $count = Payment::count();
        if($count == 0){
            return 'A00000';
        } elseif ($count > 0) {
            return strtoupper(dechex(hexdec(Payment::select('bill_number')->latest('id')->pluck('bill_number')[0]) + 1));
        } else {
            return strtoupper(dechex(hexdec(Payment::select('bill_number')->latest('id')->pluck('bill_number')[0]) + 1));
        }
    }
}
