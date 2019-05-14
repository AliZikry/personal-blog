@extends('layouts.admin')

@section('title') Author Posts @endsection

@section('content')
      <div class="content">
     <div class="card">
                        <div class="card-header bg-light">
                            Author Posts
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Created at</th>
                                        <th>Updated at</th>
                                        <th>Comments</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (Auth::user()->posts as $post)

                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td class="text-nowrap"><a href="{{ route('singlePost' , $post->id) }}">{{ $post->title }}</a></td>
                                        <td>{{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</td>
                                        <td>{{ \Carbon\Carbon::parse($post->updated_at)->diffForHumans() }}</td>
                                        <td>{{ $post->comments->count() }}</td>
                                        <td>
                                            <a href="{{ route('postEdit' , $post->id) }}" class="btn-warning">Edit</a>
                                            <form method="POST" id="deletePost-{{ $post->id }}" action="{{ route('deletePost' , $post->id) }}">@csrf</form>
                                            <a href="#" data-toggle="modal" data-target="#deletePostModal-{{ $post->id }}" class="btn-danger">Remove</a>
                                        </td>
                                    </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                 @foreach (Auth::user()->posts as $post)
                        {{-- <!-- Modal --> --}}
            <div class="modal fade" id="deletePostModal-{{ $post->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> --}}
                    <h4 class="modal-title" id="myModalLabel">You are about to delete "{{ $post->title }}".</h4>
                </div>
                <div class="modal-body">
                    Are you SURE?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No, keep it.</button>
                     <form method="POST" id="deletePost-{{ $post->id }}" action="{{ route('deletePost' , $post->id) }}">
                         @csrf
                    <button type="submit" class="btn btn-primary">Yes, delete it.</button>
                    </form>
                </div>
                </div>
            </div>
            </div>
        @endforeach
@endsection
