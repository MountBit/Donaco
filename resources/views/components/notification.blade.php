<!-- Notification Container -->
<div id="notification-container" class="fixed top-4 right-4 z-50 pointer-events-none"></div>

@if(session('success'))
    <input type="hidden" id="success-message" value="{{ session('success') }}">
@endif
@if(session('error'))
    <input type="hidden" id="error-message" value="{{ session('error') }}">
@endif

@push('scripts')
<script>
    // Definir a função globalmente
    window.showNotification = function(message, type) {
        const successIcon = `
            <div class="rounded-full bg-white bg-opacity-20 p-2 mr-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>`;
        
        const errorIcon = `
            <div class="rounded-full bg-white bg-opacity-20 p-2 mr-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>`;

        const icon = type === 'success' ? successIcon : errorIcon;
        const baseClass = type === 'success' ? 'from-green-500 to-green-600' : 'from-red-500 to-red-600';
        
        const notification = $(`
            <div class="notification transform transition-all duration-300 ease-out opacity-0 translate-x-8 pointer-events-auto">
                <div class="relative bg-gradient-to-r ${baseClass} rounded-lg p-4 min-w-[300px] backdrop-blur-sm shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-white/10">
                    <div class="flex items-center">
                        ${icon}
                        <p class="text-white font-medium">${message}</p>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-white bg-opacity-20 rounded-b-lg overflow-hidden">
                        <div class="progress-bar h-full bg-white bg-opacity-40" 
                             style="width: 100%; transition: width 3s linear;"></div>
                    </div>
                </div>
            </div>
        `).appendTo('#notification-container');

        // Mostrar notificação com animação
        requestAnimationFrame(() => {
            notification.css({
                'opacity': '1',
                'transform': 'translateX(0)'
            });
            
            // Iniciar animação da barra de progresso
            requestAnimationFrame(() => {
                notification.find('.progress-bar').css('width', '0%');
            });
        });

        // Auto-dismiss após 3 segundos
        setTimeout(() => {
            notification.css({
                'opacity': '0',
                'transform': 'translateX(8rem)'
            });
            
            // Remover elemento após a animação
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    };

    // Verificar e mostrar mensagens da sessão
    $(document).ready(function() {
        const successMessage = $('#success-message').val();
        const errorMessage = $('#error-message').val();

        if (successMessage) {
            showNotification(successMessage, 'success');
        }
        if (errorMessage) {
            showNotification(errorMessage, 'error');
        }
    });
</script>
@endpush
