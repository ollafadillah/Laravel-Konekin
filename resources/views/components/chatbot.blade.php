<div id="ai-chatbot-container" class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
    
    <!-- Chat Window -->
    <div id="ai-chat-window" class="hidden mb-4 w-80 sm:w-96 bg-white rounded-3xl shadow-2xl border border-[#DBEAFE] overflow-hidden flex flex-col h-[500px] max-h-[70vh]">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#1B3FA6] to-[#2563EB] p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white backdrop-blur-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                </div>
                <div>
                    <h3 class="text-white font-bold text-sm">Konekin AI</h3>
                    <p class="text-white/70 text-[10px] uppercase tracking-wider font-bold">Asisten Virtual</p>
                </div>
            </div>
            <button id="close-ai-chat" type="button" class="text-white/70 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Chat Body -->
        <div id="ai-chat-body" class="flex-1 p-4 overflow-y-auto bg-[#EEF4FF]/30 space-y-4">
            <!-- Initial Message -->
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-[#1B3FA6] flex-shrink-0 flex items-center justify-center text-white mt-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                </div>
                <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-[#DBEAFE]/50 text-sm text-[#475569] leading-relaxed max-w-[85%]">
                    Halo! Saya Konekin AI. Ada yang bisa saya bantu terkait platform Konekin, Creative Worker, atau UMKM?
                </div>
            </div>

            <!-- Quick Questions -->
            <div id="ai-quick-questions" class="pl-11 space-y-2">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#2563EB]/70">Pertanyaan cepat</p>
                <div class="flex flex-wrap gap-2">
                    <button type="button" class="ai-suggestion-chip px-4 py-2 rounded-full bg-white border border-[#DBEAFE] text-xs font-semibold text-[#1B3FA6] hover:bg-[#EEF4FF] hover:border-[#2563EB]/30 transition-colors" data-question="Bagaimana cara Creative Worker bekerja?">
                        Bagaimana cara Creative Worker bekerja?
                    </button>
                    <button type="button" class="ai-suggestion-chip px-4 py-2 rounded-full bg-white border border-[#DBEAFE] text-xs font-semibold text-[#1B3FA6] hover:bg-[#EEF4FF] hover:border-[#2563EB]/30 transition-colors" data-question="Bagaimana cara UMKM bekerja?">
                        Bagaimana cara UMKM bekerja?
                    </button>
                    <button type="button" class="ai-suggestion-chip px-4 py-2 rounded-full bg-white border border-[#DBEAFE] text-xs font-semibold text-[#1B3FA6] hover:bg-[#EEF4FF] hover:border-[#2563EB]/30 transition-colors" data-question="Bagaimana cara daftar dan mulai di Konekin?">
                        Bagaimana cara daftar dan mulai di Konekin?
                    </button>
                    <button type="button" class="ai-suggestion-chip px-4 py-2 rounded-full bg-white border border-[#DBEAFE] text-xs font-semibold text-[#1B3FA6] hover:bg-[#EEF4FF] hover:border-[#2563EB]/30 transition-colors" data-question="Bagaimana sistem escrow di Konekin?">
                        Bagaimana sistem escrow di Konekin?
                    </button>
                </div>
            </div>
        </div>

        <!-- Chat Input -->
        <div class="p-3 bg-white border-t border-[#DBEAFE]/50">
            <form id="ai-chat-form" class="relative flex items-center">
                <input type="text" id="ai-chat-input" placeholder="Tanya sesuatu..." class="w-full pl-4 pr-12 py-3 bg-[#EEF4FF] border-none rounded-2xl text-sm text-[#1B3FA6] placeholder-[#1B3FA6]/40 focus:ring-2 focus:ring-[#2563EB]/20 outline-none transition-all">
                <button type="submit" id="ai-send-btn" class="absolute right-2 w-8 h-8 flex items-center justify-center bg-[#2563EB] text-white rounded-xl hover:bg-[#1B3FA6] transition-colors disabled:opacity-50">
                    <svg class="w-4 h-4 translate-x-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </form>
            <div class="text-center mt-2">
                <span class="text-[9px] text-[#475569]/50 font-medium">Powered by Gemini 2.5 Flash</span>
            </div>
        </div>
    </div>

    <!-- Floating Button -->
    <div class="relative group">
        <!-- Tooltip -->
        <div class="absolute right-full mr-4 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-white text-[#1B3FA6] text-xs font-bold rounded-lg shadow-xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap border border-[#DBEAFE]/50">
            Tanya AI
            <div class="absolute top-1/2 -right-1 -translate-y-1/2 w-2 h-2 bg-white border-t border-r border-[#DBEAFE]/50 rotate-45"></div>
        </div>
        
        <button id="toggle-ai-chat" type="button" class="w-14 h-14 bg-gradient-to-tr from-[#1B3FA6] to-[#2563EB] rounded-full flex items-center justify-center text-white shadow-[0_8px_30px_rgba(37,99,235,0.3)] hover:shadow-[0_8px_30px_rgba(37,99,235,0.5)] hover:-translate-y-1 transition-all duration-300 relative">
            <svg class="w-6 h-6 relative z-10 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
        </button>
    </div>
</div>

<script>
    document.addEventListener('click', function(e) {
        const toggleBtn = e.target.closest('#toggle-ai-chat');
        const closeBtn = e.target.closest('#close-ai-chat');
        const suggestionBtn = e.target.closest('.ai-suggestion-chip');
        
        if (suggestionBtn) {
            const chatInput = document.getElementById('ai-chat-input');
            const question = suggestionBtn.dataset.question || suggestionBtn.textContent.trim();

            if (chatInput) {
                chatInput.value = question;
                chatInput.focus();

                const form = document.getElementById('ai-chat-form');
                if (form) {
                    form.requestSubmit();
                }
            }

            return;
        }

        if (toggleBtn || closeBtn) {
            const chatWindow = document.getElementById('ai-chat-window');
            const chatInput = document.getElementById('ai-chat-input');
            const btn = document.getElementById('toggle-ai-chat');
            
            if (chatWindow.classList.contains('hidden')) {
                // Buka Chat
                chatWindow.classList.remove('hidden');
                chatWindow.classList.add('flex');
                setTimeout(() => chatInput.focus(), 50);
                
                // Ubah ikon jadi X
                if(btn) {
                    btn.innerHTML = `<svg class="w-6 h-6 relative z-10 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>`;
                }
            } else {
                
                chatWindow.classList.add('hidden');
                chatWindow.classList.remove('flex');
                
                // Ubah ikon jadi Bintang
                if(btn) {
                    btn.innerHTML = `<svg class="w-6 h-6 relative z-10 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>`;
                }
            }
        }
    });

    document.addEventListener('submit', async function(e) {
        if (e.target && e.target.id === 'ai-chat-form') {
            e.preventDefault();
            
            const chatInput = document.getElementById('ai-chat-input');
            const chatBody = document.getElementById('ai-chat-body');
            const sendBtn = document.getElementById('ai-send-btn');
            
            const message = chatInput.value.trim();
            if (!message) return;

            // Pastikan array history ada (gunakan window global)
            window.aiChatHistory = window.aiChatHistory || [];

            function scrollToBottom() {
                chatBody.scrollTop = chatBody.scrollHeight;
            }

            function addMessage(content, isUser = false) {
                const wrapper = document.createElement('div');
                wrapper.className = `flex items-start gap-3 ${isUser ? 'flex-row-reverse' : ''}`;
                
                let iconHtml = !isUser 
                    ? `<div class="w-8 h-8 rounded-full bg-[#1B3FA6] flex-shrink-0 flex items-center justify-center text-white mt-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg></div>`
                    : `<div class="w-8 h-8 rounded-full bg-[#2563EB] flex-shrink-0 flex items-center justify-center text-white mt-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg></div>`;

                const bubbleClass = isUser 
                    ? 'bg-[#2563EB] text-white p-3 rounded-2xl rounded-tr-none shadow-sm text-sm leading-relaxed max-w-[85%]' 
                    : 'bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-[#DBEAFE]/50 text-sm text-[#475569] leading-relaxed max-w-[85%]';
                
                let formattedContent = content.replace(/\n/g, '<br>').replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');

                wrapper.innerHTML = `${iconHtml}<div class="${bubbleClass}">${formattedContent}</div>`;
                chatBody.appendChild(wrapper);
                scrollToBottom();
            }

            function hideQuickQuestions() {
                const quickQuestions = document.getElementById('ai-quick-questions');
                if (quickQuestions) {
                    quickQuestions.classList.add('hidden');
                }
            }

            function showTyping() {
                const wrapper = document.createElement('div');
                wrapper.id = 'ai-typing-indicator';
                wrapper.className = `flex items-start gap-3`;
                wrapper.innerHTML = `
                    <div class="w-8 h-8 rounded-full bg-[#1B3FA6] flex-shrink-0 flex items-center justify-center text-white mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                    <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-[#DBEAFE]/50 flex items-center gap-1">
                        <div class="w-1.5 h-1.5 bg-[#2563EB] rounded-full animate-bounce"></div>
                        <div class="w-1.5 h-1.5 bg-[#2563EB] rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        <div class="w-1.5 h-1.5 bg-[#2563EB] rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                    </div>
                `;
                chatBody.appendChild(wrapper);
                scrollToBottom();
            }

            function removeTyping() {
                const indicator = document.getElementById('ai-typing-indicator');
                if (indicator) indicator.remove();
            }

            // Tambahkan pesan user
            addMessage(message, true);
            hideQuickQuestions();
            chatInput.value = '';
            chatInput.disabled = true;
            sendBtn.disabled = true;

            showTyping();

            try {
                const response = await fetch('{{ route("chat.ask") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: message,
                        history: window.aiChatHistory.slice(-6)
                    })
                });

                const data = await response.json();
                removeTyping();

                if (data.success) {
                    addMessage(data.reply, false);
                    window.aiChatHistory.push({ role: 'user', content: message });
                    window.aiChatHistory.push({ role: 'assistant', content: data.reply });
                } else {
                    addMessage(data.message || 'Maaf, terjadi kesalahan.', false);
                }
            } catch (error) {
                removeTyping();
                addMessage('Gagal terhubung ke server. Silakan periksa koneksi internet Anda.', false);
            } finally {
                chatInput.disabled = false;
                sendBtn.disabled = false;
                chatInput.focus();
            }
        }
    });
</script>
