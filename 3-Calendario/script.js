const CalendarAPI = {
    addEvent: function(year, month, day, description) {
        const events = getEvents();
        const dateKey = `${year}-${month + 1}-${day}`;
        if (!events[dateKey]) {
            events[dateKey] = [];
        }
        events[dateKey].push(description);
        saveEvents(events);
        renderCalendar(currentMonth);
    },

    getEventsForDate: function(year, month, day) {
        const events = getEvents();
        const dateKey = `${year}-${month + 1}-${day}`;
        return events[dateKey] || [];
    },

    clearEvents: function() {
        localStorage.removeItem('calendarEvents');
        renderCalendar(currentMonth);
    }
};

function getEvents() {
    return JSON.parse(localStorage.getItem('calendarEvents')) || {};
}

function saveEvents(events) {
    localStorage.setItem('calendarEvents', JSON.stringify(events));
}

let currentMonth = new Date().getMonth(); // Começa no mês atual

// Renderiza o calendário para um mês específico
function renderCalendar(month) {
    const container = document.getElementById('calendar-container');
    container.innerHTML = '';
    const events = getEvents();
    const today = new Date();
    const monthDate = new Date(2026, month, 1);

    const monthDiv = document.createElement('div');
    monthDiv.classList.add('month');

    const monthTitle = document.createElement('h2');
    monthTitle.textContent = monthDate.toLocaleString('pt-BR', { month: 'long', year: 'numeric' });
    monthDiv.appendChild(monthTitle);

    const table = document.createElement('table');
    table.classList.add('table');
    const thead = document.createElement('thead');
    const headerRow = document.createElement('tr');
    ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'].forEach(day => {
        const th = document.createElement('th');
        th.textContent = day;
        headerRow.appendChild(th);
    });
    thead.appendChild(headerRow);
    table.appendChild(thead);

    const tbody = document.createElement('tbody');
    const firstDay = monthDate.getDay();
    const daysInMonth = new Date(2026, month + 1, 0).getDate();

    let row = document.createElement('tr');
    for (let j = 0; j < firstDay; j++) {
        const emptyTd = document.createElement('td');
        row.appendChild(emptyTd);
    }

    for (let day = 1; day <= daysInMonth; day++) {
        if (row.children.length === 7) {
            tbody.appendChild(row);
            row = document.createElement('tr');
        }
        const td = document.createElement('td');
        td.classList.add('day');
        td.innerHTML = day;

        if (today.getFullYear() === 2026 && today.getMonth() === month && today.getDate() === day) {
            td.classList.add('today');
        }

        const dateKey = `${2026}-${month + 1}-${day}`;
        if (events[dateKey]) {
            events[dateKey].forEach(desc => {
                const eventSpan = document.createElement('div');
                eventSpan.classList.add('event');
                eventSpan.textContent = desc;
                td.appendChild(eventSpan);
            });
        }

        td.addEventListener('click', () => {
            const desc = prompt('Digite o evento pro dia ' + day + ':');
            if (desc) {
                CalendarAPI.addEvent(2026, month, day, desc);
            }
        });

        row.appendChild(td);
    }
    tbody.appendChild(row);
    table.appendChild(tbody);
    monthDiv.appendChild(table);
    container.appendChild(monthDiv);

    // Atualiza o seletor
    document.getElementById('month-selector').value = month;
}

// Navegação
document.getElementById('prev-month').addEventListener('click', () => {
    currentMonth = (currentMonth - 1 + 12) % 12;
    renderCalendar(currentMonth);
});

document.getElementById('next-month').addEventListener('click', () => {
    currentMonth = (currentMonth + 1) % 12;
    renderCalendar(currentMonth);
});

document.getElementById('month-selector').addEventListener('change', (e) => {
    currentMonth = parseInt(e.target.value);
    renderCalendar(currentMonth);
});

// Inicia o calendário
renderCalendar(currentMonth);