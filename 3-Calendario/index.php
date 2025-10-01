<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário do Tilambucano - 2026</title>
    <style>
   body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
    margin: 0;
    padding: 20px;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
}

h1 {
    color: #1a3c6d;
    font-size: clamp(1.8rem, 5vw, 2.2rem);
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.controls {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    align-items: center;
}

#month-selector {
    padding: 8px;
    font-size: clamp(0.9rem, 2.5vw, 1rem);
    border-radius: 6px;
    border: 1px solid #ccc;
    background-color: #fff;
    cursor: pointer;
}

button {
    padding: 8px 15px;
    font-size: clamp(0.9rem, 2.5vw, 1rem);
    border: none;
    background-color: #007bff;
    color: white;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

button:hover {
    background-color: #005bb5;
}

#calendar-container {
    display: flex;
    justify-content: center;
    width: 100%;
    max-width: 800px;
}

.month {
    background-color: #fff;
    border-radius: 14px;
    overflow-x: auto;
    overflow-y: hidden;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
    transition: transform 0.3s ease;
    width: 100%;
    min-height: 500px;
    white-space: nowrap;
}

.month:hover {
    transform: translateY(-8px);
}

.month h2 {
    margin: 0;
    padding: 20px;
    background: linear-gradient(to right, #003087, #005bb5);
    color: white;
    font-size: clamp(1.2rem, 3vw, 1.4rem);
    text-align: center;
}

.table {
    width: 100%;
    border-collapse: collapse;
    display: inline-table;
    min-width: 800px;
    table-layout: fixed;
}

.table th, .table td {
    padding: 20px;
    text-align: center;
    font-size: clamp(1rem, 2.5vw, 1.2rem);
    min-width: 120px;
    word-wrap: break-word;
    overflow: hidden;
}

.table th {
    background-color: #e9ecef;
    color: #333;
    font-weight: 600;
}

.day {
    cursor: pointer;
    position: relative;
    background-color: #fff;
    transition: background-color 0.2s ease;
    min-height: 80px;
    word-wrap: break-word;
    overflow: hidden;
}

.day:hover {
    background-color: #e3f2fd;
}

.day.today {
    background-color: #007bff;
    color: white;
    font-weight: bold;
    border-radius: 6px;
}

.event {
    background-color: #ff9500;
    color: white;
    font-size: clamp(0.8rem, 2vw, 0.9rem);
    margin-top: 5px;
    padding: 5px 8px;
    border-radius: 4px;
    word-wrap: break-word;
    max-width: 100%;
    overflow: hidden;
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.event .delete-event {
    background-color: #ff4444;
    padding: 2px 6px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    font-size: 0.7rem;
    margin-left: 5px;
    white-space: nowrap;
}

.event .delete-event:hover {
    background-color: #cc0000;
}

@media (max-width: 800px) {
    #calendar-container { padding: 0 10px; max-width: 600px; }
    .month { min-height: 400px; }
    .table th, .table td { padding: 15px; min-width: 100px; }
    .table { min-width: 600px; }
}

@media (max-width: 600px) {
    #calendar-container { padding: 0 10px; }
    .month { max-width: 100%; min-height: 350px; }
    .table th, .table td { padding: 10px; min-width: 80px; }
    .table { min-width: 480px; }
    .controls { flex-direction: column; gap: 8px; }
    button, #month-selector { width: 100%; max-width: 200px; }
}
    </style>
</head>
<body>
    <h1>Calendário Brabo - 2026</h1>
    <div class="controls">
        <button id="prev-month">◄ Anterior</button>
        <select id="month-selector">
            <option value="0">Janeiro</option>
            <option value="1">Fevereiro</option>
            <option value="2">Março</option>
            <option value="3">Abril</option>
            <option value="4">Maio</option>
            <option value="5">Junho</option>
            <option value="6">Julho</option>
            <option value="7">Agosto</option>
            <option value="8">Setembro</option>
            <option value="9">Outubro</option>
            <option value="10">Novembro</option>
            <option value="11">Dezembro</option>
        </select>
        <button id="next-month">Próximo ►</button>
    </div>
    <div id="calendar-container"></div>

    <script>
        console.log('Script carregado!');

        const CalendarAPI = {
            addEvent: function(year, month, day, description) {
                console.log(`Adicionando evento: ${year}-${month + 1}-${day} - ${description}`);
                const events = getEvents();
                const dateKey = `${year}-${month + 1}-${day}`;
                if (!events[dateKey]) events[dateKey] = [];
                events[dateKey].push(description);
                saveEvents(events);
                renderCalendar(currentMonth);
            },
            getEventsForDate: function(year, month, day) {
                const events = getEvents();
                const dateKey = `${year}-${month + 1}-${day}`;
                return events[dateKey] || [];
            },
            deleteEvent: function(year, month, day, description) {
                console.log(`Apagando evento: ${year}-${month + 1}-${day} - ${description}`);
                const events = getEvents();
                const dateKey = `${year}-${month + 1}-${day}`;
                if (events[dateKey]) {
                    const index = events[dateKey].indexOf(description);
                    if (index > -1) {
                        events[dateKey].splice(index, 1);
                        if (events[dateKey].length === 0) delete events[dateKey];
                        saveEvents(events);
                        renderCalendar(currentMonth);
                    }
                }
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

        let currentMonth = new Date().getMonth(); // Começa no mês atual de 2025, mas ajusta pra 2026

        function renderCalendar(month) {
            console.log(`Renderizando mês: ${month}`);
            const container = document.getElementById('calendar-container');
            if (!container) {
                console.error('Container #calendar-container não encontrado!');
                return;
            }
            container.innerHTML = '';
            const events = getEvents();
            const today = new Date();
            const monthDate = new Date(2026, month, 1); // Fixado em 2026
            const daysInMonth = new Date(2026, month + 1, 0).getDate();

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
            const firstDay = new Date(2026, month, 1).getDay();

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
                        const eventDiv = document.createElement('div');
                        eventDiv.classList.add('event');
                        eventDiv.textContent = desc;
                        const deleteButton = document.createElement('button');
                        deleteButton.textContent = 'X';
                        deleteButton.classList.add('delete-event');
                        deleteButton.addEventListener('click', (e) => {
                            e.stopPropagation();
                            if (confirm('Tem certeza que quer apagar este evento?')) {
                                CalendarAPI.deleteEvent(2026, month, day, desc);
                            }
                        });
                        eventDiv.appendChild(deleteButton);
                        td.appendChild(eventDiv);
                    });
                }

                td.addEventListener('click', () => {
                    const desc = prompt('Digite o evento pro dia ' + day + ':');
                    if (desc) CalendarAPI.addEvent(2026, month, day, desc);
                });

                row.appendChild(td);
            }
            if (row.children.length > 0) tbody.appendChild(row);
            table.appendChild(tbody);
            monthDiv.appendChild(table);
            container.appendChild(monthDiv);

            const selector = document.getElementById('month-selector');
            if (selector) selector.value = month;
            else console.error('Seletor #month-selector não encontrado!');
        }

        document.addEventListener('DOMContentLoaded', () => {
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

            renderCalendar(currentMonth);
            console.log('Barra de rolagem e navegação até dezembro de 2026 ativadas!');
        });
    </script>
</body>
</html>