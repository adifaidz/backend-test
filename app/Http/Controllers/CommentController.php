<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\JsonAPI\Repository\CommentRepository;

class CommentController extends Controller
{
    protected $comment;

    public function __construct(CommentRepository $comment) {
        $this->comment = $comment;
    }

    public function index(Request $request) {
        return $this->comment->search($request);
    }

}
