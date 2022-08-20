<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use DB;

class LikesController extends Controller
{
    public function LikePost(Request $request)
    {
        $user_id = auth()->user()->id;

        static $action = "";


        //check if user already likes page

        if($request->likes === "dislike")
        {
            $action = "dislike";
        }
        elseif ($request->likes === "like")
        {
            $action = "like";
        }



        switch ($action) {
            case 'like':
                DB::statement("INSERT INTO likes(user_id, post_id, rating_action) 
         	   VALUES ($user_id, $request->post_id, 'like') 
         	   ON DUPLICATE KEY UPDATE rating_action='like'");

                break;

            case 'dislike':
                DB::statement("INSERT INTO likes(user_id, post_id, rating_action) 
               VALUES ($user_id, $request->post_id, 'dislike') 
         	   ON DUPLICATE KEY UPDATE rating_action='dislike'");
                break;
            default:
                break;
        }

        $ratings = $this->getLikes($request->post_id);

        $likes =  $ratings['likes'];
        $dislikes = $ratings['dislikes'];

        return response()->json([
            'likes'=> $likes,
            'dislikes' => $dislikes,
            'response','liked']);

        return redirect('/');
    }

    public function getLikes($id)
    {
        $num_likes = DB::table('likes')
            ->select(DB::raw('count(*) as count'))
            ->where('post_id', '=', $id)
            ->where('rating_action','=','like')
            ->first()
            ->count;


        $num_dislikes = DB::table('likes')
            ->select(DB::raw('count(*) as count'))
            ->where('post_id', '=', $id)
            ->where('rating_action','=','dislike')
            ->first()
            ->count;


        $ratings = [
            'likes' => $num_likes,
            'dislikes' => $num_dislikes
        ];


        $post = Post::find($id);
        $post->num_likes = $ratings['likes'];
        $post->num_dislikes = $ratings['dislikes'];

        $post->save();

        return $ratings;
    }
}
