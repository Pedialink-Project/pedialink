@extends('layout/portal')

@section('title')
PHM Dashboard
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/pages/phm/dashboard.css') }}">
@endsection

@section('header')
    Good Evening Harry!
@endsection

@section('content')
     <!-- Dashboard Content -->
    <div class="dashboard-content">
        <div class="stats-grid">
            <!--<div class="stat-card">-->
            <div class="stat-content">
                <c-pill>
                        <c-slot name="title">Assinged Children</c-slot>
                        <c-slot name="number">03</c-slot>
                        <c-slot name="icon">
                        <img src="{{ asset('assets/icons/baby-01.svg') }}" alt="" />
                        </c-slot>
                </c-pill>

                <c-pill>
                    <c-slot name="title">Assigned Mothers</c-slot>
                    <c-slot name="number">04</c-slot>
                    <c-slot name="icon">
                        <img src="{{ asset('assets/icons/mother.svg') }}" alt="" />
                    </c-slot>
                </c-pill>

                <c-pill>
                    <c-slot name="title">Vaccinations Due</c-slot>
                    <c-slot name="number">04</c-slot>
                    <c-slot name="icon">
                        <img src="{{ asset('assets/icons/vaccine.svg') }}" alt="" />
                    </c-slot>
                </c-pill>

            </div>
        </div>
    </div>

    </div>
        <!-- Charts and Data Section -->
        <div class="charts-grid">
            <!-- Antenatal Risk Cases Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Antenatal Risk Cases</h3>
                    <p>Antenatal risk rates grouped by age group</p>
                </div>
                <div class="chart-container">
                    <canvas id="riskChart"></canvas>
                </div>
            </div>

            <!-- Monthly Vaccinations Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Monthly Vaccinations Completed</h3>
                    <p>Tracking vaccination completion rates over time</p>
                </div>
                <div class="chart-container">
                    <canvas id="vaccChart"></canvas>
                    <!-- <div class="chart-center-text">
                        <span class="center-number">254</span>
                        <span class="center-label">children</span>
                    </div> -->
                </div>
            </div>
        </div>

        <!-- Appointments and Vaccinations Section -->
        <div class="appointments-grid">
            <!-- Upcoming Appointments -->
            <div class="appointments-card">
                <div class="card-header">
                    <div>
                        <h3>Upcoming Appointments</h3>
                        <p>Upcoming appointments to attend</p>
                    </div>
                    <a class="view-all-btn" href="{{ route('phm.appointments') }}">View All Appointments</a>
                </div>
                
                <div class="appointments-list">
                    <div class="appointment-item">
                        <div class="appointment-info">
                            <span class="appointment-name">Sara Nancy - Routine Checkup</span>
                            <div class="appointment-meta">
                                <i class="fas fa-baby"></i>
                                <span>Baby</span>
                            </div>
                        </div>
                        <div class="appointment-time">
                            <span class="appointment-date">2025-06-06</span>
                            <span class="appointment-hour">09:30 AM</span>
                        </div>
                    </div>
                    
                    <div class="appointment-item">
                        <div class="appointment-info">
                            <span class="appointment-name">Nancy Parker - Routine Checkup</span>
                            <div class="appointment-meta">
                                <i class="fas fa-female"></i>
                                <span>Mother</span>
                            </div>
                        </div>
                        <div class="appointment-time">
                            <span class="appointment-date">2025-06-06</span>
                            <span class="appointment-hour">09:30 AM</span>
                        </div>
                    </div>
                    
                    <div class="appointment-item">
                        <div class="appointment-info">
                            <span class="appointment-name">Lara Peter - Counseling Session</span>
                            <div class="appointment-meta">
                                <i class="fas fa-female"></i>
                                <span>Mother</span>
                            </div>
                        </div>
                        <div class="appointment-time">
                            <span class="appointment-date">2025-06-06</span>
                            <span class="appointment-hour">09:30 AM</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Vaccinations -->
            <div class="vaccinations-card">
                <div class="card-header">
                    <div>
                        <h3>Upcoming Vaccination</h3>
                        <p>Vaccines due for assigned children</p>
                    </div>
                    <a class="view-all-btn" href="{{ route('phm.vaccination') }}">View All Vaccinations</a>
                </div>
                
                <div class="vaccinations-list">
                    <div class="vaccination-item">
                        <div class="vaccination-info">
                            <span class="vaccination-name">Baby Sara</span>
                            <div class="vaccination-meta">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>RHU Center A</span>
                            </div>
                        </div>
                        <div class="vaccination-details">
                            <span class="vaccine-type">BCG</span>
                            <span class="vaccination-date">2025-06-01</span>
                        </div>
                    </div>
                    
                    <div class="vaccination-item">
                        <div class="vaccination-info">
                            <span class="vaccination-name">Baby Mike</span>
                            <div class="vaccination-meta">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>RHU Center A</span>
                            </div>
                        </div>
                        <div class="vaccination-details">
                            <span class="vaccine-type">OPV</span>
                            <span class="vaccination-date">2025-06-01</span>
                        </div>
                    </div>
                    
                    <div class="vaccination-item">
                        <div class="vaccination-info">
                            <span class="vaccination-name">Baby Sara</span>
                            <div class="vaccination-meta">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>RHU Center A</span>
                            </div>
                        </div>
                        <div class="vaccination-details">
                            <span class="vaccine-type">MMR</span>
                            <span class="vaccination-date">2025-06-01</span>
                        </div>
                    </div>
                    
                    <div class="vaccination-item">
                        <div class="vaccination-info">
                            <span class="vaccination-name">Baby Sara</span>
                            <div class="vaccination-meta">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>RHU Center A</span>
                            </div>
                        </div>
                        <div class="vaccination-details">
                            <span class="vaccine-type">MMR</span>
                            <span class="vaccination-date">2025-06-01</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ---------- Stacked Bar (Antenatal Risk Cases) ----------
        const riskCtx = document.getElementById('riskChart').getContext('2d');

        const riskData = {
        labels: ['18 - 25', '25 - 30', '30 - 40', '40 - 50', '50+'],
        datasets: [
            {
            label: 'Normal',
            data: [7, 11, 13, 2, 3],
            backgroundColor: '#10B981', // green
            borderRadius: 6,
            barThickness: 28
            },
            {
            label: 'Moderate',
            data: [5, 11, 16, 3, 4],
            backgroundColor: '#F59E0B', // amber
            borderRadius: 6,
            barThickness: 28
            },
            {
            label: 'High',
            data: [1, 12, 6, 7, 4],
            backgroundColor: '#EF4444', // red
            borderRadius: 6,
            barThickness: 28
            }
        ]
        };

        const riskConfig = {
        type: 'bar',
        data: riskData,
        options: {
            maintainAspectRatio: false,
            plugins: {
            legend: {
                display: true,
                labels: { boxWidth: 12, boxHeight: 12, padding: 12 }
            },
            tooltip: { mode: 'index', intersect: false }
            },
            scales: {
            x: {
                stacked: true,
                grid: { display: false },
                ticks: { color: '#374151', font: { size: 12 } }
            },
            y: {
                stacked: true,
                beginAtZero: true,
                max: 50,
                ticks: {
                stepSize: 10,
                color: '#6b7280',
                font: { size: 12 }
                },
                grid: {
                borderDash: [4, 4],
                color: 'rgba(15, 23, 42, 0.06)'
                }
            }
            }
        }
        };

        new Chart(riskCtx, riskConfig);

        // ---------- Doughnut (Monthly Vaccinations Completed) ----------
        const vaccCtx = document.getElementById('vaccChart').getContext('2d');

        // Values chosen to total 254 (so the center text matches)
        const vaccData = {
        labels: ['Completed', 'Pending', 'Upcoming'],
        datasets: [{
            data: [150, 80, 24], // sums to 254
            backgroundColor: ['#0EA5A4', '#FBC88D', '#F08B77'],
            hoverOffset: 8
        }]
        };

        // small plugin to draw centered text (value + label)
        const centerTextPlugin = {
        id: 'centerText',
        beforeDraw(chart) {
            if (chart.config.type !== 'doughnut') return;
            const { ctx, chartArea } = chart;
            const centerX = (chartArea.left + chartArea.right) / 2;
            const centerY = (chartArea.top + chartArea.bottom) / 2;

            ctx.save();
            // number (bold)
            ctx.font = '700 30px Inter, Arial';
            ctx.fillStyle = '#111827';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText('254', centerX, centerY - 8);

            // label (lighter)
            ctx.font = '400 13px Inter, Arial';
            ctx.fillStyle = '#6b7280';
            ctx.fillText('Children', centerX, centerY + 20);
            ctx.restore();
        }
        };

        const vaccConfig = {
        type: 'doughnut',
        data: vaccData,
        options: {
            maintainAspectRatio: false,
            cutout: '64%',
            plugins: {
            legend: {
                position: 'right',
                labels: { usePointStyle: true, pointStyle: 'circle', padding: 12 }
            },
            tooltip: {
                callbacks: {
                label: ctx => `${ctx.label}: ${ctx.formattedValue}`
                }
            }
            }
        },
            plugins: [centerTextPlugin]
        };

        new Chart(vaccCtx, vaccConfig);
    </script>
@endsection