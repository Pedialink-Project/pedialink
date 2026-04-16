@extends('layout/portal')

@section('title')
	Admin Logs
@endsection

@section('header')
    <svg 
        width="24" 
        height="24" 
        viewBox="0 0 24 24" 
        fill="none" 
        stroke="currentColor" 
        stroke-width="2" 
        stroke-linecap="round" 
        stroke-linejoin="round"
        class="log-icon"
    >
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
        <polyline points="14 2 14 8 20 8"></polyline>
        
        <line x1="8" y1="13" x2="16" y2="13"></line>
        <line x1="8" y1="17" x2="16" y2="17"></line>
        <line x1="10" y1="9" x2="8" y2="9"></line>
    </svg>
	Logs
@endsection

@section('header_right')
	<div class="logs-actions">
		<c-link type="primary" href="{{ route('admin.logs.download') }}">Download All Logs</c-link>
        <c-modal>
            <c-slot name="trigger">
                <c-button variant="destructive">Clear Logs</c-button>
            </c-slot>

            <c-slot name="header">
                <span>Clear logs</span>
            </c-slot>

            <span>
                Do you want to clear logs?
            </span>

            <c-slot name="close">
                Cancel
            </c-slot>
            
            <c-slot name="footer">
                <form method="POST" action="{{ route('admin.logs.delete') }}">
                    <c-button type="submit" variant="destructive">Clear Logs</c-button>
                </form>
            </c-slot>
        </c-modal>
	</div>
@endsection

@section('css')
	<link rel="stylesheet" href="{{ asset('css/pages/admin/logging.css') }}">
@endsection

@section('content')
	<div class="logs-summary">
		<c-card class="logs-summary-card">
			<div class="logs-summary-label">Total Logs</div>
			<div class="logs-summary-value">{{ $stats['total'] ?? 0 }}</div>
		</c-card>

		<c-card class="logs-summary-card">
			<div class="logs-summary-label">Info</div>
			<div class="logs-summary-value">{{ $stats['info'] ?? 0 }}</div>
		</c-card>

		<c-card class="logs-summary-card">
			<div class="logs-summary-label">Warnings</div>
			<div class="logs-summary-value">{{ $stats['warning'] ?? 0 }}</div>
		</c-card>

		<c-card class="logs-summary-card">
			<div class="logs-summary-label">Errors</div>
			<div class="logs-summary-value">{{ $stats['error'] ?? 0 }}</div>
		</c-card>
	</div>

	<c-card class="card logs-card">

		<div class="card-body">
			@if (count($logs) === 0)
				<c-emptycard
					title="No logs available"
					description="There are no log entries to display right now."
				/>
			@else
				<div class="table-wrapper" data-responsive="true">
					<c-table.main sticky="1" size="comfortable">
						<c-table.thead>
							<c-table.tr>
								<c-table.th sortable="0">Time</c-table.th>
								<c-table.th sortable="0">Level</c-table.th>
								<c-table.th sortable="0">User</c-table.th>
								<c-table.th sortable="0">Action</c-table.th>
								<c-table.th sortable="0">Details</c-table.th>
							</c-table.tr>
						</c-table.thead>

						<c-table.tbody>
							@foreach ($logs as $log)
								<?php
									$level = strtolower($log['level'] ?? 'info');
									$context = $log['context'] ?? [];
									$user = $context['user'] ?? [];
									$action = trim(($context['controller'] ?? 'System') . '@' . ($context['action'] ?? 'log'), '@');
									$details = trim(($context['method'] ?? '') . ' ' . ($context['uri'] ?? ''));
								?>
								<c-table.tr>
									<c-table.td>{{ $log['timestamp'] ?? '' }}</c-table.td>
									<c-table.td>
										@if ($level === 'error')
											<c-badge type="red">Error</c-badge>
										@elseif ($level === 'warning')
											<c-badge type="yellow">Warning</c-badge>
										@elseif ($level === 'debug')
											<c-badge type="secondary">Debug</c-badge>
										@else
											<c-badge type="blue">Info</c-badge>
										@endif
									</c-table.td>
									<c-table.td>
										<div class="logs-details">
											<div class="name">{{ $user['name'] ?? 'System' }}</div>
											<div class="sub-name">{{ $user['admin_type'] ?? $user['role'] ?? '' }}</div>
										</div>
									</c-table.td>
									<c-table.td>
										<div class="logs-details">
											<div class="name">{{ $action }}</div>
											<div class="sub-name">{{ $context['route'] ?? 'n/a' }}</div>
										</div>
									</c-table.td>
									<c-table.td>
										<div class="logs-details">
											<div class="name">{{ $log['message'] ?? '' }}</div>
											<div class="sub-name">{{ $details }}</div>
										</div>
									</c-table.td>
								</c-table.tr>
							@endforeach
						</c-table.tbody>
					</c-table.main>
				</div>
			@endif
		</div>
	</c-card>
@endsection
