// JavaScript for Dynamic Calendar
const calendarBody = document.getElementById("calendar-body");
const monthYear = document.getElementById("month-year");
const prevMonthBtn = document.getElementById("prev-month");
const nextMonthBtn = document.getElementById("next-month");

let currentDate = new Date();

function renderCalendar(date) {
  // Clear the previous calendar
  calendarBody.innerHTML = "";

  const year = date.getFullYear();
  const month = date.getMonth();

  // Update the month and year display
  const monthNames = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
  ];
  monthYear.textContent = `${monthNames[month]} ${year}`;

  // First day of the month
  const firstDay = new Date(year, month, 1).getDay();

  // Number of days in the month
  const daysInMonth = new Date(year, month + 1, 0).getDate();

  // Create the calendar rows
  let dateCounter = 1;
  for (let i = 0; i < 6; i++) { // Max 6 rows
    const row = document.createElement("tr");

    for (let j = 0; j < 7; j++) { // 7 columns for days
      const cell = document.createElement("td");

      if (i === 0 && j < firstDay) {
        // Empty cells before the first day of the month
        cell.textContent = "";
      } else if (dateCounter > daysInMonth) {
        // Empty cells after the last day of the month
        cell.textContent = "";
      } else {
        cell.textContent = dateCounter;
        dateCounter++;
      }

      row.appendChild(cell);
    }

    calendarBody.appendChild(row);
  }
}

// Event Listeners for Month Navigation
prevMonthBtn.addEventListener("click", () => {
  currentDate.setMonth(currentDate.getMonth() - 1);
  renderCalendar(currentDate);
});

nextMonthBtn.addEventListener("click", () => {
  currentDate.setMonth(currentDate.getMonth() + 1);
  renderCalendar(currentDate);
});

// Initial Render
renderCalendar(currentDate);
