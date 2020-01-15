let tool = [
    [ 'style', [ 'style' ] ],
    [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
    [ 'fontname', [ 'fontname' ] ],
    [ 'fontsize', [ 'fontsize' ] ],
    [ 'color', [ 'color' ] ],
    [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
    [ 'table', [ 'table' ] ],
    [ 'insert', [ 'link'] ],
    [ 'view', [ 'undo', 'redo', 'fullscreen' ] ]
];


let tool2 = [
    [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'clear'] ],
    [ 'fontsize', [ 'fontsize' ] ],
    [ 'color', [ 'color' ] ],
    [ 'para', [ 'ol', 'ul', 'paragraph' ] ],
];

$(document).ready(function() {
    $('#more_info').summernote({
        toolbar: tool
    });
});

$(document).ready(function() {
    $('#medication_allergies').summernote({
        toolbar: tool
    });
});


$(document).ready(function() {
    $('#medical_history').summernote({
        toolbar: tool
    });
});

$(document).ready(function() {
    $('#current_medication').summernote({
        toolbar: tool
    });
});

$(document).ready(function() {
    $('#description').summernote({
        toolbar: tool
    });
});

$(document).ready(function() {
    $('#gp_details').summernote({
        toolbar: tool
    });
});


// Homepage Section
$(document).ready(function() {
    $('#slider_area').summernote({
        toolbar: tool
    });
});

$(document).ready(function() {
    $('#about_area').summernote({
        toolbar: tool
    });
});

$(document).ready(function() {
    $('#offer_area').summernote({
        toolbar: tool
    });
});

$(document).ready(function() {
    $('#team_area').summernote({
        toolbar: tool
    });
});


// Initial Consultation Note
$(document).ready(function() {
    $('#initialnote').summernote({
        toolbar: tool2
    });
});

$(document).ready(function() {
    $('#initialnote1').summernote({
        toolbar: tool2
    });
});

$(document).ready(function() {
    $('#initialnote2').summernote({
        toolbar: tool2
    });
});

$(document).ready(function() {
    $('#initialnote3').summernote({
        toolbar: tool2
    });
});

$(document).ready(function() {
    $('#initialnote4').summernote({
        toolbar: tool2
    });
});

$(document).ready(function() {
    $('#initialnote5').summernote({
        toolbar: tool2
    });
});

$(document).ready(function() {
    $('#initialnote6').summernote({
        toolbar: tool2
    });
});

$(document).ready(function() {
    $('#initialnote7').summernote({
        toolbar: tool2
    });
});

$(document).ready(function() {
    $('#initialnote8').summernote({
        toolbar: tool2
    });
});

$(document).ready(function() {
    $('#initialnote9').summernote({
        toolbar: tool2
    });
});

// Follow Up Note
$(document).ready(function() {
    $('#followupnote1').summernote({
        toolbar: tool
    });
});

$(document).ready(function() {
    $('#followupnote2').summernote({
        toolbar: tool
    });
});

$(document).ready(function() {
    $('#followupnote3').summernote({
        toolbar: tool
    });
});

$(document).ready(function() {
    $('#prescription1').summernote({
        toolbar: tool2
    });
});

$(document).ready(function() {
    $('#prescription2').summernote({
        toolbar: tool2
    });
});

$(document).ready(function() {
    $('#patient_note').summernote({
        toolbar: tool,
    });
});
