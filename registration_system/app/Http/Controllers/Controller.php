<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function logAction($action, $model = null, $payload = null)
    {
        \App\Models\AuditLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'payload' => $payload ? json_encode($payload) : null,
            'ip_address' => request()->ip(),
        ]);
    }
}
