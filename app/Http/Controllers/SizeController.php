<?php

namespace App\Http\Controllers;

use App\Models\size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    //
    public function index()
    {
        $size = Size::orderBy('id', 'DESC')->paginate();
        return view('backend.size.index')->with('sizes', $size);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.size.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'string|required',
            'abbreviation' => 'string|required',
        ]);
        $data = $request->all();
        // return $data;
        $status = Size::create($data);
        if ($status) {
            request()->session()->flash('success', 'size successfully created');
        } else {
            request()->session()->flash('error', 'Error, Please try again');
        }
        return redirect()->route('size.index');
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
        $size = Size::find($id);
        if (!$size) {
            request()->session()->flash('error', 'size not found');
        }
        return view('backend.size.edit')->with('size', $size);
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
        $size = Size::find($id);
        $this->validate($request, [
            'name' => 'string|required',
            'abbreviation' => 'string|required',
        ]);
        $data = $request->all();

        $status = $size->fill($data)->save();
        if ($status) {
            request()->session()->flash('success', 'size successfully updated');
        } else {
            request()->session()->flash('error', 'Error, Please try again');
        }
        return redirect()->route('size.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $size = Size::find($id);
        if ($size) {
            $status = $size->delete();
            if ($status) {
                request()->session()->flash('success', 'size successfully deleted');
            } else {
                request()->session()->flash('error', 'Error, Please try again');
            }
            return redirect()->route('size.index');
        } else {
            request()->session()->flash('error', 'size not found');
            return redirect()->back();
        }
    }
}
