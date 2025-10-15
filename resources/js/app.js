import './bootstrap';
import '../css/app.css';

// Alpine.js store para el carrito
document.addEventListener('alpine:init', () => {
    Alpine.store('cart', {
        count: 0,
        
        init() {
            // Inicializar el contador
            this.updateCount();
            
            // Escuchar eventos de actualización del carrito
            window.addEventListener('cart-count-updated', () => {
                this.updateCount();
            });

            // Escuchar eventos Livewire
            Livewire.hook('message.processed', (message, component) => {
                if (message.updateQueue.some(update => update.type === 'callMethod' && 
                    ['addToCart', 'increment', 'decrement', 'remove', 'clearCart'].includes(update.payload.method))) {
                    this.updateCount();
                }
            });
        },
        
        updateCount() {
            // Hacer una petición para obtener el conteo actualizado
            fetch('/cart/count')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    this.count = data.count;
                })
                .catch(error => {
                    console.error('Error fetching cart count:', error);
                    this.count = 0;
                });
        }
    });
});

// Sistema de notificaciones simple
window.showNotification = function(message, type = 'info') {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white z-50 transform transition-transform duration-300 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        type === 'warning' ? 'bg-yellow-500' : 
        'bg-blue-500'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remover después de 3 segundos
    setTimeout(() => {
        notification.remove();
    }, 3000);
};

// Escuchar eventos de notificación de Livewire
document.addEventListener('livewire:initialized', () => {
    Livewire.on('notify', (data) => {
        showNotification(data.message, data.type);
    });
});