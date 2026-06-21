<script>
    (() => {
        // Helper function to create and show notification banner
        const showNotificationBanner = (message, type = 'success') => {
            const containerId = 'notify-banner-container';
            let container = document.getElementById(containerId);

            if (!container) {
                // Calculate navbar height
                const navbar = document.querySelector('.navbar');
                const navbarHeight = navbar ? navbar.offsetHeight : 70;
                
                container = document.createElement('div');
                container.id = containerId;
                container.style.cssText = [
                    'position:fixed',
                    `top:${navbarHeight + 16}px`,
                    'left:0',
                    'right:0',
                    'display:flex',
                    'flex-direction:column',
                    'align-items:center',
                    'gap:8px',
                    'justify-content:center',
                    'z-index:1080',
                    'pointer-events:none',
                ].join(';');
                document.body.appendChild(container);
            }

            const palette = {
                success: { bg: '#d7f0dd', text: '#14532d', border: '#b6e3c2' },
                danger: { bg: '#fde4e5', text: '#7f1d1d', border: '#f8c7c9' },
                error: { bg: '#fde4e5', text: '#7f1d1d', border: '#f8c7c9' },
                info: { bg: '#e0f2fe', text: '#0b3c78', border: '#b9e0fb' },
            };

            const { bg, text, border } = palette[type] ?? palette.success;
            const banner = document.createElement('div');
            banner.role = 'alert';
            banner.style.cssText = [
                'min-width:320px',
                'max-width:640px',
                'background:' + bg,
                'color:' + text,
                'border:1px solid ' + border,
                'border-radius:10px',
                'padding:14px 18px',
                'box-shadow:0 4px 12px rgba(0,0,0,0.08)',
                'display:flex',
                'align-items:flex-start',
                'gap:12px',
                'pointer-events:auto',
                'font-size:15px',
                'line-height:1.4',
                'animation:slideInDown 0.3s ease-out',
            ].join(';');

            banner.innerHTML = `
                <div style="flex:1; font-weight:500;">${message}</div>
                <button type="button" aria-label="Close"
                    style="background:none;border:0;color:${text};font-size:18px;line-height:1;cursor:pointer;opacity:0.7;">
                    &times;
                </button>
            `;

            const closeBtn = banner.querySelector('button');
            closeBtn.addEventListener('click', () => banner.remove());

            container.appendChild(banner);

            setTimeout(() => banner.remove(), 5000);
        };

        // Handle session-based messages
        const messages = [];

        @if (session('success'))
            messages.push({ message: @js(session('success')), type: 'success' });
        @endif

        @if (session('error'))
            messages.push({ message: @js(session('error')), type: 'danger' });
        @endif

        @if ($errors->any())
            messages.push({ message: `{!! implode('<br>', $errors->all()) !!}`, type: 'danger' });
        @endif

        const unique = [];
        const seen = new Set();
        messages.forEach((payload) => {
            const key = `${payload.type}:${payload.message}`;
            if (seen.has(key)) {
                return;
            }
            seen.add(key);
            unique.push(payload);
        });

        // Show session-based notifications
        if (unique.length > 0) {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    unique.forEach(({ message, type }) => showNotificationBanner(message, type));
                }, { once: true });
            } else {
                unique.forEach(({ message, type }) => showNotificationBanner(message, type));
            }
        }

        // Listen for Livewire notify events
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('notify', (event) => {
                const type = event.type || event[0]?.type || 'success';
                const message = event.message || event[0]?.message || 'Action completed';
                showNotificationBanner(message, type);
            });
        });
    })();

    // Add CSS animation for slide-in effect
    if (!document.getElementById('notify-animation-styles')) {
        const style = document.createElement('style');
        style.id = 'notify-animation-styles';
        style.textContent = `
            @keyframes slideInDown {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
    }
</script>
