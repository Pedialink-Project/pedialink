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

    function createGradient(color, chart) {
        const gradient = chart.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, color.replace("1)", "0.1)"));
        gradient.addColorStop(1, color.replace("1)", "0)"));
        return gradient;
    }

    const bmiCtx = document.getElementById("bmiChart").getContext("2d");


    const bmiData = [
        { name: "Sara", values: [0, 2, 1, 0, 0, 1, 10, 20, 15, 13, 11, 14], color: "rgba(168,85,247,1)" },
        { name: "John", values: [10, 15, 28, 40, 33, 36, 42, 39, 45, 50, 48, 49], color: "rgba(239,68,68,1)" },
        { name: "Alex", values: [0, 1, 0, 10, 15, 13, 12, 8, 4, 1, 0, 3], color: "rgba(6,182,212,1)" },
    ];

    const bmiDatasets = bmiData.map(item => ({
        label: item.name,
        data: item.values,
        borderColor: item.color,
        backgroundColor: createGradient(item.color, bmiCtx),
        tension: 0.4,
        fill: true,
        pointRadius: 4,
        pointHoverRadius: 6,
    }));

    new Chart(bmiCtx, {
        type: "line",
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: bmiDatasets
        },
        options: {
            responsive: true,
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
                    ticks: { stepSize: 10 },
                },
                x: {
                    grid: { color: "rgba(0, 0, 0, 0.05)" },
                },
            },
        },
    });


    const heightCtx = document.getElementById("heightChart").getContext("2d");

    const heightData = [
        {
            name: "Sara",
            values: [75, 76, 77, 78, 78.5, 79, 79.5, 80, 80.5, 81, 81.5, 82],
            color: "rgba(59,130,246,1)",
        },
        {
            name: "John",
            values: [95, 95.5, 96, 96.5, 97, 97.5, 98, 98.5, 99, 99.5, 100, 100.5],
            color: "rgba(236,72,153,1)",
        },
        {
            name: "Alex",
            values: [120, 120.5, 121, 121.5, 122, 122.5, 123, 123.5, 124, 124.5, 125, 125.5],
            color: "rgba(34,197,94,1)",
        }
    ];


    const heightDatasets = heightData.map(item => ({
        label: item.name,
        data: item.values,
        borderColor: item.color,
        backgroundColor: createGradient(item.color, heightCtx),
        tension: 0.4,
        fill: true,
        pointRadius: 4,
        pointHoverRadius: 6,
    }));

    new Chart(heightCtx, {
        type: "line",
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: heightDatasets
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: "bottom",
                    labels: { usePointStyle: true, pointStyle: "rectRounded", boxWidth: 12 }
                }
            },
            scales: {
                y: {
                    min: 70,
                    grid: { color: "rgba(0, 0, 0, 0.05)" },
                    ticks: { stepSize: 5 },
                },
                x: {
                    grid: { color: "rgba(0, 0, 0, 0.05)" },
                },
            },
        },
    });

    const weightCtx = document.getElementById("weightChart").getContext("2d");


    const weightData = [
        {
            name: "Child 1 (Age 1)",
            values: [10, 10.2, 10.4, 10.6, 10.8, 11, 11.2, 11.4, 11.6, 11.8, 12, 12.2],
            color: "rgba(59,130,246,1)",
        },
        {
            name: "Child 2 (Age 3)",
            values: [14, 14.2, 14.5, 14.8, 15, 15.3, 15.5, 15.8, 16, 16.2, 16.5, 16.7],
            color: "rgba(236,72,153,1)",
        },
        {
            name: "Child 3 (Age 7)",
            values: [22, 22.3, 22.6, 23, 23.3, 23.6, 24, 24.3, 24.6, 25, 25.3, 25.6],
            color: "rgba(34,197,94,1)",
        }
    ];

    const weightDatasets = weightData.map(item => ({
        label: item.name,
        data: item.values,
        borderColor: item.color,
        backgroundColor: createGradient(item.color, weightCtx),
        tension: 0.4,
        fill: true,
        pointRadius: 4,
        pointHoverRadius: 6,
    }));

    new Chart(weightCtx, {
        type: "line",
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: weightDatasets
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: "bottom",
                    labels: { usePointStyle: true, pointStyle: "rectRounded", boxWidth: 12 }
                }
            },
            scales: {
                y: {
                    min: 8,
                    grid: { color: "rgba(0, 0, 0, 0.05)" },
                    ticks: { stepSize: 10 },
                },
                x: {
                    grid: { color: "rgba(0, 0, 0, 0.05)" },
                },
            },
        },
    });


</script>


@endsection