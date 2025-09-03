 <section class="notice">
                <h2>Admin Dashboard</h2>
</section>

<div class="dashboard-container">
    <div class="content-left">
        <section class="stats">
            <h2>System Statistics</h2>

            <div class="charts-container">
                <div class="chart-section">
                    <h3>Students vs Lecturers (Pie Chart)</h3>
                    <canvas id="userPieChart" width="280" height="280"></canvas>
                </div>

                <div class="chart-section">
                    <h3>All Stats Overview (Bar Chart)</h3>
                    <canvas id="userBarChart" width="280" height="280"></canvas>
                </div>
            </div>

            <table class="stats-table">
                <thead>
                    <tr>
                        <th>Statistic</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Total Users</td><td><?= $total_users ?></td></tr>
                    <tr><td>Student Users</td><td><?= $total_students ?></td></tr>
                    <tr><td>Lecturer Users</td><td><?= $total_lecturers ?></td></tr>
                    <tr><td>Courses</td><td><?= $total_courses ?></td></tr>
                    <tr><td>Assignments</td><td><?= $total_assignments ?></td></tr>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="sidebar-right">
        <section class="calendar">
            <div class="calendar-widget">
                <div class="calendar-header">
                    <span id="calendar-prev" class="calendar-arrow">&#9664;</span>
                    <span id="calendar-month-year" class="calendar-title"></span>
                    <span id="calendar-next" class="calendar-arrow">&#9654;</span>
                </div>
                <table class="calendar-table">
                    <thead>
                        <tr>
                            <th>S</th><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th>
                        </tr>
                    </thead>
                    <tbody id="calendar-body">
                        <!-- JS renders calendar days here -->
                    </tbody>
                </table>
            </div>
        </section>
    </div>

</div>