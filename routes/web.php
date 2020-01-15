<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('logout', 'Auth\LoginController@logout');

Auth::routes();

Route::get('/', 'WelcomeController@welcome');

Auth::routes();

Route::post('contact_query', 'WelcomeController@contact_query')->name('contact_query');

Route::resource('homepages', 'HomepageController');
Route::resource('abouts', 'AboutController');
Route::resource('offers', 'OfferController');
Route::resource('teams', 'TeamController');

Route::get('/home', 'HomeController@index')->name('home');

// Timing public route go here
Route::get('timings', 'TimingController@show')->name('timings.show');

// Public renderable appointment booking routes go here
Route::get('book-appointment', 'AppointmentsController@createAppointment')->name('appointment.book');
Route::get('/doctor-by-service/{service}', 'UsersController@doctorByService')->name('doctor.show');
Route::get('/service-by-doctor/{doctor}', 'UsersController@serviceByDoctor')->name('service.show');
Route::post('book-appointment', 'AppointmentsController@appointmentBook');

Route::group(['middleware' => 'auth'], function(){
    Route::post('patients/sendSMS', 'PatientsController@sendSMS')
        ->name('patients.sendSMS');
    Route::get('home_page', function() {
        return view('home_page.index');
    })->name('home_page.index');
    Route::resource('/patients', 'PatientsController')
                ->middleware('root');
    Route::post('get/patients/', 'PatientsController@allPatients')
        ->name('patients.all');
    Route::post('get/trashed/', 'PatientsController@trashedPatients')
        ->middleware('root')->name('users.allTrashed');
    Route::get('/patients/{id}/newTemplate', 'PatientsController@newTemplate')
                ->middleware('root')->name('patients.newTemplate');
    Route::post('/png-to-base64', 'AppointmentsController@pngToBase64')->name('pngToBase64');
    Route::post('/base64-to-png', 'AppointmentsController@base64ToPng')->name('base64ToPng');

    Route::get('/appointments', 'AppointmentsController@index');
    Route::resource('/appointments', 'AppointmentsController');
    Route::post('get/appointments/', 'AppointmentsController@allAppointments')->name('appointments.all');
    Route::get('/calendar', 'AppointmentsController@fullCalendar')->name('appointments.calendar');
    Route::post('/close/{id}', 'AppointmentsController@close')->name('close');
    Route::get('/open/{id}', 'AppointmentsController@open')->name('open');
    Route::post('/progress/{id}', 'AppointmentsController@progress')->name('appointment.progress');

    Route::resource('/users', 'PatientsController');

    Route::post('/appointment-user', 'PatientsController@appointment_user')
        ->name('appointment_user.store');

    Route::get('/patients.trashed', 'PatientsController@trashed')->name('patients.trashed');

    Route::post('/patient_ajax', 'PatientsController@patient_ajax');
    Route::post('/medication_ajax', 'MedicationsController@medication_ajax');

    Route::put('/patients.restore/{id}', 'PatientsController@restore')->name('patients.restore');

    Route::resource('/tasks', 'TasksController');
    Route::get('/alltasks', 'TasksController@allTasks')->name('tasks.alltasks');
    Route::get('/opentasks', 'TasksController@openTasks')->name('tasks.opentasks');
    Route::get('/closedtasks', 'TasksController@closedTasks')->name('tasks.closedtasks');
    Route::get('/close_task/{id}', 'TasksController@close')->name('close_task');
    Route::get('/open_task/{id}', 'TasksController@open')->name('open_task');

    Route::resource('/contacts', 'ContactsController');
    Route::resource('/files', 'FilesController');
    Route::post('get/files/', 'FilesController@allFiles')
        ->name('files.all');
    Route::resource('/products', 'ProductsController');
    Route::post('get/products/', 'ProductsController@allProducts')
        ->name('products.all');
    Route::resource('/invoices', 'InvoicesController');
    Route::post('get/invoices/', 'InvoicesController@allInvoices')
        ->name('invoices.all');
    Route::resource('/payments', 'PaymentsController');
    Route::post('get/payments/', 'PaymentsController@allPayments')
        ->name('payments.all');
    Route::resource('/services', 'ServicesController');
    Route::post('get/services/', 'ServicesController@allServices')
        ->name('services.all');

    Route::put('/users/timings/update/{user}', 'UsersController@updateTimings')->name('users.timings.update');
    Route::resource('/users', 'UsersController');
    Route::post('get/users/', 'UsersController@allUsers')
        ->name('users.all');
    Route::get('/my_profile', 'UsersController@my_profile')->name('users.my_profile');
    Route::get('/users.trashed', 'PatientsController@trashed')->name('users.trashed');
    Route::put('/users.restore/{id}', 'PatientsController@restore')->name('users.restore');

    //Ajax add Users dynamically.
    Route::post('/api/add/users', 'AddUsersController@add_user');

    Route::get('/settings', 'SettingsController@index')->name('settings.index');

    Route::resource('/doctortimings', 'DoctortimingsController');
    Route::post('/change_timing_status/{id}', 'DoctortimingsController@change_timing_status')
            ->name('change_timing_status');

    Route::resource('/taxes', 'TaxController');

    Route::resource('/medications', 'MedicationsController');
    Route::post('/prescription_medication', 'MedicationsController@prescription_medication')
                ->name('prescription_medication.store');
    Route::post('get/medications/', 'MedicationsController@allMedications')
        ->name('medications.all');

    Route::resource('/drugs', 'DrugsController');

    Route::resource('/paymentmethods', 'PaymentmethodsController');

    Route::resource('/currencies', 'CurrencyController');

    Route::resource('/companydetails', 'CompanyDetailsController');

    Route::resource('/charges', 'ChargesController');


    Route::get('markAsRead', function(){
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'All notifications marked as read.');
    })->name('markAsRead');
    //Notes

    Route::resource('/notes', 'NotesController');

    Route::resource('/initialnotes', 'InitialConsultationController');

    Route::resource('/followupnotes', 'FollowupConsultationController');

    Route::resource('/vitals', 'VitalsController');

    Route::resource('/letters', 'LettersController');

    Route::resource('/prescriptions', 'PrescriptionsController');
    Route::post('/patient_create_contact', 'PrescriptionsController@prescriptionsEdit')->name('prescriptionsEdit');

    Route::resource('/referrals', 'ReferralController');

    Route::resource('/sicknotes', 'SicknoteController');

    Route::resource('/certificates', 'CertificateController');

    Route::resource('/roles', 'RolesController');

    //Patient Contact
    Route::get('/patient_create_contact/{id}', 'PatientContactController@create')->name('patientcontact.create');
    Route::post('/patient_store_contact', 'PatientContactController@store')->name('patient_store_contact.store');

    //Patient Appointment
    Route::get('/patient_create_appointment/{id}', 'PatientAppointmentController@create')->name('patientappointment.create');
    Route::post('/patient_store_appointment', 'PatientAppointmentController@store')->name('patient_store_appointment.store');

    //Patient Document
    Route::get('/patient_create_document/{id}', 'PatientDocumentController@create')->name('patientdocument.create');
    Route::post('/patient_store_document', 'PatientDocumentController@store')->name('patient_store_document.store');

    //Patient Task
    Route::get('/patient_create_task/{id}', 'PatientTaskController@create')->name('patienttask.create');
    Route::post('/patient_store_task', 'PatientTaskController@store')->name('patient_store_task.store');

    //Patient Task
    Route::get('/patient_create_invoice/{id}', 'PatientInvoiceController@create')->name('patientinvoice.create');
    Route::post('/patient_store_invoice', 'PatientInvoiceController@store')->name('patient_store_invoice.store');

    //Patient Referral
    Route::get('/patient_create_prescription/{id}', 'PatientPrescriptionController@create')->name('patientprescription.create');  //Not Needed
    Route::post('/patient_store_prescription', 'PatientPrescriptionController@store')->name('patient_store_prescription.store');

    //Patient Referral
    Route::get('/patient_create_referral/{id}', 'PatientReferralController@create')->name('patientreferral.create');
    Route::post('/patient_store_referral', 'PatientReferralController@store')->name('patient_store_referral.store');

    //Patient Sicknote
    Route::get('/patient_create_sicknote/{id}', 'PatientSicknoteController@create')->name('patientsicknote.create');
    Route::post('/patient_store_sicknote', 'PatientSicknoteController@store')->name('patient_store_sicknote.store');

    //Patient Certificate
    Route::get('/patient_create_certificate/{id}', 'PatientCertificateController@create')->name('patientcertificate.create');
    Route::post('/patient_store_certificate', 'PatientCertificateController@store')->name('patient_store_certificate.store');

    //Patient Initialnote
    Route::get('/patient_create_initialnote/{id}', 'PatientInitialnoteController@create')->name('patientinitialnote.create');
    Route::post('/patient_store_initialnote', 'PatientInitialnoteController@store')->name('patient_store_initialnote.store');

    //Patient Follow up note
    Route::get('/patient_create_followupnote/{id}', 'PatientFollowupnoteController@create')->name('patientfollowupnote.create');
    Route::post('/patient_store_followupnote', 'PatientFollowupnoteController@store')->name('patient_store_followupnote.store');

    //Patient Vital
    Route::get('/patient_create_vital/{id}', 'PatientVitalController@create')->name('patientvital.create');
    Route::post('/patient_store_vital', 'PatientVitalController@store')->name('patient_store_vital.store');

    // Export excel files
    Route::get('export', 'ExcelController@export')->name('excel.export');
    Route::get('import/create', 'ExcelController@create')->name('excel.create');
    Route::post('patients/import', 'ExcelController@import')->name('excel.import');
    Route::post('import/store', 'ExcelController@importStore')->name('excel.store');

    // Timings routes go here
    Route::post('timings', 'TimingController@store')->name('timings.store');
    Route::delete('day_breaks/{day_break}', 'TimingController@destroy')->name('timings.destroy');

    // Availability routes go here
    Route::post('availabilities', 'AvailabilityController@store')->name('availabilities.store');
    Route::get('availabilities/{availability}/edit', 'AvailabilityController@edit')->name('availabilities.edit');
    Route::put('availabilities/{availability}', 'AvailabilityController@update')->name('availabilities.update');
    Route::delete('availabilities/{availability}', 'AvailabilityController@destroy')->name('availabilities.destroy');

    // Report routes go here
    Route::get('reports', function () {
        return view('reports.index');
    })->name('reports.index');
    Route::prefix('reports')->group(function () {
        Route::get('appointment/appointments_schedule', 'ReportController@createAppointmentsSchedule')->name('reports.appointments.schedule');
        Route::post('appointment/appointments_schedule', 'ReportController@appointmentsSchedule');
        Route::get('appointment/missed_appointments', 'ReportController@createMissedAppointments')->name('reports.appointments.missed');
        Route::post('appointment/missed_appointments', 'ReportController@missedAppointments');
        Route::get('marketing/appointments', 'ReportController@createMarketingAppointments')->name('reports.marketing.appointments');
        Route::post('marketing/appointments', 'ReportController@marketingAppointments');
        Route::get('patients/patients_by_total_invoiced', 'ReportController@createPatientsByTotalInvoiced')->name('reports.patients.patients_by_total_invoiced');
        Route::post('patients/patients_by_total_invoiced', 'ReportController@patientsByTotalInvoiced');
        Route::get('marketing/referral_sources', 'ReportController@createReferalMarketing')->name('reports.marketing.referral_sources');
        Route::post('marketing/referral_sources', 'ReportController@referalMarketing');
    });

    // Template routes go here
    Route::delete('sections/{section}', 'TemplateController@destroySection');
    Route::delete('questions/{question}', 'TemplateController@destroyQuestion');
    Route::delete('answers/{answer}', 'TemplateController@destroyAnswer');
    Route::resource('templates', 'TemplateController');

    // Patient Treatment Notes routes go here
    Route::resource('patient_treatment_notes', 'PatientTreatmentNoteController');
    Route::get('patient_treatment_notes/{id}/sections', 'PatientTreatmentNoteController@sections')->name('treatmentNote.sections');
    Route::post('import', 'MedicationsController@import')->name('import.medications');
    Route::post('/file-upload', 'MedicationsController@uploadFile')->name('file.upload');

    // Symptoms questionnaire routes go here
    Route::post('questionnaires/result/{questionnaire}', 'QuestionnaireController@result')->name('questionnaires.result');
    Route::post('questionnaires/import', 'QuestionnaireController@import')->name('questionnaires.import');
    Route::post('questionnaires/import/store', 'QuestionnaireController@importStore')->name('questionnaires.import.store');
    Route::delete('questionnaires/del/{type}/{id}', 'QuestionnaireController@destroyROw');
    Route::get('questionnaires/list', 'QuestionnaireController@questionList')->name('questionnaires.list');
    Route::get('questionnaires/show/{id}', 'QuestionnaireController@showSingle')->name('questionnaires.single');
    Route::resource('questionnaires', 'QuestionnaireController');
    Route::resource('bodycharts', 'BodychartController');
    Route::get('get-bodycharts', 'BodychartController@getBodycharts')->name('getBodycharts');

    // Embed URL routes go here
    Route::post('embed_urls', 'EmbedUrlController@store')->name('embed_urls.store');
    Route::get('embed_urls/{embed_url}/edit', 'EmbedUrlController@edit')->name('embed_urls.edit');
    Route::put('embed_urls/{embed_url}', 'EmbedUrlController@update')->name('embed_urls.update');
    Route::delete('embed_urls/{embed_url}', 'EmbedUrlController@destroy')->name('embed_urls.destroy');

    // Sms routes go here
    Route::get('messages', 'SmsController@index')->name('messages.index');
    Route::delete('messages', 'SmsController@destroy')->name('messages.destroy');

    // SmsJob routes go here
    Route::resource('jobs', 'JobController');

    // Stripe routes go here
    Route::get('stripe-redirect', 'PaymentController@stripeRedirect')->name('stripe.redirect');
    Route::get('stripe-disconnect', 'PaymentController@disconnect')->name('stripe.disconnect');
});

Addchat::routes();
