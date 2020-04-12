<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Response;
use App\User;
use App\QuizLog;
use Hash;
use App\Employee;

class MasterController extends Controller
{
    public function index(){
        return view('home');
    }

    public function corona(){
        return view('kuisioner_corona');
    }

    public function auth(Request $request){

        $user = $request->get('username');
        $pass = $request->get('password');

        $asd = Hash::check('dd', bcrypt($pass));

	        // if ($asd == true) {
	        // 	$asd = 'aw';
	        // }
	        // else{
	        // 	$asd = '412';
	        // }

	        // var_dump($asd);die();

	        $login = User::where('username','=', $user)
	        ->first();

	        if ($login == null) {
	          return 'username atau password salah';

	      } else{
	          if ($login == true) {
	           return view('kuisioner');
	       }
	       else{
	           return 'password salah';
	       }

   		}

	}

	public function checkEmployeeId(Request $request)
	{
	    try {
	        $cek = db::select("SELECT
			    employee_id,
			    name,
			    department,
                password
			FROM
			    `employees` 
			WHERE
		    `employee_id` = '".$request->get('employee_id')."' and
            `password` = '".$request->get('password')."' 
		    AND `end_date` IS NULL");

	        $response = array(
	            'status' => true,
	            'data' => $cek
	        );
	        return Response::json($response);
	    } catch (QueryException $e) {
	        $response = array(
	            'status' => false,
	            'message' => $e->getMessage()
	        );
	        return Response::json($response);
	    }
	}

    public function resetPassword(Request $request)
    {
        try {
            if ($request->get('password') != $request->get('password2')) {
                $response = array(
                    'status' => false,
                );                
            }else{
                $employee = Employee::where('employees.employee_id', '=', $request->get('employee_id'))
                ->first();
                $employee = db::table('employees')->where('employees.employee_id', '=', $request->get('employee_id'))->update([
                    'password' => $request->get('password')
                ]);

                $response = array(
                    'status' => true,
                    'data' => $employee
                );
                return Response::json($response);
            }
        } catch (\Exception $e) {
            $response = array(
                'status' => false,
                'message' => $e->getMessage()
            );
            return Response::json($response);
        }
    }

	


	public function postForm(Request $request)
    {
        try {

   //       	$cek = db::select("SELECT employee_id,`answer_date` FROM `quiz_logs` 
			// WHERE
		 //    `employee_id` = '".$request->get('employee_id')."' 
		 //    order by id DESC LIMIT 1");



		 //    if ($cek) {
		 //    	if ($cek[0]->answer_date == date('Y-m-d')) {
		 //    		$stat = 0;
		 //    	}
		 //    	else{
		 //    		$stat = 1;
		 //    	}
		 //    } 
		 //    else {
		 //    	$stat = 1;
		 //    }

		    // if ($stat == 1) {
		    	$quiz = $request->get('question');
	         	$answer = $request->get('answer');

		        $jumlah_pertanyaan = $request->get('jumlah_pertanyaan');

		        for ($i=0; $i < $jumlah_pertanyaan+1; $i++) { 
		              $forms = QuizLog::create([
		               'question_code' => 'corona-2',
		               'answer_date' => date('Y-m-d'),
		               'employee_id' => $request->get('employee_id'),
		               'name' => $request->get('name'),
		               'department' => $request->get('department'),
		               'question' => $quiz[$i],
		               'answer' => $answer[$i],
                       'latitude' => $request->get('latitude'),
                       'longitude' => $request->get('longitude'),
		              ]);

		              $forms->save();                  
		        }

		        $response = array(
		            'status' => true,
		            'datas' => 'Berhasil Input Data',
		        );
		        return Response::json($response);
		    // }
		    // else{
		    // 	$response = array(
		    //         'status' => false,
		    //         'datas' => 'Anda Sudah Mengisi Laporan Untuk Hari Ini',
		    //     );
		    //     return Response::json($response);
		    // }

         } catch (QueryException $e){
             $error_code = $e->errorInfo[1];
            	if($error_code == 1062){
              		$response = array(
	                   'status' => false,
	                   'datas' => 'Anda Sudah Mengisi Laporan Untuk Hari ini'
	             	);
	             	return Response::json($response);
            	}
            	else{
              		$response = array(
	                   'status' => false,
	                   'datas' => $e->getMessage()
	             	);
	             	return Response::json($response);
            	}
         }
    }

}
