<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DailyScrum;   
use Illuminate\Support\Facades\Validator;
use JWTAuth;

use DB;



class DailyScrumController extends Controller
{

    public function index()
    {
			// $user = JWTAuth::users()->daily_scrum();
    	try{
	        $data["count"] = DailyScrum::count();
            $daily_scrum = array();
            
            $userid = DB::table('daily_scrum')->join('users','users.id','=','daily_scrum.id_users')
                                                ->select('daily_scrum.id_users')
                                                ->get();


	        foreach (DailyScrum::all() as $p) {
	            $item = [
                    "id"                    => $p->id,
	                // "id_users"              => $p->id_users,      
	                "id_users"              => JWTAuth::user()->id,            
	                "team"                  => $p->team,
	                "nama_siswa"            => $p->nama_siswa,
                    "activity_yesterday"    => $p->activity_yesterday,
	                "activity_today"    	=> $p->activity_today,
                    "problem_yesterday"    	=> $p->problem_yesterday,
                    "solution"    	        => $p->solution,                              
	                "created_at"            => $p->created_at,
                    "updated_at"            => $p->updated_at,
                    "tanggal" 			    => date('d-m-y', strtotime($p->created_at)),
	            ];

	            array_push($daily_scrum, $item);
	        }
	        $data["daily_scrum"] = $daily_scrum;
	        $data["status"] = 1;
	        return response($data);

	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
    }

    public function getAll($limit = 10, $offset = 0)
    {
    	try{				
				$data["count"] = DailyScrum::count();
				$daily_scrum = array();

				// $userid = DB::table('daily_scrum')->join('users','users.id','=','daily_scrum.id_users')
				// 																				->select('daily_scrum.id_users')
        //                                         ->get();
				// $daily_scrum = JWTAuth::user()->daily_scrum();
							// if ($data = JWTAuth::user()->id()) {

	        foreach (DailyScrum::take($limit)->skip($offset)->get() as $p) {
	            $item = [
	                "id"                    => $p->id,
	                "id_users"              => $p->id_users,             
	                // "id_users"              => JWTAuth::user()->id,            
	                "team"                  => $p->team,
	                "nama_siswa"            => $p->nama_siswa,
                    "activity_yesterday"    => $p->activity_yesterday,
	                "activity_today"    	=> $p->activity_today,
                    "problem_yesterday"    	=> $p->problem_yesterday,
                    "solution"    	        => $p->solution,                              
	                "created_at"            => $p->created_at,
                    "updated_at"            => $p->updated_at,
                    "tanggal" 			    => date('d-m-y', strtotime($p->created_at)),  
							];
							
							// $daily_scrum = JWTAuth::user()->data();

	            array_push($daily_scrum, $item);
					}
					
					$data = DailyScrum::findorFail($p->id);
					$data["daily_scrum"] = $daily_scrum;
					
					$data["status"] = 1;



					// $data = JWTAuth::user()->daily_scrum();
					
					// if ($data->id_users == JWTAuth()->id()) {
					// }

					if ($data->id_users == auth()->id()) {
						return response($data);
					}
					// $data = JWTAuth::user()->id;
	        
				
	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
    }

    public function store(Request $request)
    {
      try{
    		$validator = Validator::make($request->all(), [
    		'team'                      => 'required|string',
				'activity_yesterday'		=> 'required|string|max:255',
        'activity_today'			=> 'required|string|max:255',
				'problem_yesterday'			=> 'required|string|max:255',
				'solution'			        => 'required|string|max:255',
                
    		]);

    		if($validator->fails()){
    			return response()->json([
    				'status'	=> 0,
    				'message'	=> $validator->errors()
    			]);
    		}

            $data = new DailyScrum();  
	        $data->id_users = JWTAuth::user()->id;
	        $data->team = $request->input('team');
	        $data->activity_yesterday = $request->input('activity_yesterday');
            $data->activity_today = $request->input('activity_today');
	        $data->problem_yesterday = $request->input('problem_yesterday');
	        $data->solution = $request->input('solution');
	        $data->save();

    		return response()->json([
    			'status'	=> '1',
    			'message'	=> 'Hore! Data Scrum berhasil kamu tambahkan!'
    		], 201);

      } catch(\Exception $e){
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ]);
        }
      }
      
      public function delete($id)
    {
        try{

            $delete = DailyScrum::where("id", $id)->delete();
            if($delete){
              return response([
              	"status"	=> 1,
                  "message"   => "Datanya dihapus? Wah.. ada yang udah selesai nih..."
              ]);
            } else {
              return response([
                "status"  => 0,
                  "message"   => "Yah... Datanya gagal dihapus nih... "
              ]);
            }
        } catch(\Exception $e){
            return response([
            	"status"	=> 0,
                "message"   => $e->getMessage()
            ]);
        }
    }

    // public function index(){
    //     $data = "Data semua Daily Scrum";
    //     return response()->json($data);
    // }

    // public function getAll(){
    //     $data = "Selamat Datang, " . Auth::user()->Firstname;
    //     return response()->json($data);
    // }
}
