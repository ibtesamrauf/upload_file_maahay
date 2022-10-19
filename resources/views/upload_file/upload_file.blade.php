@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<style>
    .padding-zero {
        padding: 0px;
    }
    .background-color-custom {
        background-color: #F5F5F7;
    }
    .dropzone-background-color{
        background-color: #F5DDE7
    }
    .dropzone-custom {
        /* width: 70%; */
        border: 0px;
    }
</style>
<div class="container background-color-custom" style="max-width: unset;">
    <div class="row justify-content-center">
        <div class="col-md-6 pt-5">
            <h3 class="jumbotron text-center "><strong>Upload your Data</strong></h3> 
            <p class="text-center text-secondary" style="padding-bottom: 0px !important; margin-bottom: 0px !important;">deliver your Data fast, save & </p>
            <p class="text-center text-secondary">without interference of others</p>
            
            <div class="text-center">
                <form method="post" action="{{url('file/upload/store')}}" enctype="multipart/form-data" id="frmTarget" class="dropzone mb-2 dropzone-background-color dropzone-custom">
                    @csrf                        
                    <input type="hidden" name="user_file_share_with_id" id="user_file_share_with_id">
                </form>  
            </div>
            <div id="preview" class="d-none" >
                <div class="">
                    <strong>Uploaded Files: </strong>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Size</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="preview-tbody"></tbody>
                </table>
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-link see-more" style="display:none">See more</button>
                </div>
                <div class="error-box-dynamic"></div>
                
                <form action="{{route('user.share.image.create')}}" onsubmit="return false" id="details_form">
                    @csrf                        
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    <input type="text" class="form-control mb-2" name="title" placeholder="Title" required>
                    <textarea class="form-control mb-2" name="message" rows="3" placeholder="Message" required></textarea>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary ">Share now!</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6 padding-zero">
            <img class="img-fluid" src="{{ asset('images/my-way.png') }}">
            <!-- <img class="img-fluid" src="{{ asset('images/drop image.PNG') }}"> -->
        </div>
    </div>
</div>

<script type="text/javascript">
    
    var count = 0;
    var max_files = 10;
    var max_file_size = 104857600;
    var myDropzone;
    Dropzone.options.frmTarget = {
        maxFiles: 10,
        maxFilesize : 100,
        parallelUploads : 10,
        autoProcessQueue: false,
        url: "{{route('file.upload.store')}}",
        dictDefaultMessage: '<i class="fas fa-cloud-upload-alt fa-3x " style="color:#F21A5D"></i><p class="text-secondary">Drag more files here or click here to upload</p>',
        
        previewsContainer: false,
        init: function () {
            myDropzone = this;
            this.on("maxfilesexceeded", function(file){
                notifyGlobal({ message: 'A user can not select and upload more than 10 files at a time!' },{ type: 'danger' });
            });
            // Update selector to match your button
            $("#button").click(function (e) {
                e.preventDefault();
                myDropzone.processQueue();
            });

            this.on('sending', function(file, xhr, formData) {
                // Append all form inputs to the formData Dropzone will POST
                var data = $('#frmTarget').serializeArray();
                $.each(data, function(key, el) {
                    formData.append(el.name, el.value);
                });
            });
            this.on("addedfile", function(file) {
                // console.log("file add: ",file)
                // console.log("file add: ",count)
                if(file.size > max_file_size) notifyGlobal({ message: 'Each file should not exceed size of 100mb!' },{ type: 'danger' });
                
                if(max_files > count && file.size <= max_file_size){
                    var _this = this;
                    count = count+1;
                    $("#preview").removeClass('d-none');
                    let _size;
                    if(file.size < 1000000) _size = Math.floor(file.size/1000) + 'KB';
                    else _size = Math.floor(file.size/1000000) + 'MB';  
                    $('.preview-tbody').append("<tr id='file_identifier_"+file.upload.uuid+"'><td>"+file.name+"</td><td>"+_size+"</td><td id='remove-button"+count+"'></td></tr>");
                    $('#remove-button'+count).html($('<div><i class="fa fa-minus-circle" aria-hidden="true"></i></div>').click(function(e){
                        e.preventDefault();
                        e.stopPropagation();
                        // console.log("removeButton: ",file);
                        $('#file_identifier_'+file.upload.uuid).remove();
                        _this.removeFile(file);
                    }));
                    if(count > 2) {
                        $("table > tbody > tr").hide().slice(0, 3).show();
                        $('.see-more').show();
                    }
                }
                if(file.previewElement) file.previewElement.parentNode.removeChild(file.previewElement);
            });
            this.on("removedfile", function(file) {
                count = count-1;
                if(myDropzone.getAcceptedFiles().length == 0) $("#preview").addClass('d-none');
            });
            this.on("complete", function(file) {
                // console.log("aaaaaaaaaaaaa");
                // when all files uploded and validation of 100mb
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0 && file.size <= max_file_size) {
                    // console.log("all files uploded");
                    location.reload(true);
                }
            });

        }
        
    }

    $('.see-more').click(function (e) {
        $("table > tbody > tr").show();
        $('.see-more').hide();
    });

    // to submit title and message form then submit dropzone files 
    $('#details_form').submit(function(e){
        // Stop the form submitting
        e.preventDefault();
        $('.error-box-dynamic').html("");

        $.ajax({
            method: "POST",
            url:  $(this).attr('action'),
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: (result) => {
                if(result.status){
                    // console.log(result);
                    $('#user_file_share_with_id').val(result.data.id)
                    myDropzone.processQueue();
                }
            },
            error: (e) => {
                Object.keys(e.responseJSON.error).forEach(function(key) {
                    $('.error-box-dynamic').append('<div class="alert alert-danger" role="alert">'+e.responseJSON.error[key]+'</div>');
                });
            }
        });
    });
    
</script>

@endsection
