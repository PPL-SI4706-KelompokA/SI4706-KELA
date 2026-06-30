import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Real-Time Dynamic Timestamp Handler
function updateRealTimeTimestamps() {
    const elements = document.querySelectorAll('.realtime-time');
    elements.forEach(el => {
        const timestampStr = el.getAttribute('data-timestamp');
        if (!timestampStr) return;
        
        // Parse date bulletproof across browsers
        let formatted = timestampStr;
        if (formatted.indexOf(' ') >= 0 && formatted.indexOf('T') < 0) {
            formatted = formatted.replace(' ', 'T');
        }
        const timestamp = new Date(formatted);
        if (isNaN(timestamp.getTime())) return;
        
        const diffMs = Date.now() - timestamp.getTime();
        const diffMins = Math.floor(diffMs / 60000);
        
        let text = '';
        if (diffMins < 1) {
            text = 'Baru saja';
        } else if (diffMins < 60) {
            text = `${diffMins} menit lalu`;
        } else if (diffMins < 1440) {
            const hours = Math.floor(diffMins / 60);
            text = `${hours} jam lalu`;
        } else {
            const days = Math.floor(diffMins / 1440);
            text = `${days} hari lalu`;
        }
        
        el.textContent = text;
    });
}

document.addEventListener('DOMContentLoaded', () => {
    updateRealTimeTimestamps();
    setInterval(updateRealTimeTimestamps, 10000); // Update every 10 seconds
});

window.updateRealTimeTimestamps = updateRealTimeTimestamps;
