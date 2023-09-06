<?php

namespace App\Http\Controllers\Chat;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ChatUser\ChatUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Exception;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use App\Models\ChatUser\ChatMsg;





class ChatController extends Controller
{
    
   public function chatreg(Request $req)  {

  try{
           $myrandom =collect(['a','b','c','d','e','f','g','h','i','j','k','l','m','n',
      'o','p','q','r','s','t','u','v','w','z','y','z',
      ])->random(2)->join('');
   
    $username= $req->name.rand(1,100).$myrandom;
            

    $validator = Validator::make($req->all(), [
        'name' => 'required|string|min:3',
        'mobile' => 'required',
        'password'=>'required|string',
        'designation' => 'required|string'
        
       
    ]);             
 

    if ($validator->fails()){
        return response()->json([
        "status"=>400,
        "message"=>"err4"
        
    ]);
}     


        


    

            $getReqs = [
                   'name' => Str::title($req->name),
                   'mobile'=> $req->mobile,              
                   'password' => Hash::make($req->password),
                    'designation'=>$req->designation,
                   'username'=>$username,
           
            ];
        
          $output=collect( $getReqs)->except('password');
                
            ChatUser::create($getReqs);
            $data = ['your details'=> $output];
            return response()->json(["status"=>200,"data"=>$data,"message"=>"Registration Done Successfully"]);    





  }catch(\Exception $e){return response()->json(['Error msg'=>$e]);}

           }//fun



           public function chatlogin(Request $req)
           {
              
               $validator = Validator::make($req->all(), [
                   'username' => 'required',
                   'password' => 'required'
               ]);


               if ($validator->fails())
                   return response()->json( $validator->errors());
                
                      $modalobject= new ChatUser();
                
                 
                         $mUser= $modalobject->where('username',$req->username)->first();
                     
                     
       
            

                   if (!$mUser) {
                       $msg = "Oops! Given user does not exist";
                       return response()->json(['message'=>'email is not valid ']);
                   }
                   
                   //check if user and password is existing  
                   if ($mUser && Hash::check($req->password, $mUser->password)) {
                       $token = $mUser->createToken('auth_token')->plainTextToken;
                       $mUser->remember_token = $token;
                       $mUser->save();
                       $data1 = ['name' => $mUser->name,  'token' => $token, 'token_type' => 'Bearer'];
                    } else
                       throw new Exception("Password is incorrect");
                   // $queryTime = collect(DB::getQueryLog())->sum("time");
                   if (!$data1)
                       throw new Exception("Record Not Found!");
                       $freind= $modalobject->select('username')->where('username','!=',$req->username)->get();
                  

                       return response()->json(["status"=>200,"data"=>$data1,"message"=>"Login Successfully",'freind list'=>$freind]);            
       
          
           }



             public function  showmsg(Request $req){
                  

                try{
                     
                    
                 
                    

                    $username=$req->username;
                    $userid=ChatUser::select('id')->where('username',$req->sender_name)->first();
                    $userid_rec=ChatUser::select('id')->where('username',$req->receiver_name)->first();
                   
                     //dd($userid_rec);


                          $msgmodal = new ChatMsg();
                    
                         $data=[

                       
                   
                          'msg'   =>   $req->message,
                          'sender_id' => $req->sender_id,
                           'receiver_id'=>$req->receiver_id,
                           'con_id'=> $req->sender_id."-".$req->receiver_id,
                         ];

                        if($msgmodal->create($data)) 
                        { return response()->json(['message'=>'converstation strated between'." ". $data['sender_id'] 
                            ."and".$data['receiver_id'] ,
                             'your conversation id  is :'=>$data['con_id']
                        
                        
                        ]);}
                        else {return response()->json("some thing went wrong ");}

                      
                     

                }
                
                
                
                catch(Exception $e){ return response()->json(["error"=>$e]);}
             
                      
             }



          public function chatget(Request $req){

          
                
                $msgmodal = new ChatMsg();

               $foo =$msgmodal->select('msg')->where('con_id',$req->chat_id)->get();
                  
                return collect($foo)->toArray();
          }










   }//class



