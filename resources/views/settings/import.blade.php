@extends('adminlte::page')

@section('content')
    <div class="alert alert-success alert-dismissible" style="display: none">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Success!</strong> Excel data imported successfully.
    </div>

 <div class="panel panel-success">
    <div class="panel-heading">
           <div class="row">
                 <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                 </div>
                  <div class="col-md-4">
                     <strong>Settings</strong>
                  </div>
               <div class="col-md-2">

               </div>
           </div>
    </div>
     <div class="panel-body">
         <div class="container">
             <div class="row">
                 <div class="col-md-4"></div>
                 <div class="col-md-4">
                     <form action="{{ route('excel.import') }}" id="excel-import" method="post" enctype="multipart/form-data">
                         @csrf
                         <div class="form-group">
                             <input type="file" accept=".csv,.xlsx,.xls" class="form-control @error('patient_excel') has-error @enderror" name="patient_excel" required>
                             @error('patient_excel')
                             <div class="invalid-feedback">
                                 Please provide an excel file to import.
                             </div>
                             @enderror
                         </div>
                         <button class="btn btn-sm btn-warning btn-block"><i class="fa fa-file-excel-o"></i> Import excel</button>
                     </form>
                 </div>
                 <div class="col-md-4"></div>
             </div>
         </div>
     </div>
 </div>

@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.1/xlsx.full.min.js"></script>
    <script>
        // Send ajax request Add Product
        $("#excel-import").submit(function(event) {
            // Stop browser from submitting the form
            event.preventDefault();

            // Send ajax request
            $.ajax({
                type: 'POST',
                url: '{{ route('excel.import') }}',
                data: new FormData( this ),
                cache: false,
                contentType: false,
                processData: false,
                success: function(data){
                    parseExcel(data[0], data[1]);
                },
            });
        });

        function parseExcel(uri, fileName) {
            var sheet = {
                UsersImport: [],
                ContactsImport: [],
                AppointmentsImport: [],
                DocumentsImport: [],
                TasksImport: [],
                PrescriptionLettersImport: [],
                ReferralLettersImport: [],
                SickNoteLettersImport: [],
                CertificateLettersImport: [],
                InitialConsultationsImport: [],
                FollowUpConsultationsImport: [],
                VitalsImport: [],
                InvoicesImport: [],
            };

            let url = uri;
            let oReq = new XMLHttpRequest();
            oReq.open("GET", url, true);
            oReq.responseType = "arraybuffer";
            oReq.onload = function (e) {
                let arraybuffer = oReq.response;
                let data = new Uint8Array(arraybuffer);
                let arr = new Array();
                for (let i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(data[i]);
                let bstr = arr.join("");
                let workbook = XLSX.read(bstr, {type: "binary"});

                $.each(workbook.SheetNames, function (i, v) {
                    if(v === "Patients"){
                        sheet.UsersImport = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[i]], {raw: true});
                    }
                    else if(v === "Contacts"){
                        sheet.ContactsImport = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[i]], {raw: true});
                    }
                    else if(v === "Appointments"){
                        sheet.AppointmentsImport = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[i]], {raw: true});
                    }
                    else if(v === "Documents"){
                        sheet.DocumentsImport = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[i]], {raw: true});
                    }
                    else if(v === "Tasks"){
                        sheet.TasksImport = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[i]], {raw: true});
                    }
                    else if(v === "Prescription Letters"){
                        sheet.PrescriptionLettersImport = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[i]], {raw: true});
                    }
                    else if(v === "Referral Letters"){
                        sheet.ReferralLettersImport = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[i]], {raw: true});
                    }
                    else if(v === "Sick Note Letters"){
                        sheet.SickNoteLettersImport = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[i]], {raw: true});
                    }
                    else if(v === "Certificate Letters"){
                        sheet.CertificateLettersImport = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[i]], {raw: true});
                    }
                    else if(v === "Initial Consultations"){
                        sheet.InitialConsultationsImport = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[i]], {raw: true});
                    }
                    else if(v === "Follow Up Consultations"){
                        sheet.FollowUpConsultationsImport = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[i]], {raw: true});
                    }
                    else if(v === "Vitals"){
                        sheet.VitalsImport = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[i]], {raw: true});
                    }
                    else if(v === "Invoices"){
                        sheet.InvoicesImport = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[i]], {raw: true});
                    }
                });

                uploadExcel(sheet, fileName);
            };
            oReq.send();
        }

        function uploadExcel(data, fileName) {
            // Send ajax request
            $.ajax({
                type: 'POST',
                url: '{{ route('excel.store') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'data': JSON.stringify(data),
                    'fileName': fileName,
                },
                success: function(data){
                    if (data.status === 200) {
                        $("#excel-import").trigger('reset');

                        $('.alert-success').show();
                        setTimeout(function () {
                            $('.alert-success').hide();
                        }, 3000);
                    }
                },
            });
        }
    </script>
@endsection
