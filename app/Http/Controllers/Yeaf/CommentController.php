<?php

namespace App\Http\Controllers\Yeaf;

use App\Http\Requests\CommentStoreRequest;
use App\Models\Yeaf\Comment;
use App\Models\Yeaf\Student;
use Illuminate\Support\Facades\Redirect;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CommentStoreRequest $request)
    {
        $comment = Comment::create($request->validated());
        $student = Student::where('student_id', $comment->student_id)->first();

        return Redirect::route('students.show', [$student->id]);
    }
}
