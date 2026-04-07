document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const eventModal = document.getElementById('eventModal');
    const eventForm = document.getElementById('eventForm');
    const addEventBtn = document.getElementById('addEventBtn');
    const closeBtn = document.querySelector('.close');
    const deleteEventBtn = document.getElementById('deleteEventBtn');
    const modalTitle = document.getElementById('modalTitle');

    // CSRF-токен для Laravel
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
        || document.querySelector('[name="csrf-token"]')?.content;

    // Инициализация календаря
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'ru',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'Сегодня',
            month: 'Месяц',
            week: 'Неделя',
            day: 'День'
        },
        editable: true,
        selectable: true,

        // Загрузка событий с сервера
        events: function(fetchInfo, successCallback, failureCallback) {
            fetch(`/api/events?start=${fetchInfo.startStr}&end=${fetchInfo.endStr}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => successCallback(data))
                .catch(error => {
                    console.error('Ошибка загрузки событий:', error);
                    failureCallback(error);
                });
        },

        select: function(info) {
            document.getElementById('eventStart').value = info.startStr.substring(0, 16);
            document.getElementById('eventEnd').value = info.end ? info.endStr.substring(0, 16) : '';
            modalTitle.textContent = 'Добавить событие';
            deleteEventBtn.style.display = 'none';
            openModal();
        },

        eventClick: function(info) {
            const event = info.event;
            document.getElementById('eventTitle').value = event.title;
            document.getElementById('eventStart').value = event.startStr.substring(0, 16);
            document.getElementById('eventEnd').value = event.end ? event.endStr.substring(0, 16) : '';
            document.getElementById('eventDescription').value = event.extendedProps.description || '';
            document.getElementById('eventColor').value = event.backgroundColor || '#7f3d9e';

            eventForm.dataset.eventId = event.id;
            modalTitle.textContent = 'Редактировать событие';
            deleteEventBtn.style.display = 'inline-block';
            openModal();
        },

        // Обработка перетаскивания событий
        eventDrop: function(info) {
            updateEventOnServer(info.event);
        },
        eventResize: function(info) {
            updateEventOnServer(info.event);
        }
    });

    calendar.render();

    // Функция обновления события на сервере
    function updateEventOnServer(event) {
        fetch(`/api/events/${event.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                name: event.title,
                date_start: event.startStr,
                date_end: event.endStr,
                color: event.backgroundColor
            })
        })
            .then(response => {
                if (!response.ok) throw new Error('Ошибка обновления');
                return response.json();
            })
            .catch(error => {
                console.error('Ошибка:', error);
                calendar.refetchEvents(); // откат изменений при ошибке
                alert('Не удалось сохранить изменения');
            });
    }

    function openModal() {
        eventModal.style.display = 'block';
    }

    function closeModal() {
        eventModal.style.display = 'none';
        eventForm.reset();
        delete eventForm.dataset.eventId;
        deleteEventBtn.style.display = 'none';
    }

    addEventBtn.addEventListener('click', function() {
        eventForm.reset();
        const now = new Date();
        const nowStr = now.toISOString().substring(0, 16);
        document.getElementById('eventStart').value = nowStr;
        document.getElementById('eventColor').value = '#9c2ee4';
        modalTitle.textContent = 'Добавить событие';
        deleteEventBtn.style.display = 'none';
        openModal();
    });

    closeBtn.addEventListener('click', closeModal);

    deleteEventBtn.addEventListener('click', function() {
        if (!eventForm.dataset.eventId) return;

        const eventId = eventForm.dataset.eventId;

        if (confirm('Вы уверены, что хотите удалить это событие?')) {
            fetch(`/api/events/${eventId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (response.ok) {
                        const event = calendar.getEventById(eventId);
                        if (event) event.remove();
                        closeModal();
                    } else {
                        throw new Error('Ошибка удаления');
                    }
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                    alert('Не удалось удалить событие');
                });
        }
    });

    window.addEventListener('click', function(event) {
        if (event.target === eventModal) {
            closeModal();
        }
    });

    // Обработчик отправки формы
    eventForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const title = document.getElementById('eventTitle').value.trim();
        const start = document.getElementById('eventStart').value;
        const end = document.getElementById('eventEnd').value;
        const description = document.getElementById('eventDescription').value.trim();
        const color = document.getElementById('eventColor').value;

        if (!title) {
            alert('Пожалуйста, введите название события');
            return;
        }

        const eventData = {
            name: title,
            date_start: start,
            date_end: end || null,
            description: description,
            color: color
        };

        const url = eventForm.dataset.eventId
            ? `/api/events/${eventForm.dataset.eventId}`
            : '/api/events';
        const method = eventForm.dataset.eventId ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(eventData)
        })
            .then(response => {
                if (!response.ok) return response.json().then(err => Promise.reject(err));
                return response.json();
            })
            .then(data => {
                // Обновляем календарь
                if (eventForm.dataset.eventId) {
                    const event = calendar.getEventById(eventForm.dataset.eventId);
                    if (event) {
                        event.setProp('title', data.title);
                        event.setStart(data.start);
                        event.setEnd(data.end);
                        event.setExtendedProp('description', data.description);
                        event.setProp('backgroundColor', data.backgroundColor);
                        event.setProp('borderColor', data.borderColor);
                    }
                } else {
                    calendar.addEvent(data);
                }
                closeModal();
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert(error.message || 'Произошла ошибка при сохранении');
            });
    });

    document.querySelectorAll('.fc-daygrid-day').forEach(el => {
        el.onclick = function () {
            eventForm.reset();
            const dateFromTd = el.dataset.date;
            console.log(dateFromTd);
            // const inputVal = `${dateFromTd}T00:00`;
            // const date = el.dataset.date.toISOString().substring(0, 16);
            document.getElementById('eventStart').value = `${dateFromTd}T00:00`;
            document.getElementById('eventColor').value = '#9c2ee4';
            modalTitle.textContent = 'Добавить событие';
            deleteEventBtn.style.display = 'none';
            openModal();
        }
    })
});
