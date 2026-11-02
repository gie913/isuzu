/* image cropper */
const countdownTimer = 20000;
var cropper;
var myModal = new bootstrap.Modal(document.getElementById('getCroppedCanvasModal'));
$('.imagecrop').change(function(){
var type = $(this).attr('data-type');
var name = $(this).attr('data-name');
var preview = $(this).attr('data-preview');
var setvalue = $(this).attr('data-setvalue');
var image_modal = $(this).attr('data-image-modal');
var ratio_x = $(this).attr('data-ratio-x');
var ratio_y = $(this).attr('data-ratio-y');

$('#uploadCroppedImageButton').attr('data-setvalue',setvalue);
$('#uploadCroppedImageButton').attr('data-name',name);
$('#uploadCroppedImageButton').attr('data-preview',preview);

var file = event.target.files[0];
var reader = new FileReader();

    reader.onload = function(e) {
      $(image_modal).html('<img id="previewImage" src="' + e.target.result + '">');
      // Initialize Cropper.js
      cropper = new Cropper(document.getElementById('previewImage'), {
        aspectRatio: eval(ratio_x) / eval(ratio_y), // Set aspect ratio as needed
        viewMode: 1, // Set view mode (0, 1, 2, 3)
        guides: true, // Show guides
        autoCropArea: 1, // Automatically crop the initial image
        movable: false, // Disable dragging the cropped area
        zoomable: true, // enable zooming
        cropBoxResizable: true, // Enable resizing of the crop box
        crop: function(event) {
          // Update crop data if needed
          console.log(event.detail.x);
          console.log(event.detail.y);
          console.log(event.detail.width);
          console.log(event.detail.height);
        }
      });
    };
    reader.readAsDataURL(file);
 myModal.show();
});


$('#uploadCroppedImageButton').click(function() {
var name = $(this).attr('data-name');
var preview = $(this).attr('data-preview');
var setvalue = $(this).attr('data-setvalue');
myModal.hide();

// Upload the cropped image to the server (you need to implement this)
var croppedImageData = cropper.getCroppedCanvas().toDataURL('image/jpeg');
// Example: Sending the image data to a server-side script using AJAX
$.ajax({
  type: 'POST',
  url: "{{ url('api/v1/upload-cropped-image')}}", // Replace with your server-side script URL
  data: { image: croppedImageData,
          m_module_id:'{{$module_id}}',
          name: name},
beforeSend: function(){
        $(preview).html('<i class="bx bx-loader bx-spin  bx-md"></i> {{__('label.uploading')}}');
    },
  success: function(response) {
    var url = response.data.file_src ;
    console.log('Image uploaded successfully');
    console.log(response);
    var string = "<img src='"+url+"' height='100px' onclick=\"window.open('"+  url+"', 'Detail', 'width=500,height=600')\"; class='img-fluid img-thumbnail' style='max-height:100px'><br>";
        $(preview).html(string);
        $(setvalue).val(response.data.file_id);

    // myModal = new bootstrap.Modal(document.getElementById('getCroppedCanvasModal'));

  },
  error: function(xhr, status, error) {
    console.error('Error uploading image:', error);
    $(preview).html('<p style="color:#EA4335;">{{__('label.failed_uploaded')}}</p>');

  }
});
});

/* end image cropper */
