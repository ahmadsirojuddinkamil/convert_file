<?php

namespace Modules\Comment\App\Http\Controllers;

use Modules\Comment\App\Http\Requests\CreateCommentRequest;
use Modules\Comment\App\Models\Comment;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;

class CommentController extends Controller
{
    public function create(CreateCommentRequest $request)
    {
        $validateData = $request->validated();

        Comment::create([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => $validateData['name'],
            'comment' => $validateData['comment'],
            'star' => $validateData['star'],
        ]);

        return redirect('/')->with([
            'success' => 'Comment anda berhasil dibuat!'
        ]);
    }
}
