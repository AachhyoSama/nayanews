<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Province;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting = Setting::first();
        $provinces = Province::all();
        $district = District::where('id', $setting->district_id)->first();
        $district_group = District::where('province_id', $district->province_id)->get();
        return view('backend.setting', compact('setting', 'provinces', 'district_group'));
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request['company_logo']);
        $setting = Setting::findorFail($id);

        $this->validate($request, [
            'company_name' => 'required',
            'company_email' => 'required',
            'company_phone' => 'required',
            'province' => 'required',
            'district' => 'required',
            'address' => 'required',
            'company_logo' => 'mimes:jpeg,jpg,png',
        ]);

        $imagename = '';
        if($request->hasfile('company_logo')) {
            $image = $request->file('company_logo');
            $imagename = $image->store('company_logo', 'uploads');
            $setting->update([
                'logo' => $imagename,
            ]);
        }

        $setting->update([
            'company_name' => $request['company_name'],
            'company_email' => $request['company_email'],
            'company_phone' => $request['company_phone'],
            'province_id' => $request['province'],
            'district_id' => $request['district'],
            'address' => $request['address'],
        ]);

        return redirect()->back()->with('success', 'Setting information successfully updated.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
