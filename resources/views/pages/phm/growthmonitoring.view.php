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
        <!-- BMI Chart -->
        <c-card class="card bmi-card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">Child BMI Tracking</span>
                    <span class="card-subtitle">Track BMI growth over time</span>
                </div>
                <c-select name="bmi-child-filter" class="child-select bmi-filter" searchable="1" placeholder="Select Child" >
                    @foreach ($children ?? [] as $child)
                        <li class="select-item" data-value="<?php echo $child['id']; ?>">{{ $child['name'] }}</li>
                    @endforeach
                </c-select>
            </div>
            <hr class="divider">
            <div class="card-body">
                <canvas id="bmiChart" height="260"></canvas>
            </div>
        </c-card>

        <!-- Height Chart -->
        <c-card class="card height-card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">Child Height Tracking</span>
                    <span class="card-subtitle">Track height growth over time</span>
                </div>
                <c-select name="height-child-filter" class="child-select height-filter" searchable="1" placeholder="Select Child" >
                    @foreach ($children ?? [] as $child)
                        <li class="select-item" data-value="<?php echo $child['id']; ?>">{{ $child['name'] }}</li>
                    @endforeach
                </c-select>
            </div>
            <hr class="divider">
            <div class="card-body">
                <canvas id="heightChart" height="260"></canvas>
            </div>
        </c-card>

        <!-- Weight Chart -->
        <c-card class="card weight-card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">Child Weight Tracking</span>
                    <span class="card-subtitle">Track weight growth over time</span>
                </div>
                <c-select name="weight-child-filter" class="child-select weight-filter" searchable="1" placeholder="Select Child" >
                    @foreach ($children ?? [] as $child)
                        <li class="select-item" data-value="<?php echo $child['id']; ?>">{{ $child['name'] }}</li>
                    @endforeach
                </c-select>
            </div>
            <hr class="divider">
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

        function buildDatasets(source, chartCtx, filterValue) {
            // If no filter value or empty, return empty array (show nothing)
            if (!filterValue || filterValue === '') {
                return [];
            }
            
            // Filter for specific child
            const filtered = source.filter(item => String(item.childId) === String(filterValue));

            return filtered.map(item => ({
                label: item.label,
                data: item.data || [],
                borderColor: item.color,
                backgroundColor: createGradient(item.color, chartCtx),
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
                    },
                    x: {
                        grid: { color: "rgba(0, 0, 0, 0.05)" },
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
                    },
                    x: {
                        grid: { color: "rgba(0, 0, 0, 0.05)" },
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
                    },
                    x: {
                        grid: { color: "rgba(0, 0, 0, 0.05)" },
                    },
                },
            },
        });

        // Wait for DOM to be ready and set up filters
        setTimeout(() => {
            const bmiFilter = document.querySelector('.bmi-filter');
            const heightFilter = document.querySelector('.height-filter');
            const weightFilter = document.querySelector('.weight-filter');

            console.log('Filters found:', { bmiFilter, heightFilter, weightFilter });

            // Function to update charts based on filter value
            const updateBmiChart = (filterValue) => {
                console.log('Updating BMI Chart with filterValue:', filterValue, 'Type:', typeof filterValue);
                if (filterValue !== '' && filterValue !== null && filterValue !== undefined) {
                    const newDatasets = buildDatasets(bmiSource, bmiCtx, filterValue);
                    console.log('New BMI Datasets count:', newDatasets.length, 'Datasets:', newDatasets);
                    bmiChart.data.datasets = newDatasets;
                    bmiChart.update();
                } else {
                    console.log('Skipping BMI update - no filter value');
                }
            };

            const updateHeightChart = (filterValue) => {
                console.log('Updating Height Chart with filterValue:', filterValue);
                if (filterValue !== '' && filterValue !== null && filterValue !== undefined) {
                    const newDatasets = buildDatasets(heightSource, heightCtx, filterValue);
                    console.log('New Height Datasets count:', newDatasets.length);
                    heightChart.data.datasets = newDatasets;
                    heightChart.update();
                }
            };

            const updateWeightChart = (filterValue) => {
                console.log('Updating Weight Chart with filterValue:', filterValue);
                if (filterValue !== '' && filterValue !== null && filterValue !== undefined) {
                    const newDatasets = buildDatasets(weightSource, weightCtx, filterValue);
                    console.log('New Weight Datasets count:', newDatasets.length);
                    weightChart.data.datasets = newDatasets;
                    weightChart.update();
                }
            };

            // Listen for clicks on select items with proper event handling
            if (bmiFilter) {
                bmiFilter.addEventListener('click', (e) => {
                    const selectItem = e.target.closest('.select-item');
                    if (!selectItem) return;
                    const dataValue = selectItem.getAttribute('data-value');
                    console.log('BMI Select item clicked:', dataValue);
                    updateBmiChart(dataValue);
                });
            }

            if (heightFilter) {
                heightFilter.addEventListener('click', (e) => {
                    const selectItem = e.target.closest('.select-item');
                    if (!selectItem) return;
                    const dataValue = selectItem.getAttribute('data-value');
                    console.log('Height Select item clicked:', dataValue);
                    updateHeightChart(dataValue);
                });
            }

            if (weightFilter) {
                weightFilter.addEventListener('click', (e) => {
                    const selectItem = e.target.closest('.select-item');
                    if (!selectItem) return;
                    const dataValue = selectItem.getAttribute('data-value');
                    console.log('Weight Select item clicked:', dataValue);
                    updateWeightChart(dataValue);
                });
            }

        }, 1000);
    }

</script>


@endsection