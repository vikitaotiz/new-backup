<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PatientsImport implements WithMultipleSheets
{
    use Importable;

    public function sheets(): array
    {
        return [
            0 => new UsersImport(),
            1 => new ContactsImport(),
            2 => new AppointmentsImport(),
            3 => new DocumentsImport(),
            4 => new TasksImport(),
            5 => new PrescriptionLettersImport(),
            6 => new ReferralLettersImport(),
            7 => new SickNoteLettersImport(),
            8 => new CertificateLettersImport(),
            9 => new InitialConsultationsImport(),
            10 => new FollowUpConsultationsImport(),
            11 => new VitalsImport(),
            12 => new InvoicesImport(),
        ];
    }
}
