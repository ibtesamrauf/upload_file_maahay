<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserFile;
use App\Models\UserFileShareWith;
use Illuminate\Support\Facades\Validator;

class UploadFileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function uploadFile()
    {
        return view('upload_file.upload_file');
    }

    public function fileStore(Request $request)
    {
        $rules = [
            'file'=> 'required|max:100000',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->messages()
            ], 422); 
        } else {
            $image = $request->file('file');
            $imageName = $image->getClientOriginalName();
            $image->move(public_path('share_images'),$imageName);

            $UserFile = new UserFile();
            $UserFile->filename = $imageName;
            $UserFile->user_file_share_with_id = $request->user_file_share_with_id;
            $UserFile->save();
            return response()->json(['success'=>$imageName]);
        }
    }


    public function userShareImageCreate(Request $request)
    {
        $rules = [
            'title'=> 'required',
            'message'=> 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->messages()
            ], 422); 
        } else {
            $UserFileShareWith = UserFileShareWith::create($request->all());
            return response()->json([
                'status' => true,
                'data' => $UserFileShareWith,
            ], 201); 
        }
    }
    public function userShareFiles(Request $request)
    {
        $user_file = UserFileShareWith::with('user_files')->where('user_id',\Auth::user()->id)->get();
        // var_dump($user_file);
        // echo "<pre>";
        // print_r($user_file);
        // echo "</pre>";
        // die;
        return view('user_shared_file', compact('user_file'));
    }
    
    
}
