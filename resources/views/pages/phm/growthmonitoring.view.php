@extends('layout/portal')

@section('title')
    PHM  Growth Monitoring
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/pages/phm/dashboard.css') }}">
@endsection

@section('header')
   Growth Monitoring - Overview
@endsection

@section('content')
        <div class="charts-grid-row">   
            <!-- Antenatal Risk Cases Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Child Weight Tracking</h3>
                    <p>Track Child's Weight Over Time</p>
                    
                </div>
                <div class="chart-container">
                    <canvas id="weightChart"></canvas>
                </div>
            </div>     

<!-- Monthly Vaccinations Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Child Height Tracking</h3>
                    <p>Track Child's Height Over Time</p>
                </div>
                <div class="chart-container">
                    <canvas id="weightChart2"></canvas>
                </div>
            </div>
            
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Child BMI Tracking</h3>
                    <p>Track Child's Height Over Time</p>
                </div>
                <div class="chart-container">
                    <canvas id="weightChart3"></canvas>
                </div>
            </div>  
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

            // sample weights (matches the visual behaviour of the image)
            const weights = [1.6, 1.9, 2.4, 2.1, 1.0, 3.0, 5.6, 6.6, 10.0, 9.6, 8.2, 8.1];

            const ctxList = [
                document.getElementById('weightChart').getContext('2d'),
                document.getElementById('weightChart2').getContext('2d'),
                document.getElementById('weightChart3').getContext('2d'),

            ];

            // Use a scriptable backgroundColor that creates a vertical gradient based on chart area
            const gradientBg = (context) => {
            const chart = context.chart;
            const {ctx, chartArea} = chart;
            if (!chartArea) {
                return 'rgba(99,102,241,0.12)';
            } // fallback before layout
            const g = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                g.addColorStop(0, 'rgba(99,102,241,0.18)'); // denser near the line
                g.addColorStop(0.45, 'rgba(99,102,241,0.10)');
                g.addColorStop(1, 'rgba(99,102,241,0.02)');
                return g;
            };

            for (const ctx of ctxList) {
                const chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Sara John · C-1234',
                            data: weights,
                            borderColor: '#6366F1',         // line color
                            borderWidth: 2.5,
                            tension: 0.35,                 // smooth curve
                            fill: true,
                            backgroundColor: gradientBg,   // scriptable gradient
                            pointStyle: 'circle',
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: '#fff',  // circle interior white
                            pointBorderColor: '#6366F1',   // circle border same color as line
                            pointBorderWidth: 2,
                            showLine: true
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                backgroundColor: 'rgba(17,24,39,0.95)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                padding: 10,
                                cornerRadius: 8
                            }
                        },
                        interaction: {
                        mode: 'nearest',
                        intersect: true
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: true,
                                    drawBorder: false,
                                    color: 'rgba(15,23,42,0.06)',
                                    borderDash: [4, 6]
                                    },
                                    ticks: {
                                    color: '#6b7280',
                                    padding: 10,
                                    maxRotation: 0,
                                    font: { size: 12 }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                suggestedMax: 10,
                                ticks: {
                                    stepSize: 2,
                                    color: '#6b7280',
                                    padding: 6,
                                    font: { size: 12 }
                                },
                                grid: {
                                    display: true,
                                    drawBorder: false,
                                    color: 'rgba(15,23,42,0.06)',
                                    borderDash: [4, 6]
                                }
                            }
                        }
                    }
                });
            }
            

            // Optional: update legend label if user selects another child
            // document.getElementById('childSelect').addEventListener('change', (e) => {
            // const name = e.target.value;
            // const label = `${name} · C-1234`;
            // chart.data.datasets[0].label = label;
            // document.getElementById('legendLabel').textContent = label;
            // chart.update();
            // });
        </script>      
@endsection            