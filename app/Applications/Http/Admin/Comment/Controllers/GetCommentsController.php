<?php

namespace App\Applications\Http\Admin\Comment\Controllers;

use App\Domains\Lesson\Data\CommentFilterData;
use App\Domains\Lesson\ViewModels\Admin\CommentListViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetCommentsController
{
    public function __invoke(Request $request): View
    {
        $filters = CommentFilterData::from([
            'course_id' => $request->integer('course_id') ?: null,
            'lesson_id' => $request->integer('lesson_id') ?: null,
            'search' => $request->string('search')->toString() ?: null,
        ]);

        $viewModel = new CommentListViewModel(
            filters: $filters,
            user: $request->user('admin'),
        );

        return view('admin.comments.index', compact('viewModel'));
    }
}
