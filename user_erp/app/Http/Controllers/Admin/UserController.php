<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Exception;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class  Cow{
    public function  __construct($value){
     
   }
   
   }


/**
 * Created By : Imran
 * created date: 16-08-2023
 * Status : open
 */
class UserController extends Controller
{
    // private $_mUser;

    // public function __construct()
    // {
    //     DB::enableQueryLog();
    //     $this->_mUser = new User();
    // }
    
    public function reg(Request $req){
         //validation
         $validator = Validator::make($req->all(), [
            'userName' => 'required',
            'userEmail' => 'required',
            'userMobile'=>'required|digits:10',
            'userPassword' => 'required'
         
            // 'confirmPassword' => 'required|string'
        ]);
        if ($validator->fails()){
            return response()->json([
            "status"=>400,
            "message"=>"err4"
            
        ]);        
        }
        try{
            $getReqs = [
                'name' => Str::title($req->userName),
                'email' => $req->userEmail, 
                'mobile'=> $req->userMobile,              
                'password' => Hash::make($req->userPassword),
                'ip_address' => $_SERVER['REMOTE_ADDR']
            ];
            $getReqs = array_merge($getReqs, [
                'json_logs' => trim(json_encode($getReqs), ",") //what we have stored
            ]);
             //dd($getReqs);
            User::insertData($getReqs);
            $data = ["name"=> $req->userName, "email"=>$req->userEmail];
            return response()->json(["status"=>200,"data"=>$data,"message"=>"Registration Done Successfully"]);            
            } 
        catch (Exception $e) {
            return response()->json(["status"=>400,"message"=>"Data not found",
                                      "excpetion is:"=>$e
         ]);   
        }
        
    }

    public function login(Request $req)
    {


        $validator = Validator::make($req->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails())
            return responseMsgs(false, $validator->errors(), []);

            
            $mUser = User::where('email', $req->email)->first();  
               $modalobject= new User();

             $usercollectionobject=collect(User::select('*')->get()); 
            // $oo=$usercollectionobject->where('email',$req->email)->pluck('status'); 
              
             
              //dd($oo);                        
            // dd($usercollectionobject->pluck('name'));  
            //if($mUser->status===0) return response()->json(['message'=>'records already deleted']);
            //if($oo[0]===0) return response()->json(['message'=>'records already deleted']);
            //else return response()->json(['message'=>'ppppppppppppppppppppppp']);

         
            if (!$mUser) {
                $msg = "Oops! Given user does not exist";
                return response()->json(['message'=>'email is not valid ']);
            }
            
        
            if ($mUser && Hash::check($req->password, $mUser->password)) {
                $token = $mUser->createToken('auth_token')->plainTextToken;
                $mUser->remember_token = $token;
                $mUser->save();
                $data1 = ['name' => $mUser->name, 'email' => $mUser->email, 'token' => $token, 'token_type' => 'Bearer'];
             } else
                throw new Exception("Password is incorrect");
         
            if (!$data1)
                throw new Exception("Record Not Found!");

                return response()->json(["status"=>200,"data"=>$data1,"message"=>"Login Successfully","apiNo"=>"1.1"]);            
        
    
    }


    public function logout(Request $req)
    { //client logout 1.3
          // $user=$req->user();//geting autherized user details
          $user=Auth::user();
          dd($user);

         if($user->currentAccessToken())
        {
        $user->currentAccessToken()->delete();
        return response()->json(["message"=>" Logged out successfully "]);
        }
    } 


    public function changePassword(Request $req){

        $req->validate([
                "current_password"=>"required",
                "new_password"=>"required",
                ]);
       
        $user=Auth::user();
        if($user->status===0) return response()->json(['message'=>'not valid record found ']);

        $version=$user->version_no;
       
        if(!Hash::check($req->current_password,$user->password)){
       //1.4
        return response()->json([
        "issue"=>"Your Current Password not matched"
        ]);
        }

        try{
                    $user->update([
                    "password"=>Hash::make($req->new_password)
                    ]);
                    $version=$version+1;
                    $user->version_no= $version;
                    $user->save();
                    return response()->json([
                    "message"=>"Your password has been reset"
                    ]);
       
        }
        catch(Exception $e){
        return response()->json([
        "issue"=>"something went wrong".$e
        ]);
        }
        }

        public function deleteProfile(Request $req){
           
            $user=$req->user(); 
             if($user->status===0) return response()->json(['message'=>'not valid record found ']);
           
        
            $name = $user->name;      
            try{
                
                $user->status=0;
                $user->save();
                return response()->json([
                    "message"=>"Hello"." ".$name." "."Your details has been deleted"
                ]);
            }catch(\Exception $e){
                return response()->json([
                    "issue"=>"Something went wrong",
                    "exception"=>$e
                ],500);
            }
        }



        public function testing (Request $req)
          {
            $sorted_name_array =
            collect(User::select('name')->distinct()->get('name'))->sortBy('name')->pluck('name');
            
              
             $left= 0;
             $right=count($sorted_name_array)-1;
             while($left <= $right){
             $mid= $left + floor( $right-$left/2);
             $comp=strcasecmp($sorted_name_array[$mid], $req->name);
             if($comp===0 ){return "Your Name found at key index "." ".$mid ;}
             elseif($comp < 0 ) { $mid = $left+1 ;}
          
             elseif ($comp > 0 ) { $right= $mid-1 ;} 
      
            
             }
             return "your name not found ";
     
             //return response()->json(['message'=> gettype($req->name) ]);

           //  return response()->json(['message'=> $sorted_name_array ]);
           
               
           

         
           
     
  


             //return response()->json(['message'=> $result ]);

    

           

             
               

 

           

                
                 

        }

        public function insertmulti(){
             
            try {
                
                $file_path = 'C:\drupel joyti\laravel_project\user_erp\app\Http\Controllers\Admin\m.txt'; // Replace with the actual path to your file
     
             
                $file_contents = File::get($file_path);
                //dd($file_contents);
                $rr=json_decode($file_contents);
                
                  //dd(is_array($rr));
                 for($i=0;$i<count($rr);$i++)
 

                 {
                    
                    
                    //dd((array)$rr[$i]);
                     $array=(array)$rr[$i];

                  
                 
                    User::create($array);

                 }
               
                echo $file_contents;
            } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $exception) {
               
                echo "Error reading the file: " . $exception->getMessage();
            }


        }



  
      
      





}




