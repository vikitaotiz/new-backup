<?php

namespace App\Http\Controllers;

use App\File;
use App\Task;
use App\User;
use App\Vital;
use App\Contact;
use App\Invoice;
use App\Referral;
use App\Sicknote;
use App\Appointment;
use App\Certificate;
use App\Prescription;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\FollowupConsultation;
use App\InitialConsultation;
use App\Exports\PatientExport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ExcelController extends Controller
{
    /**
     * Export excel file.
     *
     * @param  Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        return (new PatientExport())->download('patients.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('settings.import');
    }

    /**
     * Import excel file.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(Request  $request)
    {
        // Validate form data
        $request->validate([
            'patient_excel' => 'required|mimes:csv,xlsx,xls',
        ]);

        // Checks if the file exists
        if ($request->hasFile('patient_excel')){
            // Get file name with extension
            $fileNameWithExt = $request->file('patient_excel')->getClientOriginalName();
            // Get only file name
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // Get only extension
            $extension = $request->file('patient_excel')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $fileName . Str::random(20) . "." . $extension;
            // Directory to upload
            $request->file('patient_excel')->storeAs('public/excel/', $fileNameToStore);
        }

        return response()->json([asset('/storage/public/excel/'.$fileNameToStore), $fileNameToStore]);
    }

    /**
     * Import excel file.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importStore(Request  $request)
    {
        // Validate form data
        $request->validate([
            'data' => 'required',
            'fileName' => 'required|string',
        ]);

        $data = json_decode($request->data, true);

        foreach ($data['UsersImport'] as $user) {
            if (!isset($user['Id']) && User::withTrashed()->where('email', $user['Email'])->first() == null) {
                User::create([
                    'title'     => isset($user['Title']) ? $user['Title'] : null,
                    'firstname'    => isset($user['First Name']) ? $user['First Name'] : null,
                    'lastname'    => isset($user['Last Name']) ? $user['Last Name'] : null,
                    'nhs_number'    => isset($user['NHS Number']) ? $user['NHS Number'] : null,
                    'date_of_birth'    => isset($user['Date of Birth']) ? $user['Date of Birth'] : null,
                    'phone'    => isset($user['Phone']) ? $user['Phone'] : null,
                    'emergency_contact'    => isset($user['Emergency contacts']) ? $user['Emergency contacts'] : null,
                    'email'    => isset($user['Email']) ? $user['Email'] : null,
                    'gender'    => isset($user['Gender']) ? $user['Gender'] : null,
                    'height'    => isset($user['Height']) ? $user['Height'] : null,
                    'weight'    => isset($user['Weight']) ? $user['Weight'] : null,
                    'blood_type'    => isset($user['Blood type']) ? $user['Blood type'] : null,
                    'address'    => isset($user['Address']) ? $user['Address'] : null,
                    'availability'    => isset($user['Availability']) ? $user['Availability'] : null,
                    'occupation'    => isset($user['Occupation']) ? $user['Occupation'] : null,
                    'referral_source'    => isset($user['Referral Source']) ? $user['Referral Source'] : null,
                    'gp_details'    => isset($user['General Practioner (GP) Details']) ? $user['General Practioner (GP) Details'] : null,
                    'medication_allergies'    => isset($user['Medication Allergies']) ? $user['Medication Allergies'] : null,
                    'current_medication'    => isset($user['Current Medication']) ? $user['Current Medication'] : null,
                    'deleted_at'    => isset($user['Deleted At']) ? $user['Deleted At'] : null,
                    'more_info'    => isset($user['More Information']) ? $user['More Information'] : null,
                    'gmc_no'    => isset($user['Gmc No']) ? $user['Gmc No'] : null,
                    'password'    => Hash::make('12345678'),
                    'user_id'    => isset($user['User Id']) ? $user['User Id'] : null,
                    'creator_id'    => auth()->id(),
                ]);
            }
        }

        foreach ($data['ContactsImport'] as $contact) {
            Contact::create([
                'nhs_number'     => isset($contact['NHS Number']) ? $contact['NHS Number'] : null,
                'relative_name'    => isset($contact['Name']) ? $contact['Name'] : null,
                'relationship_type'    => isset($contact['Relationship Type']) ? $contact['Relationship Type'] : null,
                'date_of_birth'    => isset($contact['Date of Birth']) ? $contact['Date of Birth'] : null,
                'phone'    => isset($contact['Phone']) ? $contact['Phone'] : null,
                'email'    => isset($contact['Email']) ? $contact['Email'] : null,
                'medical_history'    => isset($contact['Medical History']) ? $contact['Medical History'] : null,
                'more_info'    => isset($contact['More Info']) ? $contact['More Info'] : null,
                'user_id'    => isset($contact['User Id']) ? $contact['User Id'] : User::withTrashed()->where('email', $contact['Patient Email'])->value('id'),
                'creator_id'    => auth()->id(),
            ]);
        }

        foreach ($data['AppointmentsImport'] as $appointment) {
            Appointment::create([
                'description'     => isset($appointment['Description']) ? $appointment['Description'] : null,
                'appointment_date'    => isset($appointment['Appointment Date']) ? $appointment['Appointment Date'] : null,
                'end_time'    => isset($appointment['End Time']) ? $appointment['End Time'] : null,
                'status'    => isset($appointment['Status']) ? $appointment['Status'] : null,
                'progress'    => isset($appointment['Progress']) ? $appointment['Progress'] : null,
                'color'    => isset($appointment['Color']) ? $appointment['Color'] : null,
                'service_id'    => isset($appointment['Service Id']) ? $appointment['Service Id'] : null,
                'doctor_id'    => isset($appointment['Doctor Id']) ? $appointment['Doctor Id'] : 4,
                'user_id'    => isset($appointment['User Id']) ? $appointment['User Id'] : User::withTrashed()->where('email', $appointment['Patient Email'])->value('id'),
                'creator_id'    => auth()->id(),
            ]);
        }

        foreach ($data['DocumentsImport'] as $document) {
            File::create([
                'name'     => isset($document['File']) ? $document['File'] : null,
                'filename'    => isset($document['File Name']) ? $document['File Name'] : null,
                'user_id'    => isset($document['User Id']) ? $document['User Id'] : User::withTrashed()->where('email', $document['Patient Email'])->value('id'),
                'creator_id'    => auth()->id(),
            ]);
        }

        foreach ($data['TasksImport'] as $task) {
            Task::create([
                'name'     => isset($task['Name']) ? $task['Name'] : null,
                'description'    => isset($task['Description']) ? $task['Description'] : null,
                'deadline'    => isset($task['Deadline']) ? $task['Deadline'] : null,
                'status'    => isset($task['Status']) ? $task['Status'] : null,
                'doctor_id'    => isset($task['Doctor Id']) ? $task['Doctor Id'] : null,
                'user_id'    => isset($task['User Id']) ? $task['User Id'] : User::withTrashed()->where('email', $task['Patient Email'])->value('id'),
                'creator_id'    => auth()->id(),
            ]);
        }

        foreach ($data['PrescriptionLettersImport'] as $prescriptionLetters) {
            Prescription::create([
                'drug_allergies'     => isset($prescriptionLetters['Drug Allergies']) ? $prescriptionLetters['Drug Allergies'] : null,
                'comments'    => isset($prescriptionLetters['Comments']) ? $prescriptionLetters['Comments'] : null,
                'signature'    => isset($prescriptionLetters['Signature']) ? $prescriptionLetters['Signature'] : null,
                'company_id'    => isset($prescriptionLetters['Company Id']) ? $prescriptionLetters['Company Id'] : null,
                'user_id'    => isset($prescriptionLetters['User Id']) ? $prescriptionLetters['User Id'] : User::withTrashed()->where('email', $prescriptionLetters['Patient Email'])->value('id'),
                'creator_id'    => auth()->id(),
            ]);
        }

        foreach ($data['ReferralLettersImport'] as $referralLetters) {
            Referral::create([
                'name'     => isset($referralLetters['Referral Letter Name']) ? $referralLetters['Referral Letter Name'] : null,
                'body'    => isset($referralLetters['Body']) ? $referralLetters['Body'] : null,
                'company_id'    => isset($referralLetters['Company Id']) ? $referralLetters['Company Id'] : null,
                'user_id'    => isset($referralLetters['User Id']) ? $referralLetters['User Id'] : User::withTrashed()->where('email', $referralLetters['Patient Email'])->value('id'),
                'creator_id'    => auth()->id(),
            ]);
        }

        foreach ($data['InitialConsultationsImport'] as $initialConsultations) {
            InitialConsultation::create([
                'complain'     => isset($initialConsultations['Presenting Complaint']) ? $initialConsultations['Presenting Complaint'] : null,
                'history_presenting_complaint'    =>isset( $initialConsultations['History Presenting Complaint']) ? $initialConsultations['History Presenting Complaint'] : null,
                'past_medical_history'    => isset($initialConsultations['Past Medical History']) ? $initialConsultations['Past Medical History'] : null,
                'drug_history'    => isset($initialConsultations['Drug history']) ? $initialConsultations['Drug history'] : null,
                'social_history'    => isset($initialConsultations['Social History']) ? $initialConsultations['Social History'] : null,
                'family_history'    => isset($initialConsultations['Family History']) ? $initialConsultations['Family History'] : null,
                'drug_allergies'    => isset($initialConsultations['Drug Allergies']) ? $initialConsultations['Drug Allergies'] : null,
                'examination'    => isset($initialConsultations['Examination']) ? $initialConsultations['Examination'] : null,
                'diagnosis'    => isset($initialConsultations['Diagnosis']) ? $initialConsultations['Diagnosis'] : null,
                'treatment'    => isset($initialConsultations['Treatment']) ? $initialConsultations['Treatment'] : null,
                'company_id'    => isset($initialConsultations['Company Id']) ? $initialConsultations['Company Id'] : null,
                'user_id'    => isset($initialConsultations['User Id']) ? $initialConsultations['User Id'] : User::withTrashed()->where('email', $initialConsultations['Patient Email'])->value('id'),
                'creator_id'    => auth()->id(),
            ]);
        }

        foreach ($data['SickNoteLettersImport'] as $SickNoteLetter) {
            Sicknote::create([
                'name'     => isset($SickNoteLetter['Sick Note Letter Name']) ? $SickNoteLetter['Sick Note Letter Name'] : null,
                'body'    => isset($SickNoteLetter['Body']) ? $SickNoteLetter['Body'] : null,
                'company_id'    => isset($SickNoteLetter['Company Id']) ? $SickNoteLetter['Company Id'] : null,
                'user_id'    => isset($SickNoteLetter['User Id']) ? $SickNoteLetter['User Id'] : User::withTrashed()->where('email', $SickNoteLetter['Patient Email'])->value('id'),
                'creator_id'    => auth()->id(),
            ]);
        }

        foreach ($data['VitalsImport'] as $vital) {
            Vital::create([
                'temperature'     => isset($vital['Temp']) ? $vital['Temp'] : null,
                'pulse_rate'    => isset($vital['Pulse Rate']) ? $vital['Pulse Rate'] : null,
                'pain'    => isset($vital['Pain']) ? $vital['Pain'] : null,
                'height'    => isset($vital['Height']) ? $vital['Height'] : null,
                'weight'    => isset($vital['Weight']) ? $vital['Weight'] : null,
                'systolic_bp'    => isset($vital['Systolic Bp']) ? $vital['Systolic Bp'] : null,
                'diastolic_bp'    => isset($vital['Diastolic Bp']) ? $vital['Diastolic Bp'] : null,
                'respiratory_rate'    => isset($vital['Respiratory Rate']) ? $vital['Respiratory Rate'] : null,
                'oxygen_saturation'    => isset($vital['Oxygen Saturation']) ? $vital['Oxygen Saturation'] : null,
                'o2_administered'    => isset($vital['O2 Administered']) ? $vital['O2 Administered'] : null,
                'head_circumference'    => isset($vital['Head Circumference']) ? $vital['Head Circumference'] : null,
                'company_id'    => isset($vital['Company Id']) ? $vital['Company Id'] : null,
                'user_id'    => isset($vital['User Id']) ? $vital['User Id'] : User::withTrashed()->where('email', $vital['Patient Email'])->value('id'),
                'creator_id'    => auth()->id(),
            ]);
        }

        foreach ($data['CertificateLettersImport'] as $certificate) {
            Certificate::create([
                'name'     => isset($certificate['Certificate Letter Name']) ? $certificate['Certificate Letter Name'] : null,
                'body'    => isset($certificate['Body']) ? $certificate['Body'] : null,
                'company_id'    => isset($certificate['Company Id']) ? $certificate['Company Id'] : null,
                'user_id'    => isset($certificate['User Id']) ? $certificate['User Id'] : User::withTrashed()->where('email', $certificate['Patient Email'])->value('id'),
                'creator_id'    => auth()->id(),
            ]);
        }

        foreach ($data['FollowUpConsultationsImport'] as $followUpConsultation) {
            FollowupConsultation::create([
                'patient_progress'     => isset($followUpConsultation['Patient Progress']) ? $followUpConsultation['Patient Progress'] : null,
                'assessment'    => isset($followUpConsultation['Assessment']) ? $followUpConsultation['Assessment'] : null,
                'plan'    => isset($followUpConsultation['Plan']) ? $followUpConsultation['Plan'] : null,
                'company_id'    => isset($followUpConsultation['Company Id']) ? $followUpConsultation['Company Id'] : null,
                'user_id'    => isset($followUpConsultation['User Id']) ? $followUpConsultation['User Id'] : User::withTrashed()->where('email', $followUpConsultation['Patient Email'])->value('id'),
                'creator_id'    => auth()->id(),
            ]);
        }

        foreach ($data['InvoicesImport'] as $invoice) {
            Invoice::create([
                'product_service'     => isset($invoice['Product / Service Id']) ? $invoice['Product / Service Id'] : null,
                'due_date'    => isset($invoice['Due date']) ? $invoice['Due date'] : null,
                'insurance_name'    => isset($invoice['Insurance name']) ? $invoice['Insurance name'] : null,
                'description'    => isset($invoice['Description']) ? $invoice['Description'] : null,
                'code_serial'    => isset($invoice['Code serial']) ? $invoice['Code serial'] : null,
                'quantity'    => isset($invoice['Quantity']) ? $invoice['Quantity'] : null,
                'charge_id'    => isset($invoice['Charge Id']) ? $invoice['Charge Id'] : null,
                'tax_id'    => isset($invoice['Tax Id']) ? $invoice['Tax Id'] : null,
                'currency_id'    => isset($invoice['Currency Id']) ? $invoice['Currency Id'] : null,
                'company_id'    => isset($invoice['Company Id']) ? $invoice['Company Id'] : null,
                'doctor_id'    => isset($invoice['Doctor Id']) ? $invoice['Doctor Id'] : null,
                'user_id'    => isset($invoice['User Id']) ? $invoice['User Id'] : User::withTrashed()->where('email', $invoice['Patient Email'])->value('id'),
                'creator_id'    => auth()->id(),
            ]);
        }

        // Delete file from the directory
        //Storage::delete('public/excel/'.$request->fileName);

        return response()->json(['status' => 200, 'msg'=> 'Successfully Imported'], 200);
    }
}
