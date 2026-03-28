@extends('layout/portal')

@section('title')
PHM Growth Monitoring
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/phm/growthmonitoring.css') }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endsection

@section('header')
<div class="top-section">

    <svg width="28" height="28" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M17.5 17.5H8.33333C5.58347 17.5 4.20854 17.5 3.35427 16.6457C2.5 15.7915 2.5 14.4165 2.5 11.6667V2.5"
            stroke="#18181B" stroke-width="1.5" stroke-linecap="round" />
        <path d="M17.5 17.5H8.33333C5.58347 17.5 4.20854 17.5 3.35427 16.6457C2.5 15.7915 2.5 14.4165 2.5 11.6667V2.5"
            stroke="#18181B" stroke-opacity="0.2" stroke-width="1.5" stroke-linecap="round" />
        <path
            d="M14.7541 7.77745L12.3593 11.6535C12.0104 12.2182 11.6141 13.0713 10.8958 12.945C10.051 12.7963 9.64527 11.5371 8.91894 11.1201C8.32746 10.7806 7.89984 11.1898 7.55404 11.6663M17.5001 3.33301L15.9555 5.83301M4.16675 16.6663L6.27201 13.5552"
            stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path
            d="M14.7541 7.77745L12.3593 11.6535C12.0104 12.2182 11.6141 13.0713 10.8958 12.945C10.051 12.7963 9.64527 11.5371 8.91894 11.1201C8.32746 10.7806 7.89984 11.1898 7.55404 11.6663M17.5001 3.33301L15.9555 5.83301M4.16675 16.6663L6.27201 13.5552"
            stroke="#18181B" stroke-opacity="0.2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
    </svg>

    <span>
        Growth Monitoring
        @if (!empty($selectedChildId))
            &#8594; {{ $selectedChildName ?? 'Child' }} &middot; C-000{{ $selectedChildId }}
        @endif
    </span>
</div>
@endsection

@section('content')

<div class="container">

    @if (empty($dateLabels ?? []))
        <c-card class="card">
            <div class="card-body">
                <div class="table-empty">No growth records available. Please add children and their health records.</div>
            </div>
        </c-card>
    @else
        <c-card class="card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">Select Child</span>
                    <span class="card-subtitle">Choose once to update all growth charts</span>
                </div>
                <c-select name="child-filter" class="child-select combined-filter" searchable="1" placeholder="Select Child" >
                    @foreach ($children ?? [] as $child)
                        <li class="select-item" data-value="<?php echo $child['id']; ?>">{{ $child['name'] }}</li>
                    @endforeach
                </c-select>
            </div>
        </c-card>

        <!-- BMI Chart -->
        <c-card class="card bmi-card">
            <div class="card-body">
                <canvas id="bmiChart" height="260"></canvas>
            </div>
        </c-card>

        <!-- Height Chart -->
        <c-card class="card height-card">
            <div class="card-body">
                <canvas id="heightChart" height="260"></canvas>
            </div>
        </c-card>

        <!-- Weight Chart -->
        <c-card class="card weight-card">
            <div class="card-body">
                <canvas id="weightChart" height="260"></canvas>
            </div>
        </c-card>
    @endif

</div>


