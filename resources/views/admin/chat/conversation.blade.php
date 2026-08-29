@extends('layouts.admin')
@section('title', 'Chat with ' . $chatUser->name)
@section('page-title', 'Chat with ' . $chatUser->name)

@section('content')
<div class="max-w-3xl">
    <div class="card-flat overflow-hidden shadow-brutal">

        {{-- Chat Header --}}
        <div class="flex items-center gap-3 px-5 py-4 border-b-2 border-dark-950 bg-primary-200">
            <a href="{{ route('admin.chat.index') }}" class="text-dark-950 hover:text-accent transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="w-10 h-10 bg-white border-2 border-dark-950 rounded-full flex items-center justify-center font-black text-dark-950 shadow-brutal-sm">
                {{ strtoupper(substr($chatUser->name, 0, 1)) }}
            </div>
            <div>
                <p class="text-dark-950 font-black uppercase text-sm leading-tight">{{ $chatUser->name }}</p>
                <p class="text-dark-600 font-bold text-xs uppercase">{{ $chatUser->email }}</p>
            </div>
        </div>

        {{-- Messages --}}
        <div id="chat-messages" class="h-96 overflow-y-auto p-5 space-y-4 bg-[#fdfdfb]">
            @foreach($messages as $msg)
            <div class="flex {{ $msg->sender_type === 'admin' ? 'justify-end' : 'justify-start' }} mb-3">
                @if($msg->sender_type === 'user')
                <div class="flex items-end gap-2">
                    <div class="w-8 h-8 bg-white border-2 border-dark-950 rounded-full flex-shrink-0 flex items-center justify-center text-dark-950 text-xs font-black shadow-brutal-sm">
                        {{ strtoupper(substr($chatUser->name, 0, 1)) }}
                    </div>
                    <div class="chat-bubble-admin border-2 border-dark-950 bg-white shadow-brutal-sm">
                        <p class="text-dark-950 font-bold">{{ $msg->message }}</p>
                        <span class="text-[10px] font-black uppercase text-dark-400 mt-1 block tracking-widest">{{ $msg->time }}</span>
                    </div>
                </div>
                @else
                <div class="chat-bubble-user">
                    <p>{{ $msg->message }}</p>
                    <span class="text-xs opacity-70 mt-1 block text-right">{{ $msg->time }}</span>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        {{-- Reply Input --}}
        <div class="border-t-2 border-dark-950 p-4 bg-primary-100">
            <div class="flex gap-3 items-end">
                <textarea id="admin-chat-input"
                          placeholder="Type a reply..."
                          rows="1"
                          class="flex-1 form-input border-2 border-dark-950 bg-white text-dark-950 font-bold resize-none max-h-28 py-2.5 placeholder-dark-400 shadow-brutal-sm"
                          onkeydown="if(event.key==='Enter' && !event.shiftKey){event.preventDefault(); adminSend();}"></textarea>
                <button onclick="adminSend()" id="admin-send-btn" class="btn-primary px-6 py-3.5 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    scrollChatToBottom();

    @if($messages->isNotEmpty())
    let lastId = {{ $messages->last()->id }};
    @else
    let lastId = 0;
    @endif

    // Polling pesan baru dari user
    setInterval(async () => {
        try {
            const res = await fetch(`{{ route('admin.chat.poll', $chatUser->id) }}?last_id=${lastId}`);
            const data = await res.json();
            if (data.messages?.length > 0) {
                data.messages.forEach(msg => {
                    appendUserMessage(msg);
                    lastId = msg.id;
                });
                scrollChatToBottom();
            }
        } catch (e) {}
    }, 5000);
});

function adminSend() {
    const input = document.getElementById('admin-chat-input');
    const text = input.value.trim();
    if (!text) return;

    const btn = document.getElementById('admin-send-btn');
    btn.disabled = true;

    // Show immediately
    const container = document.getElementById('chat-messages');
    const div = document.createElement('div');
    div.className = 'flex justify-end mb-3';
    const now = new Date();
    const timeStr = now.getHours().toString().padStart(2, '0') + ':' + 
                    now.getMinutes().toString().padStart(2, '0');
    div.innerHTML = `<div class="chat-bubble-user"><p>${escapeHtml(text)}</p><span class="text-xs opacity-70 mt-1 block text-right">${timeStr}</span></div>`;
    container.appendChild(div);
    scrollChatToBottom();
    input.value = '';

    fetch('{{ route("admin.chat.reply", $chatUser->id) }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ message: text })
    })
    .then(r => r.json())
    .then(() => { btn.disabled = false; })
    .catch(() => { btn.disabled = false; });
}

function appendUserMessage(msg) {
    const container = document.getElementById('chat-messages');
    const div = document.createElement('div');
    div.className = 'flex justify-start mb-3';
    div.innerHTML = `
        <div class="flex items-end gap-2">
            <div class="w-8 h-8 bg-white border-2 border-dark-950 rounded-full flex-shrink-0 flex items-center justify-center text-dark-950 text-xs font-black shadow-brutal-sm">
                {{ strtoupper(substr($chatUser->name, 0, 1)) }}
            </div>
            <div class="chat-bubble-admin border-2 border-dark-950 bg-white shadow-brutal-sm">
                <p class="text-dark-950 font-bold">${escapeHtml(msg.message)}</p>
                <span class="text-[10px] font-black uppercase text-dark-400 mt-1 block tracking-widest">${msg.time}</span>
            </div>
        </div>`;
    container.appendChild(div);
}
</script>
@endpush
