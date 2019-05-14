<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreatePost;
use Illuminate\Support\Facades\Auth;
use App\Charts\DashboardChart;
use Carbon\Carbon;
use App\Post;
use App\Comment;
class AuthorController extends Controller
{
    //
     public function __construct(){
        $this->middleware('checkRole:author');
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $chart = new DashboardChart;

        $days = $this->generateGateRange(Carbon::now()->subDays(30), Carbon::now());

        $posts = [];

        foreach ($days as $day) {
            # code...
            $posts[] = Post::whereDate('created_at', $day)->where('user_id', Auth::id())->count();
        }

        $chart->dataset('Posts', 'line', $posts);
        $chart->labels($days);

        return view('author.dashboard' , compact('chart'));
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
        return view('author.posts');
    }

    public function comments()
    {
        $posts = Post::where('user_id' , Auth::id())->pluck('id')->toArray();
        $comments = Comment::where('post_id' , $posts)->get();
        return view('author.comments' , compact('comments'));
    }

    public function newPost()
    {
        return view('author.newPost');
    }

    public function createPost(CreatePost $request)
    {
        $post = new Post();
        $post->title = $request['title'];
        $post->content = $request['content'];
        $post->user_id = Auth::id();
        $post->save();

        return back()->with('success' , 'Post is successfully created.');
    }

    public function postEdit($id)
    {
        $post = Post::where('id' , $id)->where('user_id' , Auth::id())->first();

        return view('author.editPost' , compact('post'));
    }

    public function postEditPost(CreatePost $request , $id)
    {
        $post = Post::where('id' , $id)->where('user_id' , Auth::id())->first();
        $post->title   = $request['title'];
        $post->content = $request['content'];
        $post->save();

        return back()->with('success' , "Post is Successfully Updated.");
    }

    public function deletePost($id)
    {
        $post = Post::where('id' , $id)->where('user_id' , Auth::id())->first();
        $post->delete();

        return back();
    }
}