<script>

    function createGradient(color, chart) {
        const gradient = chart.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, color.replace("1)", "0.1)"));
        gradient.addColorStop(1, color.replace("1)", "0)"));
        return gradient;
    }

    const dateLabels = <?php echo json_encode($dateLabels ?? []); ?>;
    const bmiSource = <?php echo json_encode($bmiChartDatasets ?? []); ?>;
    const heightSource = <?php echo json_encode($heightChartDatasets ?? []); ?>;
    const weightSource = <?php echo json_encode($weightChartDatasets ?? []); ?>;

    console.log('=== CHART DATA ===');
    console.log('Date Labels:', dateLabels);
    console.log('BMI Source:', bmiSource);
    console.log('BMI Source child IDs:', bmiSource.map(d => ({ childId: d.childId, label: d.label })));
    console.log('Height Source:', heightSource);
    console.log('Weight Source:', weightSource);
    console.log('==================');

    const bmiCanvas = document.getElementById("bmiChart");
    const heightCanvas = document.getElementById("heightChart");
    const weightCanvas = document.getElementById("weightChart");

    if (bmiCanvas && heightCanvas && weightCanvas) {
        const bmiCtx = bmiCanvas.getContext("2d");
        const heightCtx = heightCanvas.getContext("2d");
        const weightCtx = weightCanvas.getContext("2d");

        function buildDatasets(source, chartCtx, filterValue, chartColor = null) {
            // If no filter value or empty, return empty array (show nothing)
            if (!filterValue || filterValue === '') {
                return [];
            }
            
            // Filter for specific child
            const filtered = source.filter(item => String(item.childId) === String(filterValue));

            return filtered.map(item => ({
                label: item.label,
                data: item.data || [],
                borderColor: chartColor || item.color,
                backgroundColor: createGradient(chartColor || item.color, chartCtx),
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6,
                spanGaps: false,
            }));
        }

        let bmiChart, heightChart, weightChart;

        // Initialize BMI Chart (empty by default)
        bmiChart = new Chart(bmiCtx, {
            type: "line",
            data: {
                labels: dateLabels,
                datasets: [] // Start empty
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: "bottom",
                        labels: { usePointStyle: true, pointStyle: "rectRounded", boxWidth: 12 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: "rgba(0, 0, 0, 0.05)" },
                        ticks: { stepSize: 5 },
                        title: {
                            display: true,
                            text: 'BMI Value (kg/m2)'
                        }
                    },
                    x: {
                        grid: { color: "rgba(0, 0, 0, 0.05)" },
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                },
            },
        });

        // Initialize Height Chart (empty by default)
        heightChart = new Chart(heightCtx, {
            type: "line",
            data: {
                labels: dateLabels,
                datasets: [] // Start empty
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: "bottom",
                        labels: { usePointStyle: true, pointStyle: "rectRounded", boxWidth: 12 }
                    }
                },
                scales: {
                    y: {
                        grid: { color: "rgba(0, 0, 0, 0.05)" },
                        ticks: { stepSize: 5 },
                        title: {
                            display: true,
                            text: 'Height (cm)'
                        }
                    },
                    x: {
                        grid: { color: "rgba(0, 0, 0, 0.05)" },
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                },
            },
        });

        // Initialize Weight Chart (empty by default)
        weightChart = new Chart(weightCtx, {
            type: "line",
            data: {
                labels: dateLabels,
                datasets: [] // Start empty
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: "bottom",
                        labels: { usePointStyle: true, pointStyle: "rectRounded", boxWidth: 12 }
                    }
                },
                scales: {
                    y: {
                        grid: { color: "rgba(0, 0, 0, 0.05)" },
                        ticks: { stepSize: 5 },
                        title: {
                            display: true,
                            text: 'Weight (kg)'
                        }
                    },
                    x: {
                        grid: { color: "rgba(0, 0, 0, 0.05)" },
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                },
            },
        });

        // Wait for DOM to be ready and set up filters
        setTimeout(() => {
            const combinedFilter = document.querySelector('.combined-filter');

            console.log('Combined filter found:', combinedFilter);

            const updateAllCharts = (filterValue) => {
                console.log('Updating all charts with filterValue:', filterValue);

                if (filterValue === '' || filterValue === null || filterValue === undefined) {
                    bmiChart.data.datasets = [];
                    heightChart.data.datasets = [];
                    weightChart.data.datasets = [];
                    bmiChart.update();
                    heightChart.update();
                    weightChart.update();
                    return;
                }

                const bmiColor = 'rgba(59,130,246,1)';
                const heightColor = 'rgba(34,197,94,1)';
                const weightColor = 'rgba(236,72,153,1)';

                bmiChart.data.datasets = buildDatasets(bmiSource, bmiCtx, filterValue, bmiColor);
                heightChart.data.datasets = buildDatasets(heightSource, heightCtx, filterValue, heightColor);
                weightChart.data.datasets = buildDatasets(weightSource, weightCtx, filterValue, weightColor);

                bmiChart.update();
                heightChart.update();
                weightChart.update();
            };

            // Listen once and update all charts from a single child selection
            if (combinedFilter) {
                combinedFilter.addEventListener('click', (e) => {
                    const selectItem = e.target.closest('.select-item');
                    if (!selectItem) return;
                    const dataValue = selectItem.getAttribute('data-value');
                    console.log('Combined child select clicked:', dataValue);
                    updateAllCharts(dataValue);
                });
            }

        }, 1000);
    }

</script>


@endsection