<?php

namespace App\Controllers\Admin;

use Library\Framework\Http\Request;
use Library\Framework\Http\Response;

class LoggingController
{
    public function view(Request $request)
    {
        $limit = (int) ($request->query('limit') ?? 100);
        $limit = max(25, min(200, $limit));

        return view('admin/logging', [
            'logs' => logger()->recent($limit),
            'stats' => logger()->stats(),
            'limit' => $limit,
        ]);
    }

    public function download(Request $request)
    {
        $filename = 'application-logs-' . date('Y-m-d') . '.log';

        return (new Response())->file(
            logger()->path(),
            $filename,
            'text/plain'
        );
    }

    public function delete(Request $request)
    {
        if (!logger()->clear()) {
            return redirect(route('admin.logs'))
                ->withMessage(
                    'Unable to clear logs at the moment',
                    'Logs Not Cleared',
                    'error'
                );
        }

        log_activity('Application logs were cleared', [
            'controller' => self::class,
            'action' => 'delete',
            'method' => $request->method ?? null,
            'uri' => $request->uri ?? null,
            'user' => $this->actorContext(),
        ], 'warning');

        return redirect(route('admin.logs'))
            ->withMessage(
                'Logs were cleared successfully',
                'Logs Cleared',
                'success'
            );
    }

    private function actorContext(): array
    {
        if (!function_exists('auth') || !auth()->check()) {
            return [];
        }

        $user = auth()->user();
        $role = $user?->getRole();

        return [
            'id' => $user->id ?? null,
            'name' => $user->name ?? null,
            'role' => $user->role ?? null,
            'admin_type' => is_object($role) && method_exists($role, 'getAdminType') ? $role->getAdminType() : null,
        ];
    }
}