@extends('layouts.app')

@section('content')
<div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table">
                    <caption>List of user shared files</caption>
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Message</th>
                        <th scope="col">Files</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user_file as $value)
                            <tr>
                                <th scope="row">{{$value->id}}</th>
                                <td>{{$value->title}}</td>
                                <td>{{$value->message}}</td>
                                <td>
                                    @if($value->user_files)
                                        <table class="table">
                                            <tbody>
                                                @foreach($value->user_files as $value_files)
                                                    <tr>
                                                        <td>{{$value_files->id}}</td>
                                                        <td>{{$value_files->filename}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection