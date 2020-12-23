<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.admin.index');
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
        //
        $request->validate([
            'username' => 'required|min:5|unique:admins,username,',
            'password' => 'required|min:5'
        ]);
        try {
            $data = Admin::create([
                'username' => $request->username,
                'password' => $request->password
            ]);

            return ([
                'status' => 'success',
                'data' => $data,
                'message' => 'Admin Berhasil Dimasukkan'
            ]);
        } catch (\Throwable $th) {
            return ([
                'status' => 'success',
                'data' => null,
                'message' => $th->getMessage()
            ]);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'username' => 'required|min:5|unique:admins,username,'.$admin->id,
            'password' => 'sometimes|nullable|min:5'
        ]);
        try {
            $request->password==null ? $data = $request->only(['username']) : $data = $request->all();
            $admin->update($data);
            return ([
                'status' => 'success',
                'data' => $admin,
                'message' => 'Admin Berhasil Diubah'
            ]);
        } catch (\Throwable $th) {
            return ([
                'status' => 'success',
                'data' => null,
                'message' => $th->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        try {
            $admin->delete();
            return response()->json([
                'status' => 'success',
                'data' => null,
                'message' => 'Admin Sukses Di Hapus.'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'failed',
                'data' => null,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function get_admin(){
        $data = Admin::all();
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => 'Data Sukses Di Dapatkan.'
        ]);
    }
    public function reset_session($id){
        $admin = Admin::find($id);
        $user_session = $admin->auth_session;
        if($user_session){
            $admin->update([
                'status' => 0,
                'auth_session' => null
            ]);
            Session::getHandler()->destroy($user_session);
            return ([
                'status' => 'success',
                'data' => null,
                'message' => 'Akun Admin Berhasil Di Reset'
            ]);
        }
        return ([
            'status' => 'failed',
            'data' => null,
            'message' => 'Sesi Admin Tidak Ditemukan.'
        ]);
    }
}
