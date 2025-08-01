function initLoginPage() {
    const passwordInput = document.getElementById('password');
    const checkboxToggle = document.getElementById('showPasswordToggle');

    if (passwordInput && checkboxToggle) {
        checkboxToggle.addEventListener('change', function () {
            passwordInput.type = this.checked ? 'text' : 'password';
        });
    }
}
function renderCharts() {
    const stats = window.dashboardStats;
    if (!stats) return;

    // Pie Chart: Students vs Lecturers
    const pieCtx = document.getElementById("userPieChart").getContext("2d");
    new Chart(pieCtx, {
        type: "pie",
        data: {
            labels: ["Students", "Lecturers"],
            datasets: [{
                data: [stats.totalStudents, stats.totalLecturers],
                backgroundColor: ["#36A2EB", "#FF6384"],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: "bottom"
                }
            }
        }
    });

    // Bar Chart: All Stats
    const barCtx = document.getElementById("userBarChart").getContext("2d");
    new Chart(barCtx, {
        type: "bar",
        data: {
            labels: ["Users", "Students", "Lecturers", "Courses", "Assignments"],
            datasets: [{
                label: "Counts",
                data: [
                    stats.totalUsers,
                    stats.totalStudents,
                    stats.totalLecturers,
                    stats.totalCourses,
                    stats.totalAssignments
                ],
                backgroundColor: [
                    "#36A2EB",
                    "#4BC0C0",
                    "#FF6384",
                    "#FFCE56",
                    "#9966FF"
                ],
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initLoginPage();

    // Calendar Logic
    const data = window.dashboardData || {};
    const monthYearLabel = document.getElementById('calendar-month-year');
    const prevBtn = document.getElementById('calendar-prev');
    const nextBtn = document.getElementById('calendar-next');
    const calendarBody = document.getElementById('calendar-body');

    // Fallback for current date if not provided from server
    const today = data.today || (new Date()).getDate();
    let displayMonth = (typeof data.currentMonth === 'number') ? data.currentMonth : (new Date()).getMonth();
    let displayYear = (typeof data.currentYear === 'number') ? data.currentYear : (new Date()).getFullYear();

    const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    function renderCalendar(month, year) {
        monthYearLabel.textContent = `${monthNames[month]} ${year}`;
        calendarBody.innerHTML = '';

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        let day = 1;
        for (let week = 0; week < 6; week++) {
            const row = document.createElement('tr');
            for (let weekday = 0; weekday < 7; weekday++) {
                const cell = document.createElement('td');
                if (week === 0 && weekday < firstDay) {
                    cell.className = 'empty';
                    cell.innerHTML = '&nbsp;';
                } else if (day > daysInMonth) {
                    cell.className = 'empty';
                    cell.innerHTML = '&nbsp;';
                } else {
                    cell.textContent = day;
                    if (
                        day === today &&
                        month === displayMonth &&
                        year === displayYear
                    ) {
                        cell.classList.add('calendar-today');
                    }
                    day++;
                }
                row.appendChild(cell);
            }
            calendarBody.appendChild(row);
            if (day > daysInMonth) break;
        }
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            displayMonth--;
            if (displayMonth < 0) {
                displayMonth = 11;
                displayYear--;
            }
            renderCalendar(displayMonth, displayYear);
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            displayMonth++;
            if (displayMonth > 11) {
                displayMonth = 0;
                displayYear++;
            }
            renderCalendar(displayMonth, displayYear);
        });
    }


    // Initialize calendar
    renderCalendar(displayMonth, displayYear);

    // Initialize charts
    renderCharts();
});
