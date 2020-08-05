<?php

namespace App\Library\JsonAPI\Repository;

use Illuminate\Http\Request;
use App\Library\JsonAPI\Repository\BaseRepository;

class CommentRepository extends BaseRepository
{
    protected $searchable = ['id','name', 'email', 'body', 'postId'];


    public function all() : array
    {
        return $this->http->get('/comments')->throw()->json();
    }

    public function search(Request $request) : array
    {   $comments =$this->all();

        // If no params sent, return all
        if(!count($request->all()))
            return $comments;

        // If there's no params matching the searchable array , return empty
        if(!$request->hasAny($this->searchable))
            return [];


        return collect($comments)->filter(function ($comment) use ($request) {
            $found = true;

            foreach($request->only($this->searchable) as $filter => $searchValue) {
                $commentValue = $comment[$filter];

                if(!$this->stringSearch($commentValue, $searchValue) && !$this->numericSearch($commentValue, $searchValue)){
                    $found = false;
                    break;
                }
            }

            return $found;
        })->toArray();
    }

    private function stringSearch($string, $value) {
        return is_string($string) && strpos($string, $value) !== false;
    }

    private function numericSearch($string, $value) {
        return is_numeric($string) && $string == $value;
    }
}
