<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PatientExport implements WithMultipleSheets
{
    use Exportable;

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
            'Patients' => new UsersExport(),
            'Contacts' => new ContactsExport(),
            'Appointments' => new AppointmentsExport(),
            'Documents' => new DocumentsExport(),
            'Tasks' => new TasksExport(),
            'Prescription Letters' => new PrescriptionLettersExport(),
            'Referral Letters' => new ReferralLettersExport(),
            'Sick Note Letters' => new SickNoteLettersExport(),
            'Certificate Letters' => new CertificateLettersExport(),
            'Initial Consultations' => new InitialConsultationsExport(),
            'Follow Up Consultations' => new FollowUpConsultationsExport(),
            'Vitals' => new VitalsExport(),
            'Invoices' => new InvoicesExport(),
        ];

        return $sheets;
    }
}
