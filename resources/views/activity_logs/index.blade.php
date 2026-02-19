@extends('layouts.admin')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">System Logs (Audit Trail)</h1>
        <p class="text-gray-500 text-sm">Monitor all system activities and user actions.</p>
    </div>

    <form method="GET" action="{{ route('logs.index') }}" class="flex flex-col md:flex-row gap-3">
        <!-- Admin Filter -->
        <select name="admin_id" onchange="this.form.submit()" class="px-4 py-2 rounded-xl bg-gray-50 border-none shadow-[inset_2px_2px_4px_rgba(0,0,0,0.05),inset_-2px_-2px_4px_rgba(255,255,255,1)] focus:ring-2 focus:ring-emerald-500 text-sm text-gray-700">
            <option value="">All Admins</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('admin_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>

        <!-- Action Filter -->
        <select name="action_type" onchange="this.form.submit()" class="px-4 py-2 rounded-xl bg-gray-50 border-none shadow-[inset_2px_2px_4px_rgba(0,0,0,0.05),inset_-2px_-2px_4px_rgba(255,255,255,1)] focus:ring-2 focus:ring-emerald-500 text-sm text-gray-700">
            <option value="">All Actions</option>
            @foreach($actions as $action)
                <option value="{{ $action }}" {{ request('action_type') == $action ? 'selected' : '' }}>
                    {{ ucfirst($action) }}
                </option>
            @endforeach
        </select>
        
        @if(request()->hasAny(['admin_id', 'action_type']))
            <a href="{{ route('logs.index') }}" class="px-4 py-2 bg-gray-200 text-gray-600 rounded-xl text-sm font-bold hover:bg-gray-300 transition-colors flex items-center justify-center">
                Reset
            </a>
        @endif
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3">Timestamp</th>
                    <th class="px-6 py-3">User</th>
                    <th class="px-6 py-3">Action</th>
                    <th class="px-6 py-3">Subject</th>
                    <th class="px-6 py-3">Description</th>
                    <th class="px-6 py-3">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($logs as $log)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs whitespace-nowrap">
                        {{ $log->created_at->format('Y-m-d H:i:s') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $log->user->name ?? 'System' }}</div>
                        <div class="text-xs text-gray-400">{{ $log->user->email ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-bold uppercase
                            {{ $log->action == 'created' ? 'bg-green-100 text-green-700' : 
                               ($log->action == 'updated' ? 'bg-blue-100 text-blue-700' : 
                               ($log->action == 'deleted' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700')) }}">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-mono text-xs text-gray-500">
                        {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                    </td>
                    <td class="px-6 py-4 text-gray-700">
                        {{ $log->description }}
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs font-mono">
                        {{ $log->ip_address }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        {{ $logs->links() }}
    </div>
</div>
@endsection
