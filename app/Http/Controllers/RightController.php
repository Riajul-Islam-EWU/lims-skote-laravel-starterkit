<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Right;
use Session;

class RightController extends Controller
{
    public function showRight()
    {
        $show_right_data = Right::all();
        // $show_right_data = Right::paginate(3);
        // $show_right_data = Right::simplePaginate(3);
        return view('right/show_right', compact('show_right_data'));
    }

    public function addRight()
    {
        return view('right/add_right');
    }

    public function saveRight(Request $request)
    {
        $right = new Right();

        $right->right_name = $request->right_name;
        $right->right_code = $request->right_code;

        if ($request->status == "on") {
            $request->status = "Active";
        } else {
            $request->status = "Inactive";
        }
        $right->status = $request->status;

        $right->save();

        Session::flash('msg', 'Right Created successfully!');

        // return $request->all();
        // return redirect()->back();
        return redirect('/show_right');
    }

    public function editRight($id = null)
    {
        $edit_right_data = Right::find($id);
        return view('right/edit_right', compact('edit_right_data'));
    }

    public function updateRight(Request $request, $id)
    {
        $right = Right::find($id);

        $right->right_name = $request->right_name;
        $right->right_code = $request->right_code;

        if ($request->status == "on") {
            $request->status = "Active";
        } else {
            $request->status = "Inactive";
        }
        $right->status = $request->status;

        $right->save();

        Session::flash('msg', 'Right\'s Data updated successfully!');

        // return $request->all();
        // return redirect()->back();
        return redirect('/show_right');
    }

    public function deleteRight($id = null)
    {
        $delete_right_data = Right::find($id);
        $delete_right_data->delete();

        Session::flash('msg', 'Right\'s Data deleted successfully!');

        return redirect('/show_right');
    }
}
