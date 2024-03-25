<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Models\Comment;
use Ramsey\Uuid\Uuid;

class commentController extends Controller
{
    public function create(CreateCommentRequest $request)
    {
        $validateData = $request->validated();

        $createComment =  Comment::create([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => $validateData['name'],
            'comment' => $validateData['comment'],
            'star' => $validateData['star'],
        ]);

        return redirect('/')->with([
            'uuid' => $createComment['uuid'],
            'name' => $createComment['name'],
            'success' => 'Comment has been created!'
        ]);
    }

    public function reply(CreateCommentRequest $request, $saveUuidFromRoute)
    {
        $validateData = $request->validated();

        $createComment =  Comment::create([
            'uuid' => $saveUuidFromRoute,
            'name' => $validateData['name'],
            'comment' => $validateData['comment'],
            'star' => $validateData['star'],
        ]);

        return redirect('/')->with([
            'uuid' => $createComment['uuid'],
            'name' => $createComment['name'],
            'success' => 'Comment has been created!'
        ]);
    }
}
