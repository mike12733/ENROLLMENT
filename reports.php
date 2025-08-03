<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$students = getAllStudents();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Online Enrollment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-graduation-cap"></i> Online Enrollment System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="students.php">Manage Students</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="announcements.php">Announcements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="reports.php">Reports</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4"><i class="fas fa-chart-bar"></i> Enrollment Reports</h2>
                
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title"><?php echo getEnrolledStudentsCount(); ?></h4>
                                        <p class="card-text">Total Enrolled</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title"><?php echo getPendingStudentsCount(); ?></h4>
                                        <p class="card-text">Pending</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-clock fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title"><?php echo getMaleStudentsCount(); ?></h4>
                                        <p class="card-text">Male Students</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-male fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title"><?php echo getFemaleStudentsCount(); ?></h4>
                                        <p class="card-text">Female Students</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-female fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Program Distribution -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Program Distribution</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="programChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Grade Level Distribution</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="gradeChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Report -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Detailed Enrollment Report</h5>
                        <div>
                            <button class="btn btn-primary btn-sm" onclick="exportToCSV()">
                                <i class="fas fa-download"></i> Export CSV
                            </button>
                            <button class="btn btn-secondary btn-sm" onclick="window.print()">
                                <i class="fas fa-print"></i> Print
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="reportsTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Grade Level</th>
                                        <th>Program</th>
                                        <th>Gender</th>
                                        <th>Status</th>
                                        <th>Date Registered</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $student): ?>
                                        <tr>
                                            <td><?php echo $student['student_id']; ?></td>
                                            <td><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></td>
                                            <td><?php echo $student['email']; ?></td>
                                            <td><?php echo $student['grade_level']; ?></td>
                                            <td><?php echo $student['program']; ?></td>
                                            <td><?php echo $student['gender']; ?></td>
                                            <td>
                                                <span class="badge <?php echo $student['status'] == 'enrolled' ? 'bg-success' : 'bg-warning'; ?>">
                                                    <?php echo ucfirst($student['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($student['created_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        // Program Distribution Chart
        const programCtx = document.getElementById('programChart').getContext('2d');
        const programChart = new Chart(programCtx, {
            type: 'doughnut',
            data: {
                labels: ['STEM', 'ABM', 'HUMSS', 'GAS'],
                datasets: [{
                    data: [
                        <?php 
                        $stem_count = 0;
                        $abm_count = 0;
                        $humss_count = 0;
                        $gas_count = 0;
                        foreach ($students as $student) {
                            switch ($student['program']) {
                                case 'STEM': $stem_count++; break;
                                case 'ABM': $abm_count++; break;
                                case 'HUMSS': $humss_count++; break;
                                case 'GAS': $gas_count++; break;
                            }
                        }
                        echo "$stem_count, $abm_count, $humss_count, $gas_count";
                        ?>
                    ],
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Grade Level Distribution Chart
        const gradeCtx = document.getElementById('gradeChart').getContext('2d');
        const gradeChart = new Chart(gradeCtx, {
            type: 'bar',
            data: {
                labels: ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'],
                datasets: [{
                    label: 'Students',
                    data: [
                        <?php 
                        $grade_counts = array_fill(0, 6, 0);
                        foreach ($students as $student) {
                            $grade_num = (int)substr($student['grade_level'], -1);
                            if ($grade_num >= 7 && $grade_num <= 12) {
                                $grade_counts[$grade_num - 7]++;
                            }
                        }
                        echo implode(', ', $grade_counts);
                        ?>
                    ],
                    backgroundColor: '#36A2EB'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        function exportToCSV() {
            const table = document.getElementById('reportsTable');
            const rows = table.querySelectorAll('tbody tr');
            let csv = 'Student ID,Name,Email,Grade Level,Program,Gender,Status,Date Registered\n';
            
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const rowData = [];
                cells.forEach(cell => {
                    let cellText = cell.textContent.trim();
                    if (cellText.includes(',')) {
                        cellText = '"' + cellText + '"';
                    }
                    rowData.push(cellText);
                });
                csv += rowData.join(',') + '\n';
            });
            
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'enrollment_report.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }
    </script>
</body>
</html>