@php($tabMenuPosition = $tabMenuPosition ?? 1)
@php($patient_id = $patient_id ?? 1)
<div class="nav-tabs-custom no-print">
    <ul class="nav nav-tabs nav-tabs-success">
        <li @if($tabMenuPosition == 1) class="active" @endif><a href="{{route('patients.show', [$patient_id, '#more_details'])}}">Details</a></li>
        <li @if($tabMenuPosition == 2) class="active" @endif><a href="{{route('patients.show', [$patient_id, '#contacts'])}}">Contacts</a></li>
        <li @if($tabMenuPosition == 3) class="active" @endif><a href="{{route('patients.show', [$patient_id, '#appointments'])}}">Appointments</a></li>
        <li @if($tabMenuPosition == 4) class="active" @endif><a href="{{route('patients.show', [$patient_id, '#documents'])}}">Documents</a></li>
        <li @if($tabMenuPosition == 5) class="active" @endif><a href="{{route('patients.show', [$patient_id, '#notes'])}}">Notes</a></li>
        <li @if($tabMenuPosition == 6) class="active" @endif><a href="{{route('patients.show', [$patient_id, '#prescriptions'])}}">Prescriptions</a></li>
        <li @if($tabMenuPosition == 7) class="active" @endif><a href="{{route('patients.show', [$patient_id, '#letters'])}}">Letters</a></li>
        <li @if($tabMenuPosition == 8) class="active" @endif><a href="{{route('patients.show', [$patient_id, '#tasks'])}}">Tasks</a></li>
        <li @if($tabMenuPosition == 9) class="active" @endif><a href="{{route('patients.show', [$patient_id, '#invoices'])}}">Invoices</a></li>
    </ul>
</div>