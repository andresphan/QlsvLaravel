<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class SubjectController extends Controller
{
    function __construct() {
        $this->middleware(['auth', 'verified']);
    }

    protected $pattern = [
        'name' => 'required|regex:/^[a-zA￾ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộ
        ớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s]+$/i|max:50',
        'number_of_credit' => 'required|numeric|between:1,10',
        ];
    protected $messenger = [
        'required' => ':attribute không được để trống',
        'regex' => ':attribute không được chứa số hoặc ký tự đặc biệt',
        'max' => ':attribute không được lớn hơn :max ký tự',
        'between' => ':attribute phải là số từ :min đến :max',
        'numeric' => ':attribute phải là số',
        ];
    protected $customName = [
        'name' => 'Tên',
        'number_of_credit' => 'Số tín chỉ'
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
        $subjects = Subject::where("name", "LIKE", "%$search%")->paginate($itemPerPage)->withQueryString();
        return view('subject.index', ["subjects" => $subjects, "search" => $search]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('subject.create');
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
        $this->validate($request,$this->pattern, $this->messenger, $this->customName);
        $data = $request->all();
        $subject = new Subject();
        $subject->name = $data["name"];
        $subject->number_of_credit = $data["number_of_credit"];
        $subject->save();
        $request->session()->put("success", "Đã tạo môn học {$subject->name} thành công");
        return redirect()->route("subjects.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        //
        return view('subject.edit', ["subject" => $subject]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        //
        $this->validate($request,$this->pattern, $this->messenger, $this->customName);
        $data = $request->all();
        $subject->name = $data["name"];
        $subject->number_of_credit = $data["number_of_credit"];
        $subject->save();
        $request->session()->put("success", "Đã update {$subject->name} thành công");
        return redirect()->route("subjects.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        //
        try {
            $subject->forceDelete();
            request()->session()->put('success', "Đã xóa môn học {$subject->name} thành công");
        }catch(QueryException $e) {
            if ($e->getCode() == 23000) {
                request()->session()->put('error', "Môn học {$subject->name} đã đăng ký môn học, bạn không thể xóa");
           
            }
            else {
                request()->session()->put('error', $e->getMessage());
            } 
        }
        return redirect()->route("subjects.index");
        
    }

    function unregistered($id) {
        // $id = $request->input("id");
        $subjects = Subject::whereNotIn('id',function($query) use($id){
            $query->select('subject_id')->from('registers')
                          ->where('student_id','=', $id);
            })->get();
        $notUnregisteredSubjects = [];
        foreach ($subjects as $subject):
            $notUnregisteredSubjects[] = ["id" => $subject->id, "name" => $subject->name];
        endforeach;
        echo json_encode($notUnregisteredSubjects);
            
    }
}
