<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\JsonAPI\Repository\PostRepository;
use App\Library\JsonAPI\Repository\CommentRepository;

class PostController extends Controller
{
    protected $post;
    protected $comment;

    public function __construct(PostRepository $post, CommentRepository $comment) {
        $this->post = $post;
        $this->comment = $comment;
    }

    public function index() {
        return $this->post->all();
    }

    public function show($id){
        return $this->post->find($id);
    }

    public function top(Request $request) {
        $posts = $this->post->all();
        $comments = $this->comment->all();
        $groupedComments = collect($comments)->groupBy('postId');

        $response = collect($posts)->map(function($post) use ($groupedComments){
            $id = $post['id'];

            $count = $groupedComments->get($id)->count();
            return [
                'post_id' => $id,
                'post_title' => $post['title'],
                'post_body' => $post['body'],
                'total_number_of_comments' => $count
            ];
        });

        return $response->sortByDesc('total_number_of_comments')->values()->take($request->limit);
    }
}
