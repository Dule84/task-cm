$(document).ready(function (e) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let wrapper = document.getElementById("signature-pad");
    let saveButton = document.getElementById("sign");
    let canvas = wrapper.querySelector("canvas");
    let signaturePad;

    window.resizeCanvas = function () {
        const ratio = window.devicePixelRatio || 1;
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
    }

    resizeCanvas();

    signaturePad = new SignaturePad(canvas);

    saveButton.addEventListener("click", function(event) {
        let dataUrl = signaturePad.toDataURL();
        let signature = dataUrl.replace(/^data:image\/(png|jpg);base64,/, "");
        let pdf_file = document.getElementById("pdf_file").files[0];

        let formData = new FormData();

        formData.append('pdf_file', pdf_file);
        formData.append('signature', signature);

        $.ajax({
            url: '/create',
            type: 'POST',
            data: formData,
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            success: function (data) {
                window.location.replace(window.location.origin);
            },
            error: function (textStatus) {
                $('[name="pdf_file"]').next('span').html(textStatus.responseJSON.pdf_file);
            }
        });
    });
});
