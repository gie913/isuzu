const documents = ["pdf", "xls", "xlsx","doc", "docx"];
const images = ["jpg", "png", "jpeg","jiff","gif","webp"];
var active_file_uploads = 0;

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
            active_file_uploads++;
            $('#btn-submit').prop('disabled', true).addClass('disabled');
            $(data_preview).html('<i class="bx bx-loader bx-spin bx-md"></i> Mengunggah');
        },
        success: function(response){
            console.log(response.success);
            //var data = JSON.parse(response);
           // console.log(data);
            if(response.success === true){
             var url = response.data.url ;
             var string;
             if (images.includes(response.data.file_extension)) {
                string = "<img class=\"img-fluid img-thumbnail\" src=\""+ response.data.url +"\" onclick=\"window.open('"+  url+"', 'Detail', 'width=500,height=600')\"; style=\"max-height:120px\"> ";
                }
             else
             {
                string = "<iframe src=\""+ response.data.url +"\" onclick=\"window.open('"+  url+"', 'Detail', 'width=500,height=600')\"; style=\"max-height:120px\"></iframe>";

             }

                $(data_preview).html(string);
                $(data_setvalue).val(response.data.file_id);

            }else{
                alert(response.message);
            }
        },
        error: function(data)
        {
            var errorMsg = (data.responseJSON && data.responseJSON.message) ? data.responseJSON.message : 'Gagal mengunggah file';
            $(data_preview).html('<p style="color:#EA4335;">'+ errorMsg +'</p>');
        },
        complete: function()
        {
            active_file_uploads--;
            if (active_file_uploads <= 0) {
                active_file_uploads = 0;
                $('#btn-submit').prop('disabled', false).removeClass('disabled');
            }
        }
    });
 });
