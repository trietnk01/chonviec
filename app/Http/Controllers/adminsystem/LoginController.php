<?php
namespace App\Http\Controllers\adminsystem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UserGroupMemberModel;
use App\GroupMemberModel;
use DB;
use Sentinel;
class LoginController extends Controller
{
    public function login(Request $request){                
        if($request->isMethod('post')){            
            Sentinel::authenticate($request->all());
            if(Sentinel::check()){                
                $user=Sentinel::getUser();                                
                $arrPrivilege=getArrPrivilege();                
                if(count($arrPrivilege) > 0){                                        
                    return redirect()->route('adminsystem.dashboard.getForm');  
                }else{
                    return view('adminsystem.login');
                }                    
            }else{
                return view('adminsystem.login');
            }      
        }else{            
            return view('adminsystem.login');
        }        
    }   
    public function logout(){
         Sentinel::logout();
         return redirect()->route('adminsystem.login');
    }
}
