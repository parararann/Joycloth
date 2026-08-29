@extends('layouts.admin')
@section('title', 'Live Chat Admin')
@section('page-title', 'Live Chat')
@section('page-subtitle', 'Manage conversations with customers')

@section('content')

@if($totalUnread > 0)
<div class="alert-warning mb-6">
    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
    <span>There are <strong>{{ $totalUnread }}</strong> unreplied messages</span>
</div>
@endif

<div class="space-y-3">
    @forelse($conversations as $conv)
    <a href="{{ route('admin.chat.conversation', $conv['user']->id) }}"
       class="card-flat p-5 flex items-center gap-4 hover:border-accent hover:shadow-brutal transition-all {{ $conv['unreadCount'] > 0 ? 'bg-primary-50' : '' }}">

        <div class="w-12 h-12 bg-primary-200 border-2 border-dark-950 text-dark-950 shadow-brutal-sm rounded-full flex items-center justify-center font-bold text-lg flex-shrink-0">
            {{ strtoupper(substr($conv['user']->name, 0, 1)) }}
        </div>

        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between gap-2">
                <p class="text-dark-950 font-bold uppercase">{{ $conv['user']->name }}</p>
                <span class="text-dark-600 font-bold text-xs flex-shrink-0 uppercase">{{ $conv['lastMessage']->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-dark-700 font-medium text-sm truncate mt-0.5">
                @if($conv['lastMessage']->sender_type === 'admin')
                <span class="text-primary-400">You:</span>
                @endif
                {{ $conv['lastMessage']->message }}
            </p>
        </div>

        @if($conv['unreadCount'] > 0)
        <div class="w-6 h-6 bg-primary-500 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">
            {{ $conv['unreadCount'] }}
        </div>
        @endif
    </a>
    @empty
    <div class="card-flat p-16 text-center shadow-brutal">
        <div class="text-6xl mb-3">💬</div>
        <p class="text-dark-600 font-bold uppercase text-lg">No conversations yet</p>
    </div>
    @endforelse
</div>
@endsection
