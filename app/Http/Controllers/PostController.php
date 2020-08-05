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

        $posts = collect($posts);
        $comments = collect($comments);

        // Iterate every posts and count related comments
        $response = $posts->map(function($post) use (&$comments){
            $id = $post['id'];

            // Get related comments and non related comments and reduce comments
            list($postComments,$nonRelated) = $comments->partition(function ($comment) use ($id) {
                return $comment['postId'] === $id;
            });

            $comments = $nonRelated;

            return [
                'post_id' => $id,
                'post_title' => $post['title'],
                'post_body' => $post['body'],
                'total_number_of_comments' => $postComments->count(),
            ];
        });

        return $response->sort(function ($a, $b) {
            // Sort post id asc
            if ($a['total_number_of_comments'] == $b['total_number_of_comments']) {
                return ($a['post_id'] < $b['post_id']) ? -1 : 1;
            }

            // Sorts total comments desc
            return ($a['total_number_of_comments'] < $b['total_number_of_comments']) ? 1 : -1;
        })->values()->take($request->limit);
    }
}
