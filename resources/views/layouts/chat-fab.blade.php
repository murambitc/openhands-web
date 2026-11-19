{{-- =====================
     FLOATING AI CHAT
===================== --}}
<button id="chatFabBtn" class="chat-fab-btn" onclick="toggleChatFAB()" aria-label="Open chat">
    <i class="bi bi-chat-dots-fill"></i>
</button>

<div id="chatFabPanel" class="chat-fab-panel">
    {{-- Header --}}
    <div class="chat-fab-header">
        <div class="d-flex align-items-center gap-2">
            <div class="chat-avatar">
                <i class="bi bi-robot"></i>
            </div>
            <div>
                <strong style="font-size: 14px;">Open Hands AI</strong>
                <div style="font-size: 11px; color: #22c55e;">
                    <span class="chat-status-dot"></span> Online
                </div>
            </div>
        </div>
        <button type="button" class="chat-close-btn" onclick="toggleChatFAB()" aria-label="Close">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    {{-- Messages --}}
    <div id="chatFabMessages" class="chat-fab-messages">
        <div class="chat-bubble chat-bot">
            Hello 👋 I'm here for you. How are you feeling today?
        </div>
    </div>

    {{-- Typing indicator --}}
    <div id="chatFabTyping" class="chat-fab-typing" style="display:none;">
        <div class="chat-bubble chat-bot">
            <span class="typing-dot"></span>
            <span class="typing-dot"></span>
            <span class="typing-dot"></span>
        </div>
    </div>

    {{-- Input --}}
    <div class="chat-fab-input">
        <input
            type="text"
            id="chatFabInput"
            placeholder="Type a message..."
            autocomplete="off"
            onkeydown="if(event.key==='Enter'){sendChatFAB();}"
        >
        <button type="button" class="chat-send-btn" onclick="sendChatFAB()" aria-label="Send">
            <i class="bi bi-send-fill"></i>
        </button>
    </div>
</div>

