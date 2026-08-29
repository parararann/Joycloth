import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// ============================================
// ALPINE.JS GLOBAL STORES & COMPONENTS
// ============================================

// Notification store
Alpine.store('notification', {
    show: false,
    message: '',
    type: 'success', // success, error, warning, info
    timer: null,

    notify(message, type = 'success', duration = 4000) {
        this.message = message;
        this.type = type;
        this.show = true;
        clearTimeout(this.timer);
        this.timer = setTimeout(() => { this.show = false; }, duration);
    },
    close() { this.show = false; }
});

// Cart count store
Alpine.store('cart', {
    count: parseInt(localStorage.getItem('cartCount') || '0'),
    update(count) {
        this.count = count;
        localStorage.setItem('cartCount', count);
    }
});

Alpine.start();

// ============================================
// SMOOTH SCROLL for anchor links
// ============================================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// ============================================
// NAVBAR SCROLL EFFECT
// ============================================
window.addEventListener('scroll', () => {
    const navbar = document.getElementById('navbar');
    if (navbar) {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }
});

// ============================================
// FILE UPLOAD PREVIEW
// ============================================
window.previewImage = function(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0] && preview) {
        const reader = new FileReader();
        reader.onload = (e) => {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
};

// ============================================
// QUANTITY SELECTOR
// ============================================
window.decreaseQty = function(id) {
    const input = document.getElementById(id);
    if (input && parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
        input.dispatchEvent(new Event('change'));
    }
};

window.increaseQty = function(id) {
    const input = document.getElementById(id);
    if (input) {
        input.value = parseInt(input.value) + 1;
        input.dispatchEvent(new Event('change'));
    }
};

// ============================================
// LIVE CHAT - Auto scroll to bottom
// ============================================
window.scrollChatToBottom = function() {
    const chatContainer = document.getElementById('chat-messages');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
};

// Poll for new chat messages (every 5 seconds)
window.startChatPolling = function(orderId, lastId) {
    return setInterval(async () => {
        try {
            const res = await fetch(`/chat/poll?last_id=${lastId}`);
            const data = await res.json();
            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    appendChatMessage(msg);
                    lastId = msg.id;
                });
                scrollChatToBottom();
            }
        } catch (e) {}
    }, 5000);
};

window.appendChatMessage = function(msg) {
    const container = document.getElementById('chat-messages');
    if (!container) return;
    const div = document.createElement('div');
    div.className = `flex ${msg.sender_type === 'user' ? 'justify-end' : 'justify-start'} mb-3`;
    div.innerHTML = `
        <div class="chat-bubble-${msg.sender_type === 'user' ? 'user' : 'admin'}">
            <p>${escapeHtml(msg.message)}</p>
            <span class="text-xs opacity-60 mt-1 block">${msg.time}</span>
        </div>`;
    container.appendChild(div);
};

window.escapeHtml = function(text) {
    const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
    return text.replace(/[&<>"']/g, m => map[m]);
};
