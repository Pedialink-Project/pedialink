@extends('layout/portal')

@section('title')
Parent - Growth Tracking
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/parent/nutrition-tracking.css') }}">
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

    <span>Growth Tracking</span>
</div>
@endsection

@section('content')

<main class="container">

    <!-- BMI Chart -->
    <c-card class="card bmi-card">
        <div class="header">
            <div class="title-section">
                <span class="card-title">Child BMI Tracking</span>
                <span class="card-subtitle">Track Baby Sarah's BMI over time</span>
            </div>
            <!-- Child Selector -->
            <c-select name='child' class="child-select" placeholder="Select Child">
                <li class="select-item" data-value="all-children">All Children</li>
                <li class="select-item " data-value="baby-sara">Baby Sara</li>
                <li class="select-item" data-value="baby-john">Baby John</li>
            </c-select>
        </div>
        <hr class="divider">
        <div class="card-body">
            <canvas id="bmiChart">

            </canvas>
        </div>
    </c-card>

    <!-- Height Chart -->
    <c-card class="card height-card">
        <div class="header">
            <div class="title-section">
                <span class="card-title">Child Height Tracking</span>
                <span class="card-subtitle">Track Baby Sarah's Height over time</span>
            </div>
            <!-- Child Selector -->
            <c-select name='child' class="child-select" placeholder="Select Child">
                <li class="select-item" data-value="all-children">All Children</li>
                <li class="select-item " data-value="baby-sara">Baby Sara</li>
                <li class="select-item" data-value="baby-john">Baby John</li>
            </c-select>
        </div>
        <hr class="divider">
        <div class="card-body">
            <canvas id="heightChart">

            </canvas>
        </div>
    </c-card>

    <!-- Weight Chart -->
    <c-card class="card weight-card">
        <div class="header">
            <div class="title-section">
                <span class="card-title">Child Weight Tracking</span>
                <span class="card-subtitle">Track Baby Sarah's Weight over time</span>
            </div>
            <!-- Child Selector -->
            <c-select name='child' class="child-select" placeholder="Select Child">
                <li class="select-item" data-value="all-children">All Children</li>
                <li class="select-item " data-value="baby-sara">Baby Sara</li>
                <li class="select-item" data-value="baby-john">Baby John</li>
            </c-select>
        </div>
        <hr class="divider">
        <div class="card-body">
            <canvas id="weightChart">

            </canvas>
        </div>
    </c-card>

</main>


<script>
    const growthData = <?php echo json_encode($growthData); ?>;

    function createGradient(color, chart) {
        const gradient = chart.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, color.replace("1)", "0.1)"));
        gradient.addColorStop(1, color.replace("1)", "0)"));
        return gradient;
    }

    const bmiCtx = document.getElementById("bmiChart").getContext("2d");



    const bmiDatasets = growthData.map(child => ({
        label: child.name,
        data: child.bmi,
        borderColor: "rgba(168,85,247,1)",
        backgroundColor: createGradient("rgba(168,85,247,1)", bmiCtx),
        tension: 0.4,
        fill: true,
        pointRadius: 4,
        pointHoverRadius: 6,
    }));

    new Chart(bmiCtx, {
        type: "line",
        data: {
            labels: growthData[0]?.labels ?? [],
            datasets: bmiDatasets
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: "bottom",
                    labels: {
                        usePointStyle: true,
                        pointStyle: "rectRounded",
                        boxWidth: 12
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: "rgba(0, 0, 0, 0.05)"
                    },
                    ticks: {
                        stepSize: 10
                    },
                },
                x: {
                    grid: {
                        color: "rgba(0, 0, 0, 0.05)"
                    },
                },
            },
        },
    });


    const heightCtx = document.getElementById("heightChart").getContext("2d");




    const heightDatasets = growthData.map(child => ({
        label: child.name,
        data: child.height,
        borderColor: "rgba(59,130,246,1)",
        backgroundColor: createGradient("rgba(59,130,246,1)", heightCtx),
        tension: 0.4,
        fill: true,
        pointRadius: 4,
        pointHoverRadius: 6,
    }));

    new Chart(heightCtx, {
        type: "line",
        data: {
            labels: growthData[0]?.labels ?? [],
            datasets: heightDatasets
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: "bottom",
                    labels: {
                        usePointStyle: true,
                        pointStyle: "rectRounded",
                        boxWidth: 12
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: "rgba(0, 0, 0, 0.05)"
                    },
                    ticks: {
                        stepSize: 5
                    },
                },
                x: {
                    grid: {
                        color: "rgba(0, 0, 0, 0.05)"
                    },
                },
            },
        },
    });

    const weightCtx = document.getElementById("weightChart").getContext("2d");



    const weightDatasets = growthData.map(child => ({
        label: child.name,
        data: child.weight,
        borderColor: "rgba(34,197,94,1)",
        backgroundColor: createGradient("rgba(34,197,94,1)", weightCtx),
        tension: 0.4,
        fill: true,
        pointRadius: 4,
        pointHoverRadius: 6,
    }));

    new Chart(weightCtx, {
        type: "line",
        data: {
            labels: growthData[0]?.labels ?? [],
            datasets: weightDatasets
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: "bottom",
                    labels: {
                        usePointStyle: true,
                        pointStyle: "rectRounded",
                        boxWidth: 12
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: "rgba(0, 0, 0, 0.05)"
                    },
                    ticks: {
                        stepSize: 10
                    },
                },
                x: {
                    grid: {
                        color: "rgba(0, 0, 0, 0.05)"
                    },
                },
            },
        },
    });
</script>


@endsection