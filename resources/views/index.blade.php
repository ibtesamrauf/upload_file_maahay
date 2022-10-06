@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Welcome</div>

                <div class="card-body">
                    Click here to go to upload file page
                    <a href="{{ route('upload.file') }}" class="btn btn-primary">Upload File</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
