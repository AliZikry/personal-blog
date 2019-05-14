<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreatePost;
use App\Http\Requests\UserUpdate;
use App\Charts\DashboardChart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\Comment;
use App\User;

class AdminController extends Controller
{
    //

    public function __construct(){
        $this->middleware('checkRole:admin');
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $chart = new DashboardChart;

        $days = $this->generateGateRange(Carbon::now()->subDays(30), Carbon::now());

        $posts = [];

        foreach ($days as $day) {
            # code...
            $posts[] = Post::whereDate('created_at', $day)->count();
        }

        $chart->dataset('Posts', 'line', $posts);
        $chart->labels($days);

        return view('admin.dashboard' , compact('chart'));
    }

    private function generateGateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];
        for ($date = $start_date; $date->lte($end_date); $date->addDay()) {
            # code...
            $dates[] = $date->format('Y-m-d');
        }
        return $dates;
    }

    public function posts()
    {
        $posts = Post::all();
        return view('admin.posts' , compact('posts'));
    }

    public function postEdit($id)
    {
        $post = Post::where('id' , $id)->first();

        return view('admin.editPost' , compact('post'));
    }

    public function postEditPost(CreatePost $request , $id)
    {
        $post = Post::where('id' , $id)->first();
        $post->title   = $request['title'];
        $post->content = $request['content'];
        $post->save();

        return back()->with('success', 'Post is successfully created.');
    }

    public function deletePost($id)
    {
        $post = Post::where('id' , $id)->first();
        $post->delete();

        return back();
    }

    public function comments()
    {
        $comments = Comment::all();
        return view('admin.comments' , compact('comments'));
    }

    public function deleteComment($id)
    {
        $comment = Comment::where('id' , $id)->first();
        $comment->delete();

        return back();
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users' , compact('users'));
    }

    public function editUser($id)
    {
        $user = User::where('id' , $id)->first();
        return view('admin.editUser' , compact('user'));
    }

    public function editUserPost(UserUpdate $request , $id)
    {
        $user = User::where('id' , $id)->first();
        $user->name  = $request['name'];
        $user->email = $request['email'];

        if ($request['author'] == 1) {
            # code...
            $user->author = true;
        }elseif ($request['admin'] == 1) {
            # code...
            $user->admin = true;
        }

        $user->save();

        return back()->with('success' , 'User updated successfully');
    }

    public function deleteUser($id)
    {
        $user = User::where('id' , $id)->first();
        $user->delete();

        return back();
    }
}
