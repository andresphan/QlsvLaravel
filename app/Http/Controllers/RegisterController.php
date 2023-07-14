<?php

namespace App\Http\Controllers;

use App\Models\Register;
use App\Models\Student;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    function __construct() {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input("search");
        $itemPerPage = env("ITEM_PER_PAGE", 2);
        $registers = Register::join('students', 'students.id', '=', 'registers.student_id') 
            ->join('subjects', 'subjects.id', '=', 'registers.subject_id') 
            ->select('registers.*')
            ->where("students.name", "LIKE", "%$search%") 
            ->orwhere("subjects.name", "LIKE", "%$search%") 
            ->orderBy("students.name", "ASC")
            ->orderBy("subjects.name", "ASC")
            ->paginate($itemPerPage)
            ->withQueryString();
        return view('register.index',["registers" => $registers, "search" => $search]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $students = Student::all();
        return view('register.create', ["students" => $students]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->all();
        $register = new Register();
        $register->student_id = $data["student_id"];
        $register->subject_id = $data["subject_id"];
        $register->save();
        $request->session()->put("success", "Sinh viên {$register->student->name} đăng ký môn {$register->subject->name} thành công");
        return redirect()->route("registers.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Register  $register
     * @return \Illuminate\Http\Response
     */
    public function show(Register $register)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Register  $register
     * @return \Illuminate\Http\Response
     */
    public function edit(Register $register)
    {
        //
        return view('register.edit', ["register" => $register]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Register  $register
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Register $register)
    {
        //
        $data = $request->all();
        $register->score = $data["score"];
        $register->save();
        $request->session()->put("success", "Đã cập nhật điểm sinh viên {$register->student->name} học môn {$register->subject->name} thành công");
        return redirect()->route("registers.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Register  $register
     * @return \Illuminate\Http\Response
     */
    public function destroy(Register $register)
    {
        //
         try {
            $register->forceDelete();
            request()->session()->put('success', "Đã xóa sinh viên {$register->student->name} đăng ký môn {$register->subject->name} thành công");
        }catch(QueryException $e) {
            request()->session()->put('error', $e->getMessage());
        }
        return redirect()->route("registers.index");
    }
}
