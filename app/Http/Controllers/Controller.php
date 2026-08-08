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

    /**
     * Allow only Admin or Kepala Desa (Kades) roles.
     */
    protected function authorizeAdminOrKades(): void
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['Admin', 'Kepala Desa'], true)) {
            abort(403);
        }
    }
}
