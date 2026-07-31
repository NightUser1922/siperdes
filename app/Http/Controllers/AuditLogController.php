<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index()
    {
        $auditLogs = AuditLog::with('user')
            ->orderBy('waktu_akses', 'desc')
            ->orderBy('id_log', 'desc')
            ->get();

        return view('audit-log', compact('auditLogs'));
    }
}