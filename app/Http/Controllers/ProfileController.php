<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profiles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('form');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd()
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        if($request->update)
        {
            // dd("update info",$request->all());
            $edit_data = $request->all();

            if($request->img){
                $imageUpload = $this->storeImage($request->img);
                $edit_data['img']=$imageUpload;
            }

            // dd($edit_data);

            $all_profile = session('PROFILES');
            $format=[];
            foreach($all_profile as $data){
                if($data['uId'] == $request->uId){
                    if(isset($data["img"]) && !isset($edit_data["img"])){
                        $edit_data["img"] = $data["img"];
                    }
                    array_push($format, $edit_data);
                }else{
                    array_push($format,$data);
                }
            }
            session(["PROFILES"=>$format]);
            return redirect('/');

        }
        
        $formData = $request->all();
        
        //giving unique id to each entry
        $formData["uId"]=rand(1,100);

        //store path and get path
        if(isset($formData['img'])){

            $imageUpload = $this->storeImage($formData['img']);
            $formData["img"]=$imageUpload;
        }else{
            $formData["img"]=NULL;
        }

        //get all profiles and add new
        $exist = session('PROFILES')? session('PROFILES') : [];
        array_push($exist,$formData);
        session(["PROFILES"=>$exist]);
        
        return back();

        
    }
    
    public function edit($id)
    {
        $all_profile = session('PROFILES')?session('PROFILES') : [];
        foreach($all_profile as $data){
            if($data['uId'] == $id){
                return view('form')->with('edit', $data);
            }
        }
    }

   
    public function destroy($id)
    {
        $all_profile = session('PROFILES');
        $filter = [];
        foreach($all_profile as $data){
            if($data['uId'] != $id){
                array_push($filter,$data);
            }else{
                if(isset($data['img'])){
                    $path_file=public_path().'/'.$data['img'];
                    unlink($path_file);
                }
            }
        }
        session(['PROFILES'=>$filter]);
        return redirect("/");
    }
    function saveDb(){
        $all_profile = session('PROFILES');
        // dd($all_profile);
        foreach($all_profile as $data){
           $data_db=[
            'name' => $data['name'],
            'email' => $data['mail'],
            'mobile' => $data['number'],
            'role' => $data['role'],
            'password' => bcrypt($data['pass']),
            'image' => $data['img'],
            'date'=> $data['date']
           ];

            Profiles::create($data_db);
        }
        session()->forget('PROFILES');
        // dd($all_profile);

        return redirect('/')->with('mess','USERS SAVED SUCCESSFULLY');
    }

    private function storeImage($file):string{
        
        if($file){
            $fileName=$file->getClientOriginalName();
            $folder_dir = public_path()."/Images/";
            $file_name=rand(1000,10000)."-".$fileName;
            $file->move($folder_dir, $file_name); 
            return "Images/".$file_name;
        }
    }
}
