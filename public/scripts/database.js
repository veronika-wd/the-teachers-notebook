// ============================================
// ГЛОБАЛЬНЫЕ ПЕРЕМЕННЫЕ
// ============================================
let currentTable = null;
let currentData = [];
let searchHandler = null;

// ============================================
// ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ
// ============================================

function safeIncludes(value, searchTerm) {
    if (value === null || value === undefined) return false;

    let strValue;
    if (typeof value === 'object') {
        strValue = Object.values(value)
            .filter(v => typeof v === 'string' || typeof v === 'number')
            .join(' ')
            .toLowerCase();
    } else {
        strValue = String(value).toLowerCase();
    }
    return strValue.includes(searchTerm);
}

function cleanupPrevious() {
    const searchInput = document.getElementById('searchInput');

    if (searchHandler) {
        searchInput.removeEventListener('input', searchHandler);
        searchHandler = null;
    }

    if (currentTable && typeof currentTable.destroy === 'function') {
        currentTable.destroy();
        currentTable = null;
    }

    currentData = [];
}

/**
 * Обновляет счетчик результатов с небольшой задержкой
 */
function countData() {
    // Используем setTimeout чтобы дать время на отрисовку
    setTimeout(() => {
        const rows = document.querySelectorAll('#database-table .tabulator-row');
        const count = rows.length;
        const counterElement = document.getElementById('countData');
        if (counterElement) {
            counterElement.textContent = `Всего результатов: ${count}`;
        }
    }, 50);
}

