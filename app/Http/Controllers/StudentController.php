<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentExport;
use App\Imports\StudentImport;
class StudentController extends Controller
{
    function __construct() {
        $this->middleware(['auth', 'verified']);
    }

    protected $pattern = [
        'name' => 'required|regex:/^[a-zA￾ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộ
        ớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s]+$/i|max:50',
        'birthday' => 'required|date',
        'gender' => 'bail|required|numeric|between:0,2',
    ];
    protected $messenger = [
        'required' => ':attribute không được để trống',
        'regex' => ':attribute không được chứa số hoặc ký tự đặc biệt',
        'date' => ':attribute không nhập sai định dạng ngày tháng năm',
        'max' => ':attribute không được lớn hơn :max ký tự',
        'between' => ':attribute phải là số từ :min đến :max',
        'numeric' => ':attribute phải là số',
    ];
    protected $customName = [
        'name' => 'Tên',
        'birthday' => 'Ngày sinh',
        'gender' => 'Giới tính',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $search = $request->input("search");
        $itemPerPage = env("ITEM_PER_PAGE", 2);
        $students = Student::where("name", "LIKE", "%$search%")->paginate($itemPerPage)->withQueryString();
        return view('student.index', ["students" => $students, "search" => $search]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('student.create');
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
        $this->validate($request, $this->pattern, $this->messenger, $this->customName);
        $data = $request->all();
        $student = new Student();
        $student->name = $data["name"];
        $student->birthday = $data["birthday"];
        $student->gender = $data["gender"];
        $student->save();
        $request->session()->put("success", "Đã tạo sinh viên {$student->name} thành công");
        return redirect()->route("students.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
        return view('student.edit', ["student" => $student]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        //
        $this->validate($request, $this->pattern, $this->messenger, $this->customName);
        $data = $request->all();
        $student->name = $data["name"];
        $student->birthday = $data["birthday"];
        $student->gender = $data["gender"];
        $student->save();
        $request->session()->put("success", "Đã update {$student->name} thành công");
        return redirect()->route("students.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
        try {
            $student->forceDelete();
            request()->session()->put('success', "Đã xóa sinh viên {$student->name} thành công");
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                request()->session()->put('error', "Sinh viên {$student->name} đã đăng ký môn học, bạn không thể xóa");

            } else {
                request()->session()->put('error', $e->getMessage());
            }
        }
        return redirect()->route("students.index");

    }

    public function formImport() {
        return view('student.formimport');
    }

    public function import() 
    {
        Excel::import(new StudentImport, request()->file('excel'));
	    return redirect()->route("students.index");

    }

    public function export()
    {
        return Excel::download(new StudentExport, 'StudentList.xlsx');
    }

}
