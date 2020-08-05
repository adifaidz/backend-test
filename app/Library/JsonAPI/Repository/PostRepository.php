<?php

namespace App\Library\JsonAPI\Repository;

use App\Library\JsonAPI\Exceptions\JsonAPIException;
use App\Library\JsonAPI\Repository\BaseRepository;
use Exception;

class PostRepository extends BaseRepository
{
    public function all()
    {
        return $this->http->get('/posts')->throw()->json();
    }

    public function find($id)
    {
        return $this->http->get('/posts/'. $id)->throw()->json();
    }
}
