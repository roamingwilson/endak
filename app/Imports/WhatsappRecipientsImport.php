<?php

namespace App\Imports;

use App\Models\WhatsappRecipients;
use Maatwebsite\Excel\Concerns\ToModel;

class WhatsappRecipientsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // تخطى السطر الأول إذا كان رؤوس أعمدة
        if ($row[0] == 'number' || $row[0] == 'رقم' || empty($row[0])) {
            return null;
        }
        return new WhatsappRecipients([
            'number' => $row[0],
            'department_id' => $row[1],
        ]);
    }
}
