document.addEventListener('DOMContentLoaded', function() {
    const CONFIG = {
        api_url: '/api/events/counts',
        debug: true
    };

    const calendarGrid = document.getElementById('calendar-grid');
    const monthYearEl = document.getElementById('calendar-month-year');
    const prevMonthBtn = document.getElementById('prev-month');
    const nextMonthBtn = document.getElementById('next-month');

    let currentYear = new Date().getFullYear();
    let currentMonth = new Date().getMonth(); // 0-11

    function log(...args) {
        if (CONFIG.debug) {
            console.log('[Calendar]', ...args);
        }
    }

    /**
     * Форматирует дату в YYYY-MM-DD (локальное время)
     */
    function formatDateKey(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    /**
     * Возвращает цвет фона в зависимости от количества событий
     */
    function getColorForCount(count) {
        if (!count || count <= 0) return null;
        const intensity = Math.min(5, count);
        return `rgba(182, 0, 214, ${0.2 * intensity})`;
    }

    /**
     * Склонение слов: 1 мероприятие, 2 мероприятия, 5 мероприятий
     */
    function getEventTitle(count) {
        if (!count || count <= 0) return '';

        const lastDigit = count % 10;
        const lastTwoDigits = count % 100;

        if (lastTwoDigits >= 11 && lastTwoDigits <= 19) {
            return `${count} мероприятий`;
        }
        if (lastDigit === 1) {
            return `${count} мероприятие`;
        }
        if (lastDigit >= 2 && lastDigit <= 4) {
            return `${count} мероприятия`;
        }
        return `${count} мероприятий`;
    }

    /**
     * Загружает данные о событиях
     */
    async function loadCalendarData(year, month) {
        const apiMonth = month + 1;
        const eventsUrl = `${CONFIG.api_url}?year=${year}&month=${apiMonth}`;

        log('Запрос событий:', eventsUrl);

        try {
            const response = await fetch(eventsUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            const eventsData = response.ok ? await response.json() : {};
            log('События получены:', eventsData);

            // Нормализуем формат событий (если пришёл массив)
            let normalizedEvents = eventsData;
            if (Array.isArray(eventsData)) {
                normalizedEvents = {};
                eventsData.forEach(item => {
                    if (item.date && item.count !== undefined) {
                        normalizedEvents[item.date] = item.count;
                    }
                });
            }

            return { events: normalizedEvents };

        } catch (error) {
            console.error('Ошибка загрузки данных:', error);
            return { events: {} };
        }
    }

    /**
     * Генерирует сетку календаря с цветными ячейками
     */
    function generateCalendar(year, month, data) {
        const eventsData = data.events || {};

        log('Отрисовка календаря:', year, month + 1);
        log('Данные событий:', eventsData);

        const monthNames = [
            "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь",
            "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"
        ];
        const dayNames = ["Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"];

        // Показываем индикатор загрузки
        if (calendarGrid) {
            calendarGrid.classList.add('loading');
        }

        const firstDay = new Date(year, month, 1);
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const startingDay = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1;

        // Обновляем заголовок
        if (monthYearEl) {
            monthYearEl.textContent = `${monthNames[month]} ${year}`;
        }

        // Очищаем сетку
        if (calendarGrid) {
            calendarGrid.innerHTML = '';
        }

        // Заголовки дней недели
        dayNames.forEach(day => {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day-header';
            dayElement.textContent = day;
            calendarGrid.appendChild(dayElement);
        });

        // Пустые ячейки до первого дня месяца
        for (let i = 0; i < startingDay; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'calendar-day empty';
            calendarGrid.appendChild(emptyDay);
        }

        // Дни месяца
        let coloredCount = 0;

        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const dateString = formatDateKey(date);

            // Получаем данные
            const eventCount = eventsData[dateString] || 0;

            // Отладка первых 5 дней
            if (CONFIG.debug && day <= 5) {
                log(`📅 День ${day}:`, {
                    dateString: dateString,
                    eventCount: eventCount,
                    hasEvents: eventCount > 0
                });
            }

            // Создаём ячейку
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';
            dayElement.textContent = day;
            dayElement.dataset.date = dateString;

            // Обработка событий
            if (eventCount > 0) {
                const color = getColorForCount(eventCount);
                dayElement.style.backgroundColor = color;
                dayElement.style.setProperty('background-color', color, 'important');
                dayElement.classList.add('has-events');

                // Устанавливаем тултип и aria-label
                dayElement.title = getEventTitle(eventCount);
                dayElement.setAttribute('aria-label', getEventTitle(eventCount));

                coloredCount++;

                // Клик по ячейке
                dayElement.addEventListener('click', function() {
                    showEventsForDate(dateString, eventCount);
                });
            }

            // Подсветка текущего дня
            const today = new Date();
            if (day === today.getDate() &&
                month === today.getMonth() &&
                year === today.getFullYear()) {
                dayElement.classList.add('is-today');
            }

            calendarGrid.appendChild(dayElement);
        }

        if (calendarGrid) {
            calendarGrid.classList.remove('loading');
        }

        log(`Календарь отрисован. Событий: ${coloredCount}`);
    }

    function showEventsForDate(dateString, count) {
        log(`События за ${dateString}: ${count}`);

        let message = `${dateString}\n`;
        if (count > 0) {
            message += `${getEventTitle(count)}\n`;
        }

        alert(message);
    }

    /**
     * Переключаем месяц и перерисовываем календарь
     */
    async function changeMonth(delta) {
        currentMonth += delta;

        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        } else if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }

        log('Переключение месяца:', currentYear, currentMonth + 1);
        await renderCalendar();
    }

    /**
     * Основная функция отрисовки (загружает данные + рисует)
     */
    async function renderCalendar() {
        const data = await loadCalendarData(currentYear, currentMonth);
        generateCalendar(currentYear, currentMonth, data);
    }

    /**
     * Запускает календарь после загрузки страницы
     */
    async function init() {
        log('Инициализация календаря...');

        // Проверяем наличие элементов
        if (!calendarGrid) {
            console.error('Элемент #calendar-grid не найден!');
            return;
        }

        if (!monthYearEl) {
            console.warn('Элемент #calendar-month-year не найден!');
        }

        // Навешиваем обработчики кнопок
        if (prevMonthBtn) {
            prevMonthBtn.addEventListener('click', () => changeMonth(-1));
            prevMonthBtn.style.cursor = 'pointer';
        }

        if (nextMonthBtn) {
            nextMonthBtn.addEventListener('click', () => changeMonth(1));
            nextMonthBtn.style.cursor = 'pointer';
        }

        // Первая отрисовка
        await renderCalendar();

        log('Календарь готов!');
    }

    // Запуск
    init();
});
