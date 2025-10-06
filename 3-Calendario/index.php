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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            color: #1a3c6d;
            font-size: 2.5rem;
            margin-bottom: 20px;
            text-align: center;
            text-transform: uppercase;
        }

        .controls {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
            align-items: center;
            flex-wrap: wrap;
        }

        #month-selector {
            padding: 12px;
            font-size: 1.1rem;
            border-radius: 8px;
            border: 2px solid #007bff;
            background-color: #fff;
            cursor: pointer;
        }

        button {
            padding: 12px 20px;
            font-size: 1.1rem;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #005bb5;
        }

        .calendar {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .calendar-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .calendar-header h2 {
            color: #1a3c6d;
            font-size: 2rem;
            margin: 0;
        }

        .week-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            margin-bottom: 10px;
        }

        .week-day {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            font-weight: bold;
            color: #333;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
        }

        .calendar-day {
            min-height: 120px;
            background: #fff;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .calendar-day:hover {
            border-color: #007bff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
        }

        .calendar-day.empty {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            cursor: default;
        }

        .calendar-day.empty:hover {
            transform: none;
            border-color: #dee2e6;
            box-shadow: none;
        }

        .day-number {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }

        .calendar-day.today .day-number {
            color: #007bff;
            background: #e6f0fa;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
        }

        .events {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 5px;
            overflow-y: auto;
            max-height: 80px;
        }

        .event {
            background: #ff9500;
            color: #000;
            padding: 6px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            word-wrap: break-word;
            position: relative;
            cursor: pointer;
        }

        .event-text {
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .event-tooltip {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            white-space: nowrap;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            margin-bottom: 5px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .event-tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 6px solid transparent;
            border-top-color: #333;
        }

        .event:hover .event-tooltip {
            opacity: 1;
            visibility: visible;
        }

        .delete-event {
            background: #ff4444;
            border: none;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.7rem;
            margin-left: 5px;
        }

        .delete-event:hover {
            background: #cc0000;
        }

        /* Novo: Estilo para feriados */
        .holiday-badge {
            background: #28a745;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            margin-top: 5px;
            text-align: center;
            word-wrap: break-word;
            max-width: 100%;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .calendar-day {
                min-height: 100px;
                padding: 8px;
            }
            
            .week-day, .day-number {
                font-size: 0.9rem;
            }
            
            .event {
                font-size: 0.75rem;
                padding: 4px 6px;
            }
            
            .event-tooltip {
                font-size: 0.7rem;
                padding: 6px 10px;
            }
            
            .holiday-badge {
                font-size: 0.7rem;
                padding: 3px 6px;
            }
        }

        @media (max-width: 480px) {
            .calendar-day {
                min-height: 80px;
            }
            
            .week-days, .calendar-grid {
                gap: 4px;
            }
            
            .week-day, .calendar-day {
                padding: 8px 4px;
            }
            
            .holiday-badge {
                font-size: 0.65rem;
                padding: 2px 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
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

        <div class="calendar">
            <div class="calendar-header">
                <h2 id="current-month">Janeiro 2026</h2>
            </div>
            
            <div class="week-days">
                <div class="week-day">Dom</div>
                <div class="week-day">Seg</div>
                <div class="week-day">Ter</div>
                <div class="week-day">Qua</div>
                <div class="week-day">Qui</div>
                <div class="week-day">Sex</div>
                <div class="week-day">Sáb</div>
            </div>
            
            <div class="calendar-grid" id="calendar-grid">
                <!-- Os dias serão gerados pelo JavaScript -->
            </div>
        </div>
    </div>

    <script>
        let currentMonth = new Date().getMonth();
        const currentYear = 2026;
        let holidays = {}; // Armazena os feriados: { 'YYYY-MM-DD': 'Nome do Feriado' }

        // Nova função: Carrega feriados da API
        async function loadHolidays() {
            try {
                console.log('Carregando feriados de 2026...');
                const response = await fetch(`https://brasilapi.com.br/api/feriados/v1/${currentYear}`);
                if (!response.ok) throw new Error('Erro na API');
                const data = await response.json();
                data.forEach(holiday => {
                    const dateKey = holiday.date;
                    holidays[dateKey] = holiday.name;
                });
                console.log(`Feriados carregados: ${Object.keys(holidays).length}`);
                renderCalendar(currentMonth);
            } catch (error) {
                console.error('Erro ao carregar feriados:', error);
                // Fallback com feriados manuais
                holidays = {
                    '2026-01-01': 'Confraternização Universal',
                    '2026-04-21': 'Tiradentes',
                    '2026-05-01': 'Dia do Trabalho',
                    '2026-06-19': 'Corpus Christi',
                    '2026-09-07': 'Independência do Brasil',
                    '2026-10-12': 'Nossa Senhora Aparecida',
                    '2026-11-02': 'Finados',
                    '2026-11-15': 'Proclamação da República',
                    '2026-12-25': 'Natal'
                };
                renderCalendar(currentMonth);
            }
        }

        const CalendarAPI = {
            addEvent: function(year, month, day, description) {
                const events = getEvents();
                const dateKey = `${year}-${month + 1}-${day}`;
                if (!events[dateKey]) events[dateKey] = [];
                events[dateKey].push(description);
                saveEvents(events);
                renderCalendar(currentMonth);
            },
            deleteEvent: function(year, month, day, description) {
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
            }
        };

        function getEvents() {
            return JSON.parse(localStorage.getItem('calendarEvents')) || {};
        }

        function saveEvents(events) {
            localStorage.setItem('calendarEvents', JSON.stringify(events));
        }

        function renderCalendar(month) {
            const calendarGrid = document.getElementById('calendar-grid');
            const currentMonthElement = document.getElementById('current-month');
            const monthNames = [
                'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
            ];

            // Atualiza o título do mês
            currentMonthElement.textContent = `${monthNames[month]} ${currentYear}`;

            // Limpa o calendário
            calendarGrid.innerHTML = '';

            // Calcula o primeiro dia do mês e quantos dias tem o mês
            const firstDay = new Date(currentYear, month, 1).getDay();
            const daysInMonth = new Date(currentYear, month + 1, 0).getDate();
            const today = new Date();

            // Adiciona dias vazios no início
            for (let i = 0; i < firstDay; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.classList.add('calendar-day', 'empty');
                calendarGrid.appendChild(emptyDay);
            }

            // Adiciona os dias do mês
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('div');
                dayElement.classList.add('calendar-day');
                
                // Verifica se é hoje
                if (today.getFullYear() === currentYear && 
                    today.getMonth() === month && 
                    today.getDate() === day) {
                    dayElement.classList.add('today');
                }

                // Número do dia
                const dayNumber = document.createElement('div');
                dayNumber.classList.add('day-number');
                dayNumber.textContent = day;
                dayElement.appendChild(dayNumber);

                // Container de eventos
                const eventsContainer = document.createElement('div');
                eventsContainer.classList.add('events');
                dayElement.appendChild(eventsContainer);

                // Adiciona feriados
                const dateKey = `${currentYear}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                if (holidays[dateKey]) {
                    const holidayBadge = document.createElement('div');
                    holidayBadge.classList.add('holiday-badge');
                    holidayBadge.textContent = holidays[dateKey];
                    eventsContainer.appendChild(holidayBadge);
                }

                // Adiciona eventos existentes
                const events = getEvents();
                if (events[dateKey]) {
                    events[dateKey].forEach(eventDesc => {
                        const eventElement = document.createElement('div');
                        eventElement.classList.add('event');
                        
                        const eventText = document.createElement('span');
                        eventText.classList.add('event-text');
                        eventText.textContent = eventDesc.length > 20 ? 
                            eventDesc.substring(0, 20) + '...' : eventDesc;
                        
                        const tooltip = document.createElement('div');
                        tooltip.classList.add('event-tooltip');
                        tooltip.textContent = eventDesc;
                        
                        const deleteBtn = document.createElement('button');
                        deleteBtn.classList.add('delete-event');
                        deleteBtn.textContent = 'X';
                        deleteBtn.onclick = (e) => {
                            e.stopPropagation();
                            if (confirm('Apagar este evento?')) {
                                CalendarAPI.deleteEvent(currentYear, month, day, eventDesc);
                            }
                        };
                        
                        eventElement.appendChild(eventText);
                        eventElement.appendChild(tooltip);
                        eventElement.appendChild(deleteBtn);
                        eventsContainer.appendChild(eventElement);
                    });
                }

                // Click para adicionar evento
                dayElement.onclick = () => {
                    const eventDesc = prompt(`Adicionar evento para ${day}/${month + 1}/${currentYear}:`);
                    if (eventDesc && eventDesc.trim()) {
                        CalendarAPI.addEvent(currentYear, month, day, eventDesc.trim());
                    }
                };

                calendarGrid.appendChild(dayElement);
            }
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', () => {
            loadHolidays(); // Carrega feriados ao iniciar

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
        });

        // Inicializa o calendário após carregar feriados (já chamado em loadHolidays)
    </script>
</body>
</html>