<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;




class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {  
        //$this->middleware('api');
        $this->middleware('auth:api'); //bas e3melt authorize zebtat eniu ejeb el data
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   //$this->authorize('isAdmin'); //el user bebatlo ytla3o eza ana fayteh b email user // only access for admin type
        if (Gate::allows('isAdmin') || Gate::allows('isAuthor')) {
            return User::latest()->paginate(5);
        }
      
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|max:191|unique:users',
            'password' => 'required|string|min:6'
        ]);
        return User::create([
            'name'=>$request['name'],
            'email'=>$request['email'],
            'type'=>$request['type'],
            'bio'=>$request['bio'],
            'photo'=>$request['photo'],
            'password'=>Hash::make($request['password'])
                  ]);
    }
    //return $request->photo; //3shan ytla3 3endy el data bl network, btjeeb el data from el form
     //this funcion call the put request
    //Request $request to accept the request
    
    public function updateProfile(Request $request)
    {
        $user= auth('api')->user();
        //$user= User::where('id',4)->first(); // from database
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|max:191|unique:users,email,'. $user->id,
            //eza moujoud nafs el email 3adi 3'air haik faild
            'password' => 'sometimes|required|min:6'
        ]);

        $currentPhoto = $user->photo;
       if ($request->photo != $currentPhoto) {
         $name= time().'.'. explode('/', explode(':', substr($request->photo,0, strpos($request->photo, ';')))[1])[1];

          \Image::make($request->photo)->save(public_path('img/profile/').$name);
          //take unique value(esem elswra bl folder profile) as a new value in the database
          $request->merge(['photo' => $name]);
         //if change the photo when select one the old deleted (el photo el e3meltelha save b profile folder)
         $userPhoto=public_path('img/profile/').$currentPhoto;
          if (file_exists($userPhoto)){
            @unlink($userPhoto);
          }
        }
        //to save el update
        //$name= time().'.'. explode('/', explode(':', substr($request->photo,0, strpos($request->photo, ';')))[1])[1];
       //\Image::make($request->photo)->save(public_path('img/profile/').$name);
       if (!empty($request->password)){
           //e3melt el if hay 3shan 3'ayart el password bdon ma ykon mshafar ma brda yfoot
        $request->merge(['password' =>Hash::make($request['password'])]);
       }
        //save information
        $user->update($request->all());  //change the value (all Request) in the database  
        return ['message' => "Success"];
    }
    public function profile()
    {   return auth('api')->user();        
        //return User::where('id',4)->first();        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { $user = User::findOrFail($id);
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|max:191|unique:users,email,'. $user->id,
            //eza moujoud nafs el email 3adi 3'air haik faild
            'password' => 'sometimes|min:6'
        ]);
        $user->update($request->all());
        return["message" => "updated user info"];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   $this->authorize('isAdmin');// limit la el user ma y3mal delete bas el admin y3mal deletebas eza admen b2dar y3mal delete
        
        $user = User::findOrFail($id);
        
        //Delete the User bwsal el equest bas ma b3mal delete hay el jomleh ele ta7et hyeh ele 3mlat el delete
        $user->delete();
        return["message" => "Deleted User"];
    }
    public function search(){
     if($search = \Request::get('q')){   
     $users= User::where(function($query) use ($search){
     $query ->where('name','LIKE',"%$search%")
     ->orWhere('email','LIKE',"%$search%")
     ->orWhere('type','LIKE',"%$search%");

     })->paginate(20);
    }else{
        $users = User::latest()->paginate(5);
    }
    return $users;
}}
