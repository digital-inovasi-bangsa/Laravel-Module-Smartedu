<?php

namespace Modules\School\Http\Controllers;

use Modules\Smartedu\Transformers\ParticipantResource;
use Modules\School\Http\Requests\ParticipantRequest;
use Illuminate\Support\Facades\Validator;
use Modules\School\Entities\Participant;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Hash;

class ParticipantController extends Controller
{
    public function index()
    {
        return ParticipantResource::collection(Participant::with(['group','major','room', 'school'])->where('school_id', auth('school')->id())->get());
    }

    public function Login(ParticipantRequest $request)
    {
        return Participant::login($request);
    }

    public function store(Request $request)
    {
        $add = new Participant;

        $add->name = $request->name;
        $add->nisn = $request->nisn;
        $add->visible = $request->password;
        $add->password = $request->password;
        $add->group_id = $request->group_id;
        $add->major_id = $request->major_id;
        $add->room_id = $request->room_id;
        $add->school_id = $request->school_id;

        $result =  $add->save();
        if ($result) {
            return response()->json(['success' => 'Berhasil Menambah Participant'], 200);
        } else {
            return response()->json(['error' => 'Gagal Menambah Participant'], 400);
        }
    }

    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'name'                  => 'required',
            'nisn'                  => 'required',
            'password'              => 'required|max:6',
            'group_id'              => 'required',
            'major_id'              => 'required',
            'room_id'               => 'required',
        ]);
        $participant = Participant::where('id', $id)
        ->update([
            'name' => $request->name,
            'nisn' => $request->nisn,
            'visible' => $request->password,
            'password' => Hash::make($request->password),
            'group_id' => $request->group_id,
            'major_id' => $request->major_id,
            'room_id' => $request->room_id,
        ]);
        return ParticipantResource::collection(Participant::all());
    }

    public function deleteAllBysekolah($id)
    {
        $participant = Participant::where('school_id', $id)->delete();
        if ($participant) {
            return response()->json(['success' => 'Berhasil dihapus'], 200);
        } else {
            return response()->json(['error' => 'Not Found'], 404);
        }
    }

    public function deleteParticipant($id)
    {
        $participant = Participant::find($id);
        if ($participant) {
            $participant->delete();
            return response()->json(['success' => 'Berhasil dihapus'], 200);
        } else {
            return response()->json(['error' => 'Not Found'], 404);
        }
    }
}