<style>
    .chat-fab-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2F5DAA, #be185d);
        border: none;
        color: #fff;
        font-size: 24px;
        cursor: pointer;
        z-index: 999;
        box-shadow: 0 8px 30px rgba(47, 93, 170, 0.5);
        transition: transform 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chat-fab-btn:hover {
        transform: scale(1.08);
    }

    .chat-fab-panel {
        position: fixed;
        bottom: 90px;
        right: 20px;
        width: 340px;
        max-width: calc(100vw - 40px);
        height: 480px;
        max-height: calc(100vh - 120px);
        display: none;
        flex-direction: column;
        border-radius: 16px;
        overflow: hidden;
        z-index: 1000;
        background: rgba(11, 18, 32, 0.98);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.6);
        animation: chatSlideIn 0.3s ease;
    }

    .chat-fab-panel.active {
        display: flex;
    }

    @keyframes chatSlideIn {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .chat-fab-header {
        padding: 12px 16px;
        background: linear-gradient(180deg, #2F5DAA, #1e3a8a);
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #fff;
    }

    .chat-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .chat-status-dot {
        display: inline-block;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #22c55e;
        margin-right: 4px;
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
        animation: pulseDot 1.8s infinite;
    }

    @keyframes pulseDot {
        0%, 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
        50%      { box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
    }

    .chat-close-btn {
        background: transparent;
        border: none;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        padding: 6px;
        border-radius: 8px;
        transition: background 0.2s;
    }

    .chat-close-btn:hover {
        background: rgba(255, 255, 255, 0.15);
    }

    .chat-fab-messages {
        flex: 1;
        padding: 16px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .chat-fab-messages::-webkit-scrollbar { width: 5px; }
    .chat-fab-messages::-webkit-scrollbar-thumb {
        background: #2F5DAA;
        border-radius: 10px;
    }

    .chat-bubble {
        max-width: 80%;
        padding: 10px 14px;
        border-radius: 14px;
        font-size: 13px;
        line-height: 1.5;
        word-wrap: break-word;
        animation: bubbleIn 0.25s ease;
    }

    @keyframes bubbleIn {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .chat-bot {
        align-self: flex-start;
        background: rgba(255, 255, 255, 0.08);
        color: #e2e8f0;
        border-bottom-left-radius: 4px;
    }

    .chat-user {
        align-self: flex-end;
        background: linear-gradient(135deg, #2F5DAA, #1e3a8a);
        color: #fff;
        border-bottom-right-radius: 4px;
    }

    .chat-fab-typing {
        padding: 0 16px 10px;
    }

    .typing-dot {
        display: inline-block;
        width: 6px;
        height: 6px;
        background: #94a3b8;
        border-radius: 50%;
        margin: 0 2px;
        animation: typingBounce 1.2s infinite;
    }

    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typingBounce {
        0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
        30%           { transform: translateY(-6px); opacity: 1; }
    }

    .chat-fab-input {
        padding: 12px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        gap: 8px;
    }

    .chat-fab-input input {
        flex: 1;
        background: #fff;
        color: #000;
        border: none;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13px;
        outline: none;
    }

    .chat-send-btn {
        background: linear-gradient(180deg, #ff4d8d, #c2185b);
        border: none;
        color: #fff;
        width: 42px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 16px;
        transition: opacity 0.2s;
    }

    .chat-send-btn:hover {
        opacity: 0.9;
    }

    .chat-send-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    @media (max-width: 480px) {
        .chat-fab-panel {
            right: 10px;
            left: 10px;
            width: auto;
            bottom: 80px;
        }
        .chat-fab-btn {
            width: 54px;
            height: 54px;
            font-size: 20px;
        }
    }
</style>

<script>
    (function () {
        const messagesEl = document.getElementById('chatFabMessages');
        const typingEl = document.getElementById('chatFabTyping');
        const inputEl = document.getElementById('chatFabInput');
        const sendBtn = document.querySelector('.chat-send-btn');
        const panelEl = document.getElementById('chatFabPanel');

        let historyLoaded = false;

        window.toggleChatFAB = function () {
            panelEl.classList.toggle('active');
            if (panelEl.classList.contains('active') && !historyLoaded) {
                loadChatHistory();
            }
            if (panelEl.classList.contains('active')) {
                setTimeout(() => inputEl.focus(), 200);
            }
        };

        function loadChatHistory() {
            historyLoaded = true;
            fetch('{{ route("chat.index") }}', {
                headers: { 'Accept': 'application/json' },
            })
            .then(res => res.ok ? res.json() : [])
            .then(messages => {
                if (!Array.isArray(messages) || messages.length === 0) return;
                messagesEl.innerHTML = '';
                messages.forEach(m => appendBubble(m.message, m.sender === 'user' ? 'user' : 'bot'));
                scrollToBottom();
            })
            .catch(() => { /* silent */ });
        }

        function appendBubble(text, side) {
            const div = document.createElement('div');
            div.className = 'chat-bubble ' + (side === 'user' ? 'chat-user' : 'chat-bot');
            div.textContent = text;
            messagesEl.appendChild(div);
            scrollToBottom();
        }

        function scrollToBottom() {
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }

        window.sendChatFAB = async function () {
            const message = inputEl.value.trim();
            if (!message) return;

            appendBubble(message, 'user');
            inputEl.value = '';
            typingEl.style.display = 'block';
            sendBtn.disabled = true;

            try {
                const res = await fetch('{{ route("chat.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ message }),
                });

                const data = await res.json();
                typingEl.style.display = 'none';

                if (data.error) {
                    appendBubble('⚠️ ' + data.error, 'bot');
                } else if (data.reply) {
                    appendBubble(data.reply, 'bot');
                } else {
                    appendBubble('⚠️ No response from server.', 'bot');
                }
            } catch (err) {
                typingEl.style.display = 'none';
                appendBubble('⚠️ Connection error. Please try again.', 'bot');
            } finally {
                sendBtn.disabled = false;
                inputEl.focus();
            }
        };
    })();
</script>