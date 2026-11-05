(function () {
    'use strict';
    const root = document.getElementById('ambientesCalendar');
    if (!root) return;

    const title = document.getElementById('calendarTitle');
    const filter = document.getElementById('calendarAmbiente');
    let current = new Date();
    current = new Date(current.getFullYear(), current.getMonth(), 1);

    const isoDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };
    const startOfGrid = () => {
        const date = new Date(current);
        const weekday = (date.getDay() + 6) % 7;
        date.setDate(date.getDate() - weekday);
        return date;
    };
    const eventDateKey = (value) => {
        const date = new Date(value);
        return isoDate(date);
    };
    const eventTime = (value) => new Intl.DateTimeFormat('es-CO', {hour: '2-digit', minute: '2-digit', hour12: false}).format(new Date(value));

    async function load() {
        root.innerHTML = '<div class="amb-calendar-loading"><i class="fas fa-spinner fa-spin"></i> Cargando calendario...</div>';
        title.textContent = new Intl.DateTimeFormat('es-CO', {month: 'long', year: 'numeric'}).format(current);
        const gridStart = startOfGrid();
        const gridEnd = new Date(gridStart); gridEnd.setDate(gridEnd.getDate() + 41);
        const params = new URLSearchParams({inicio: isoDate(gridStart), fin: isoDate(gridEnd)});
        if (filter.value) params.set('ambiente_id', filter.value);
        try {
            const response = await fetch(root.dataset.endpoint + '?' + params.toString(), {headers: {'X-Requested-With': 'XMLHttpRequest'}});
            const payload = await response.json();
            if (!response.ok || !payload.success) throw new Error(payload.message || 'No fue posible cargar el calendario.');
            render(gridStart, payload.events || []);
        } catch (error) {
            root.innerHTML = '';
            const box = document.createElement('div'); box.className = 'amb-calendar-error';
            box.textContent = error.message || 'No fue posible cargar el calendario.'; root.appendChild(box);
        }
    }

    function render(gridStart, events) {
        root.innerHTML = '';
        const calendar = document.createElement('div'); calendar.className = 'amb-calendar';
        ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'].forEach((name) => { const cell=document.createElement('div'); cell.className='amb-calendar-weekday'; cell.textContent=name; calendar.appendChild(cell); });
        const grouped = {};
        events.forEach((event) => { const key=eventDateKey(event.start); (grouped[key] ||= []).push(event); });
        const today = isoDate(new Date());
        for (let i=0; i<42; i++) {
            const date = new Date(gridStart); date.setDate(date.getDate()+i);
            const key = isoDate(date);
            const day = document.createElement('div'); day.className='amb-calendar-day';
            if (date.getMonth() !== current.getMonth()) day.classList.add('is-outside');
            if (key === today) day.classList.add('is-today');
            const number=document.createElement('div'); number.className='amb-calendar-date'; number.textContent=date.getDate(); day.appendChild(number);
            (grouped[key] || []).forEach((event) => {
                const button=document.createElement('button'); button.type='button'; button.className='amb-calendar-event';
                button.title=`${event.ambiente} · Responsable: ${event.responsable}`;
                const time=document.createElement('span'); time.className='amb-calendar-event-time'; time.textContent=`${eventTime(event.start)} - ${eventTime(event.end)}`;
                const eventTitle=document.createElement('span'); eventTitle.className='amb-calendar-event-title'; eventTitle.textContent=`${event.ambiente}: ${event.title}`;
                button.append(time,eventTitle);
                if (event.url) button.addEventListener('click',()=>{ window.location.href=event.url; }); else button.disabled=true;
                day.appendChild(button);
            });
            calendar.appendChild(day);
        }
        root.appendChild(calendar);
    }

    document.getElementById('calendarPrev').addEventListener('click',()=>{ current.setMonth(current.getMonth()-1); load(); });
    document.getElementById('calendarNext').addEventListener('click',()=>{ current.setMonth(current.getMonth()+1); load(); });
    document.getElementById('calendarToday').addEventListener('click',()=>{ const now=new Date(); current=new Date(now.getFullYear(),now.getMonth(),1); load(); });
    filter.addEventListener('change',load);
    load();
})();
