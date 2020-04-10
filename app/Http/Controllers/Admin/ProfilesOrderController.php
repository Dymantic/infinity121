<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Profile;
use Illuminate\Http\Request;

class ProfilesOrderController extends Controller
{
    public function store()
    {
        request()->validate([
            'order' => ['required', 'array'],
            'order.*' => ['exists:profiles,id']
        ]);

        Profile::setOrder(request('order'));
    }
}
