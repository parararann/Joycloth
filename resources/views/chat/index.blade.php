@extends('layouts.app')
@section('title', 'Live Chat')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex items-center gap-4 mb-6">
        <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-2xl flex items-center justify-center text-white text-xl">💬</div>
        <div>
            <h1 class="font-display font-bold text-2xl text-dark-900">Live Chat</h1>
            <p class="text-dark-400 text-sm">Chat directly with our team</p>
        </div>
        <div class="ml-auto flex items-center gap-2">
            @if($isAdminOnline)
                <div class="w-2.5 h-2.5 bg-emerald-400 rounded-full animate-pulse"></div>
                <span class="text-sm text-emerald-600 font-bold uppercase tracking-wide">Online</span>
            @else
                <div class="w-2.5 h-2.5 bg-red-500 rounded-full"></div>
                <span class="text-sm text-red-600 font-bold uppercase tracking-wide">Offline</span>
            @endif
        </div>
    </div>

    {{-- Working Hours Info --}}
    <div class="bg-primary-50 border-2 border-dark-950 p-4 mb-4 shadow-brutal-sm">
        <p class="text-center text-sm md:text-base text-dark-900 font-bold">
            🕒 Our team is active Mon–Sat, 08:00–17:00 WIB. <br class="sm:hidden">
            <span class="text-primary-700">Messages outside working hours will be replied to on the next business day.</span>
        </p>
    </div>

    {{-- Chat Window --}}
    <div class="card-flat overflow-hidden border-2 border-dark-950 shadow-brutal">
        {{-- Messages Area --}}
        <div id="chat-messages" class="h-96 overflow-y-auto p-5 space-y-3 bg-dark-50">

            {{-- Welcome Message --}}
            <div class="flex justify-start mb-3">
                <div class="chat-bubble-admin bg-dark-800 text-white">
                    <p>👋 Hello! Welcome to Joycloth. How can we help you today?</p>
                    <span class="text-xs opacity-60 mt-1 block">System</span>
                </div>
            </div>

            @foreach($messages as $msg)
            <div class="flex {{ $msg->sender_type === 'user' ? 'justify-end' : 'justify-start' }} mb-3">
                @if($msg->sender_type === 'admin')
                <div class="flex items-end gap-2">
                    <div class="w-7 h-7 bg-primary-500 rounded-full flex-shrink-0 flex items-center justify-center text-white text-xs font-bold">A</div>
                    <div class="chat-bubble-admin">
                        <p>{{ $msg->message }}</p>
                        <span class="text-xs opacity-60 mt-1 block">{{ $msg->time }}</span>
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

        {{-- Input Area --}}
        <div class="border-t border-dark-200 p-4 bg-white">
            <div class="flex gap-3 items-end">
                <textarea id="chat-input"
                          placeholder="Type your message..."
                          rows="1"
                          class="flex-1 form-input resize-none max-h-28 py-2.5"
                          onkeydown="if(event.key==='Enter' && !event.shiftKey){event.preventDefault(); sendMessage();}"></textarea>
                <button onclick="sendMessage()"
                        id="send-btn"
                        class="btn-primary px-5 py-3 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </div>
            <p class="text-xs text-dark-400 mt-2">Press Enter to send, Shift+Enter for a new line</p>
        </div>
    </div>


</div>
@endsection

@push('scripts')
<script>
// Scroll ke bawah saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    scrollChatToBottom();

    // Mulai polling untuk pesan baru dari admin
    @if($messages->isNotEmpty())
    const lastId = {{ $messages->last()->id }};
    @else
    const lastId = 0;
    @endif

    let currentLastId = lastId;

    setInterval(async () => {
        try {
            const res = await fetch(`/chat/poll?last_id=${currentLastId}`);
            const data = await res.json();
            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    appendAdminMessage(msg);
                    currentLastId = msg.id;
                });
                scrollChatToBottom();
            }
        } catch (e) {}
    }, 5000);
});

function sendMessage() {
    const input = document.getElementById('chat-input');
    const text = input.value.trim();
    if (!text) return;

    const btn = document.getElementById('send-btn');
    btn.disabled = true;

    // Tampilkan pesan langsung di UI
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

    fetch('{{ route("chat.send") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ message: text })
    })
    .then(r => r.json())
    .then(data => { btn.disabled = false; })
    .catch(() => { btn.disabled = false; });
}

function appendAdminMessage(msg) {
    const container = document.getElementById('chat-messages');
    const div = document.createElement('div');
    div.className = 'flex justify-start mb-3';
    div.innerHTML = `
        <div class="flex items-end gap-2">
            <div class="w-7 h-7 bg-primary-500 rounded-full flex-shrink-0 flex items-center justify-center text-white text-xs font-bold">A</div>
            <div class="chat-bubble-admin">
                <p>${escapeHtml(msg.message)}</p>
                <span class="text-xs opacity-60 mt-1 block">${msg.time}</span>
            </div>
        </div>`;
    container.appendChild(div);
}
</script>
@endpush
