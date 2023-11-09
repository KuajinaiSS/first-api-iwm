<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments=Appointment::get();
        return response()->json($appointments,200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $fields=$request->validate([
                'name'=>'required',
                'date'=>'nullable',
                'symptoms'=>'nullable',
                'user_id'=>'required'
            ]);

            $appointment=Appointment::create([
                'name'=>$fields['name'],
                'date'=>$fields['date'],
                'symptoms'=>$fields['symptoms'],
                'user_id'=>$fields['user_id']
            ]);
            DB::commit();
            return response()->json($appointment,200);
        }catch (\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        //
        try {
            return response()->json($appointment,200);
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        try{
            DB::beginTransaction();
            // valdiar
            $fields=$request->validate([
                'name'=>'nullable',
                'date'=>'nullable',
                'symptoms'=>'nullable',
                'user_id'=>'nullable'
            ]);

            // actualizar
            $appointment->update([
                'name'=>$fields['name']??$appointment->name,
                'date'=>$fields['date']??$appointment->date,
                'symptoms'=>$fields['symptoms']??$appointment->symptoms,
                'user_id'=>$fields['user_id']??$appointment->user_id
            ]);
            DB::commit();
            return response()->json($appointment,200);
        }catch (\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        try{
            DB::beginTransaction();
            $appointment->delete();
            DB::commit();
            return response()->json('Deleted success',200);
        }catch (\Exception $exception){
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }
    }
}
