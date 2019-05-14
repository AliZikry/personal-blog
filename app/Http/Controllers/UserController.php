<?php

namespace App\Http\Controllers;
use App\Comment;

use Illuminate\Http\Request;
use App\Http\Requests\UserUpdate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Charts\DashboardChart;
use Carbon\Carbon;

class UserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $chart = new DashboardChart;

        $days = $this->generateGateRange(Carbon::now()->subDays(30) , Carbon::now());

        $comments = [];

        foreach ($days as $day) {
            # code...
            $comments [] = Comment::whereDate('created_at' , $day)->where('user_id' , Auth::id())->count();
        }

        $chart->dataset('Comments' , 'line' , $comments);
        $chart->labels($days);

        return view('user.dashboard' , compact('chart'));
    }

    private function generateGateRange(Carbon $start_date , Carbon $end_date)
    {
        $dates = [];
        for ($date=$start_date; $date->lte($end_date) ; $date->addDay()) {
            # code...
            $dates [] = $date->format('Y-m-d');
        }
        return $dates;
    }

    public function comments()
    {
        return view('user.comments');
    }

    public function profile()
    {
        return view('user.profile');
    }

    public function profilePost(UserUpdate $request)
    {
        $user = Auth::user();

        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->save();

        if ($request['password'] != "") {
            # code...
            if (!(Hash::check($request['password'] , Auth::user()->password))) {
                # code...
                return redirect()->back()->with('error' , "Your current password does not match with the one you provided.");
            }

            if (strcmp($request['password'] , $request['new_password']) == 0) {
                # code...
                return redirect()->back()->with('error', "Your new password cannot be the same as the current password.");
            }

            $validation = $request->validate([
              'password' => 'required',
              'new_password' => 'required|string|min:6|confirmed'
            ]);

            $user->password = bcrypt($request['new_password']);
            $user->save();

            return redirect()->back()->with('success' , "Password changed Successfully.");

        }

        return back();
        // dump($request->all());
    }

   public function deleteComment($id)
   {
        $comment = Comment::where('id' , $id)->where('user_id' , Auth::id())->delete();
        return back();
   }

   public function newComment(Request $request)
   {
        $comment = new Comment;
        $comment->post_id = $request['post'];
        $comment->user_id = Auth::id();
        $comment->content = $request['comment'];
        $comment->save();
        return back();
   }
}
