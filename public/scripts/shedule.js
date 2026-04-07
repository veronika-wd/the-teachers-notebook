const scheduleData = [
  { day: "Понедельник", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Вторник", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Среда", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Четверг", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Пятница", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },

  { day: "Понедельник", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Вторник", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Среда", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Четверг", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Пятница", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
    
  { day: "Понедельник", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Вторник", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Среда", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Четверг", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Пятница", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },

  { day: "Понедельник", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Вторник", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Среда", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Четверг", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Пятница", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
    
  { day: "Понедельник", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Вторник", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Среда", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Четверг", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Пятница", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },

  { day: "Понедельник", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Вторник", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Среда", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Четверг", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },
  { day: "Пятница", lesson5: "Математика", lesson6: "Физика", lesson7: "Математика", lesson8: "Физика", lesson9: "Математика", lesson10: "Физика", lesson11: "Биология", teacher: "Иванов" },

];

const table = new Tabulator("#shedule-table", {
  data: scheduleData,  // данные
  layout: "fitColumns", // автоподбор ширины
  groupBy: "day", 
  columns: [
    { title: "5 класс", field: "lesson5" },
    { title: "6 класс", field: "lesson6" },
    { title: "7 класс", field: "lesson7" },
    { title: "8 класс", field: "lesson8" },
    { title: "9 класс", field: "lesson9" },
    { title: "10 класс", field: "lesson10" },
    { title: "11 класс", field: "lesson11" },
  ],
});