// ============================================
// ОСНОВНАЯ ФУНКЦИЯ ЗАГРУЗКИ ДАННЫХ
// ============================================
async function loadData(url = '/api/students') {
    const searchInput = document.getElementById('searchInput');

    cleanupPrevious();

    const orderByClassBtn = document.getElementById('orderByClass');
    if (orderByClassBtn) {
        orderByClassBtn.style.display = (url === '/api/students') ? 'inline-block' : 'none';
    }

    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        currentData = data;

        // ============================================
        // СТУДЕНТЫ / УЧЕНИКИ
        // ============================================
        if (url === '/api/students') {
            currentTable = new Tabulator("#database-table", {
                data: data,
                layout: "fitColumns",
                columns: [
                    {title: "Фамилия", field: "surname"},
                    {title: "Имя", field: "name"},
                    {title: "Отчество", field: "patronymic"},
                    {title: "Дата рождения", field: "birthDate"},
                    {title: "Класс", field: "class"},
                    {title: "Серия и номер паспорта", field: "passportData"},
                    {title: "Адрес проживания", field: "address"},
                    {title: "Статус", field: "status"}
                ],
            });

            // Подписываемся на события таблицы
            currentTable.on("tableBuilt", function() {
                countData();
            });

            currentTable.on("dataLoaded", function(data) {
                countData();
            });

            const sortByClassBtn = document.getElementById('orderByClass');
            if (sortByClassBtn) {
                sortByClassBtn.onclick = () => {
                    currentTable.setSort([{column: "class", dir: "asc"}]);
                };
            }

            const sortBySurnameBtn = document.getElementById('orderBySurname');
            if (sortBySurnameBtn) {
                sortBySurnameBtn.onclick = () => {
                    currentTable.setSort([{column: "surname", dir: "asc"}]);
                };
            }

            searchHandler = function performSearchStudents() {
                const searchTerm = searchInput.value.trim().toLowerCase();

                if (searchTerm === '') {
                    currentTable.setData(currentData);
                    return;
                }

                const results = currentData.filter(item => {
                    return (
                        safeIncludes(item.name, searchTerm) ||
                        safeIncludes(item.surname, searchTerm) ||
                        safeIncludes(item.patronymic, searchTerm) ||
                        safeIncludes(item.birthDate, searchTerm) ||
                        safeIncludes(item.class, searchTerm) ||
                        safeIncludes(item.passportData, searchTerm) ||
                        safeIncludes(item.parent, searchTerm) ||
                        safeIncludes(item.parentPhone, searchTerm) ||
                        safeIncludes(item.address, searchTerm) ||
                        safeIncludes(item.status, searchTerm)
                    );
                });

                currentTable.setData(results);
            };

        }
            // ============================================
            // УЧИТЕЛЯ
        // ============================================
        else if (url === '/api/teachers') {
            currentTable = new Tabulator("#database-table", {
                data: data,
                layout: "fitColumns",
                columns: [
                    {title: "ФИО", field: "fullname"},
                    {title: "Телефон", field: "phone"},
                    {title: "Дата рождения", field: "birthDate"},
                    {title: "Серия и номер паспорта", field: "passportData"},
                    {title: "СНИЛС", field: "snils"},
                    {title: "ИНН", field: "inn"},
                    {title: "Адрес проживания", field: "address"},
                    {title: "Должность", field: "post"},
                    {title: "Образование", field: "education"},
                    {title: "Опыт работы", field: "experience"},
                ],
            });

            currentTable.on("tableBuilt", function() {
                countData();
            });

            currentTable.on("dataLoaded", function(data) {
                countData();
            });

            const sortBySurnameBtn = document.getElementById('orderBySurname');
            if (sortBySurnameBtn) {
                sortBySurnameBtn.onclick = () => {
                    currentTable.setSort([{column: "fullname", dir: "asc"}]);
                };
            }

            searchHandler = function performSearchTeachers() {
                const searchTerm = searchInput.value.trim().toLowerCase();

                if (searchTerm === '') {
                    currentTable.setData(currentData);
                    return;
                }

                const results = currentData.filter(item => {
                    return (
                        safeIncludes(item.fullname, searchTerm) ||
                        safeIncludes(item.phone, searchTerm) ||
                        safeIncludes(item.birthDate, searchTerm) ||
                        safeIncludes(item.pasportData, searchTerm) ||
                        safeIncludes(item.snils, searchTerm) ||
                        safeIncludes(item.inn, searchTerm) ||
                        safeIncludes(item.address, searchTerm) ||
                        safeIncludes(item.post, searchTerm) ||
                        safeIncludes(item.education, searchTerm) ||
                        safeIncludes(item.experience, searchTerm) ||
                        safeIncludes(item.qualification, searchTerm)
                    );
                });

                currentTable.setData(results);
            };

        }
            // ============================================
            // РОДИТЕЛИ / ОПЕКУНЫ
        // ============================================
        else {
            currentTable = new Tabulator("#database-table", {
                data: data,
                layout: "fitColumns",
                columns: [
                    {title: "ФИО", field: "fullName"},
                    {title: "Телефон", field: "phone"},
                    {title: "Место работы", field: "job"},
                    {title: "Адрес проживания", field: "address"},
                    {title: "Статус", field: "status"},
                    {title: "Серия и номер паспорта", field: "passportData"},
                    {title: "СНИЛС", field: "snils"},
                    {title: "ИНН", field: "inn"},
                ],
            });

            currentTable.on("tableBuilt", function() {
                countData();
            });

            currentTable.on("dataLoaded", function(data) {
                countData();
            });

            const sortBySurnameBtn = document.getElementById('orderBySurname');
            if (sortBySurnameBtn) {
                sortBySurnameBtn.onclick = () => {
                    currentTable.setSort([{column: "fullName", dir: "asc"}]);
                };
            }

            searchHandler = function performSearchGuardians() {
                const searchTerm = searchInput.value.trim().toLowerCase();

                if (searchTerm === '') {
                    currentTable.setData(currentData);
                    return;
                }

                const results = currentData.filter(item => {
                    return (
                        safeIncludes(item.fullName, searchTerm) ||
                        safeIncludes(item.job, searchTerm) ||
                        safeIncludes(item.status, searchTerm) ||
                        safeIncludes(item.phone, searchTerm) ||
                        safeIncludes(item.address, searchTerm)
                    );
                });

                currentTable.setData(results);
            };
        }

        searchInput.addEventListener('input', searchHandler);

        console.log('✅ Таблица загружена:', url);

    } catch (error) {
        console.error('❌ Ошибка загрузки данных:', error);
        alert('Не удалось загрузить данные. Проверьте консоль для деталей.');
    }
}

// ============================================
// ОБРАБОТЧИКИ КНОПОК НАВИГАЦИИ
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    const pupilsBtn = document.getElementById('pupils');
    const teachersBtn = document.getElementById('teachers');
    const guardiansBtn = document.getElementById('guardians');

    if (pupilsBtn) {
        pupilsBtn.addEventListener('click', () => loadData('/api/students'));
    }
    if (teachersBtn) {
        teachersBtn.addEventListener('click', () => loadData('/api/teachers'));
    }
    if (guardiansBtn) {
        guardiansBtn.addEventListener('click', () => loadData('/api/guardians'));
    }

    loadData('/api/students');
});
