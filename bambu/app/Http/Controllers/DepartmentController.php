<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\support\Facades\Validator;
use App\Helpers\jwtAuthAdmin;
use Illuminate\Support\Facades\DB;
use App\Gender;
use App\Department;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $department = Department::all();
        $data = array(
            'departments' => $department,
            'status'  => 'success',
            'code'    => 200
        );
        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hash = $request->header('Authorization', null);
        $jwtAuthAdmin = new jwtAuthAdmin();
        $checkToken = $jwtAuthAdmin->checkToken($hash);
        if ($checkToken) {
            // recoger datos del POST
            $json =  $request->input('json', null);
            $params = json_decode($json);
            $paramsArray = json_decode($json,true);
            //validacion
            $validate = Validator::make($paramsArray, [
                'department'  => 'required',
                'positionDpt' => 'required',
                'gender_id'   => 'required'
            ]);
            if ($validate->fails()) {
                return response()->json($validate->errors(),400);
            }
            $department = new Department();
            $department->department = $params->department;
            $department->positionDpt = $params->positionDpt;
            $department->gender_id = $params->gender_id;

            $department->save();
            $data = array(
                'department' => $department ,
                'status'  => 'success',
                'code'    => 200,
            );
        } else {
            // Error
            $data = array(
                'message' => 'login incorrecto',
                'status' => 'Error',
                'code'  => 400,
            );
        }
        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $department = Department::find($id);
        return response()->json(array(
            'department' => $department,
            'status'   => 'success'
        ), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    {
        $hash = $request->header('Authorization', null);
        $jwtAuthAdmin = new jwtAuthAdmin();
        $checkToken = $jwtAuthAdmin->checkToken($hash);
        if ($checkToken) {
            // recoger datos del POST
            $json =  $request->input('json', null);
            $params = json_decode($json);
            $paramsArray = json_decode($json,true);
            //validacion
            $validate = Validator::make($paramsArray, [
                'department'  => 'required',
                'positionDpt' => 'required',
                'gender_id'   => 'required'
            ]);
            if ($validate->fails()) {
                return response()->json($validate->errors(),400);
            }
            unset($paramsArray['id']);
            unset($paramsArray['created_at']);
            $department = Department::where('id', $id)->update($paramsArray);

            $data = array(
                'department' => $department,
                'status'  => 'success',
                'code'    => 200
            );
        } else {
            //Error
            $data = array(
                'message' => 'login incorrecto',
                'status' => 'Error',
                'code'  => 400,
            );
        }

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = Department::find($id);
        $department->delete();
        $data = array(
            'department' => $department,
            'status'  => 'success',
            'code'    => 200
        );
        return response()->json($data, 200);
    }
}
