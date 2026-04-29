// Auto-calculate booking price
document.addEventListener('DOMContentLoaded', function () {
    const start = document.querySelector('input[name="start_date"]');
    const end   = document.querySelector('input[name="end_date"]');

    if (start && end) {
        function calcDays() {
            const s = new Date(start.value);
            const e = new Date(end.value);
            if (s && e && e > s) {
                const days = Math.ceil((e - s) / 86400000);
                const info = document.getElementById('day-info');
                if (info) info.textContent = days + ' day(s) selected';
            }
        }
        start.addEventListener('change', calcDays);
        end.addEventListener('change', calcDays);
    }
});