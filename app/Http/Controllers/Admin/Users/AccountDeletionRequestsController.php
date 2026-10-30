<?php

namespace App\Http\Controllers\Admin\Users;

use App\AccountDeletionRequest;
use App\Http\Controllers\Controller;
use App\User;

class AccountDeletionRequestsController extends Controller
{
    public function index()
    {
        abort_unless(User::userHasPermission(3), 403);

        $requests = AccountDeletionRequest::with('user')->orderBy('confirmed_at')->paginate(25);

        return view('admin.users.deletion-requests', compact('requests'));
    }
}
