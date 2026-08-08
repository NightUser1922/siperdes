<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function authorizeAdmin(): void
    {
        if (!auth()->check() || auth()->user()->role !== 'Admin') {
            abort(403);
        }
    }
}
