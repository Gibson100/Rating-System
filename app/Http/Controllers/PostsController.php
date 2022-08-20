<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Likes;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{

    // prevent unauthorized editing or deletion of posts middleware is required
    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'index', 'getPostId', 'getLikes'
        ]]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();

        $num_likes = Likes::select('SELECT COUNT(*) FROM rating_info WHERE post_id = $id AND rating_action=\'like\'');

        $num_dislikes = Likes::select('SELECT COUNT(*) FROM rating_info 
		  			WHERE post_id = $id AND rating_action=\'dislike\'');

        $ratings[] = [
            'likes' => $num_likes,
            'dislikes' => $num_dislikes
        ];


        return view('/welcome', compact(['posts', 'ratings']));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPostId($id)
    {
        $post = Post::find($id);
        return view('posts.edit', compact('post'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'image|required|max:1999'
        ]);

        //get filename with extension
        $filenameWithExt = $request->file('image')->getClientOriginalName();

        //get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

        //get just extension
        $extension = $request->File('image')->getClientOriginalExtension();

        //filename to store
        $fileNameToStore = $filename . '_' . time() . '.' . $extension;

        //upload image
        $path = $request->File('image')->storeAs('public/images', $fileNameToStore);


        // create Post
        $post = new Post;

        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->image = $fileNameToStore;
        $post->user_id = auth()->user()->id;

        $post->save();

        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function edit(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'max:255',
        ]);

        //handle file upload
        if ($request->hasFile('image')) {
            //get filename with extension
            $filenameWithExt = $request->file('image')->getClientOriginalName();

            //get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            //get just extension
            $extension = $request->File('image')->getClientOriginalExtension();

            //filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            //upload image
            $path = $request->File('image')->storeAs('public/images', $fileNameToStore);
        }

        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        if ($request->hasFile('image')) {
            $post->image = $fileNameToStore;
        }
        $post->save();

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        $post = Post::find($id);
//        if(auth()->user()->id !== $post->user_id)
//        {
//            return redirect('/posts')->with('error','Unauthorized page');
//        }

        $post = Post::findOrFail($id);

        Storage::delete('public/images/'.$post->image); // delete from storage

        $post->delete();

        return redirect('/');
    }

    public function LikePost(Request $request)
    {

        $post = Post::find($request->id);
        $response = auth()->user()->toggleLike($post);
        $post->num_likes = $post->likers()->get()->count();

        $post->save();

        return response()->json(['success' => $response]);
    }

    public function getLikes(Request $request)
    {
        $post = Post::find($request->post_id);
        $likes = $post->num_likes;
        $dislikes = $post->num_dislikes;

        return response()->json([
            'likes' => $likes,
            'dislikes' => $dislikes
        ]);

    }
}
