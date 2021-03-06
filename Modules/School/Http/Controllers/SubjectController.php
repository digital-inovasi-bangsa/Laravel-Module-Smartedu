<?php

namespace Modules\School\Http\Controllers;

use Modules\School\Http\Requests\SubjectRequest;
use Modules\School\Transformers\SubjectResource;
use Modules\School\Entities\Subject;
use Illuminate\Routing\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return SubjectResource::collection(Subject::where('school_id', auth('school')->id())->with(['major', 'group'])->get());
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(SubjectRequest $request)
    {
        Subject::create(
            array_merge(
                $request->all(),
                [
                    'school_id' => auth('school')->id()
                ]
            )
        );
        return SubjectResource::collection(Subject::all());
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('school::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('school::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $result = $subject->fill($request->all())->save();

        if ($result) {
            return response()->json(['success' => 'Berhasil Mengubah Subject'], 200);
        } else {
            return response()->json(['error' => 'Gagal Mengubah Subject'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $subject = Subject::find($id);
        if ($subject) {
            $result = $subject->delete();
            if ($result) {
                return response()->json(['success' => 'Berhasil Menghapus Subject'], 200);
            } else {
                return response()->json(['error' => 'Gagal Menghapus Subject'], 400);
            }
        } else {
            return response()->json(['error' => "Data dengan id $id tidak ada"], 400);
        }
    }
}
