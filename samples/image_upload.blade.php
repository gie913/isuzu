var active_image_uploads = 0;

$('.upload_file').change(function(){
    var data_name = $(this).attr('data-name');
    var data_preview = $(this).attr('data-preview');
    var data_setvalue = $(this).attr('data-setvalue');
    var m_module_id = $(this).attr('data-module');

    var fd = new FormData();
    var files = $(this)[0].files[0];
    if (!files) {
        return;
    }
    fd.append('file',files);
    fd.append("m_module_id", m_module_id); // for parameters
    fd.append("name", data_name); // for parameters
    $.ajax({
        url: '{{ url("api/v1/upload-file")}}',
        type: 'post',
        data: fd,
        contentType: false, // because we send file
        processData: false,
        beforeSend: function(){
            active_image_uploads++;
            $('#btn-submit').prop('disabled', true).addClass('disabled');
            $(data_preview).html('<i class="bx bx-loader bx-spin  bx-md"></i> Mengunggah');
        },
        success: function(response){
            console.log(response.success);
            //var data = JSON.parse(response);
           // console.log(data);
            if(response.success === true){
             var url = "{{ url('view-file-external?file_path=')}}"+response.data.url ;
             var string = "<a href='#'  onclick=\"window.open('"+  url+"', 'Detail', 'width=500,height=600')\";> <i class='bx bx-check'></i>Berhasil diunggah. Klik untuk melihat </a> <br>";

                $(data_preview).html(string);
                $(data_setvalue).val(response.data.file_id);

            }else{
                alert('Gagal mengunggah . '+response.message);
            }
        },
        error: function(data)
        {
            var errorMsg = (data.responseJSON && data.responseJSON.message) ? data.responseJSON.message : 'Gagal mengunggah, silakan coba lagi';
            $(data_preview).html('<p style="color:#EA4335;">'+ errorMsg +'</p>');
        },
        complete: function()
        {
            active_image_uploads--;
            if (active_image_uploads <= 0) {
                active_image_uploads = 0;
                $('#btn-submit').prop('disabled', false).removeClass('disabled');
            }
        }
    });
 });
