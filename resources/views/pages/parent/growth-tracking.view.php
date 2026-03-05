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
@if(empty($growthData))
<c-emptytable
    alt="No Growth Data"
    title="No Growth Records Yet"
    description="No growth tracking data available. Start recording your child's height, weight, and BMI measurements to view their growth progress here."
/>
@else
<main class="container">




    <!-- BMI Chart -->

        <div class="left-col">

    <c-card class="card bmi-card">
        <div class="header">
            <div class="title-section">
                <span class="card-title">Child BMI Tracking</span>
                <span class="card-subtitle">Track Baby Sarah's BMI over time</span>
            </div>


            <c-select class="child-select-bmi" placeholder="Select Child">

                @if(!empty($childrenList))
                @foreach ($childrenList as $child)
                <li class="select-item" data-value="{{ $child['id'] }}">
                    {{ $child['name'] }}
                </li>
                @endforeach
                <li class="select-item" data-value="all-children">
                    All Children
                </li>
                @else
                <li class="select-item disabled">
                    No children available
                </li>
                @endif
            </c-select>
        </div>
        <hr class="divider">
        <div class="card-body">
            <canvas id="bmiChart">

            </canvas>
            <div class="no-data-message bmi-no-data" style="display:none;">
                No BMI records available for this child
            </div>

        </div>
    </c-card>

    <!-- Height Chart -->
    <c-card class="card height-card">
        <div class="header">
            <div class="title-section">
                <span class="card-title">Child Height Tracking</span>
                <span class="card-subtitle">Track Baby Sarah's Height over time</span>
            </div>
            <c-select class="child-select-height" placeholder="Select Child">

                @if(!empty($childrenList))
                @foreach ($childrenList as $child)
                <li class="select-item" data-value="{{ $child['id'] }}">
                    {{ $child['name'] }}
                </li>
                @endforeach
                <li class="select-item" data-value="all-children">
                    All Children
                </li>
                @else
                <li class="select-item disabled">
                    No children available
                </li>
                @endif
            </c-select>
        </div>
        <hr class="divider">
        <div class="card-body">
            <canvas id="heightChart">


            </canvas>
            <div class="no-data-message height-no-data" style="display:none;">
                No height records available for this child
            </div>

        </div>
    </c-card>

    </div>

        <div class="right-col">


    <!-- Weight Chart -->
    <c-card class="card weight-card">
        <div class="header">
            <div class="title-section">
                <span class="card-title">Child Weight Tracking</span>
                <span class="card-subtitle">Track Baby Sarah's Weight over time</span>
            </div>
            <c-select
                class="child-select-weight"
                placeholder="Select Child">

                @if(!empty($childrenList))
                @foreach ($childrenList as $child)
                <li class="select-item" data-value="{{ $child['id'] }}">
                    {{ $child['name'] }}
                </li>

                @endforeach
                <li class="select-item" data-value="all-children">
                    All Children
                </li>
                @else
                <li class="select-item disabled">
                    No children available
                </li>
                @endif
            </c-select>
        </div>
        <hr class="divider">
        <div class="card-body">
            <canvas id="weightChart">

            </canvas>
            <div class="no-data-message weight-no-data" style="display:none;">
                No weight records available for this child
            </div>
        </div>
    </c-card>
    </div>

   
</main>
 @endif

<script>
    const growthData = <?php echo json_encode($growthData); ?>;




    function createGradient(color, ctx) {
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, color.replace("1)", "0.1)"));
        gradient.addColorStop(1, color.replace("1)", "0)"));
        return gradient;
    }

    const bmiCtx = document.getElementById("bmiChart").getContext("2d");
    const heightCtx = document.getElementById("heightChart").getContext("2d");
    const weightCtx = document.getElementById("weightChart").getContext("2d");

    function buildDatasets(children, type, ctx, color) {
        return children.map(child => ({
            label: child.name,
            data: child[type],
            borderColor: color,
            backgroundColor: createGradient(color, ctx),
            tension: 0.4,
            fill: true,
            pointRadius: 4,
            pointHoverRadius: 6,
        }));
    }

    function getLabels(children) {
        return children[0]?.labels ?? [];
    }


    let bmiChart = new Chart(bmiCtx, {
        type: "line",
        data: {
            labels: getLabels(growthData),
            datasets: buildDatasets(growthData, "bmi", bmiCtx, "rgba(168,85,247,1)")
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

    let heightChart = new Chart(heightCtx, {
        type: "line",
        data: {
            labels: getLabels(growthData),
            datasets: buildDatasets(growthData, "height", heightCtx, "rgba(59,130,246,1)")
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

    let weightChart = new Chart(weightCtx, {
        type: "line",
        data: {
            labels: getLabels(growthData),
            datasets: buildDatasets(growthData, "weight", weightCtx, "rgba(34,197,94,1)")
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


    function updateCharts(childId) {

        let filteredChildren;

        if (!childId || childId === "all-children") {
            filteredChildren = growthData;
        } else {
            filteredChildren = growthData.filter(child => child.id == childId);
        }

        const labels = getLabels(filteredChildren);

        bmiChart.data.labels = labels;
        bmiChart.data.datasets = buildDatasets(filteredChildren, "bmi", bmiCtx, "rgba(168,85,247,1)");

        heightChart.data.labels = labels;
        heightChart.data.datasets = buildDatasets(filteredChildren, "height", heightCtx, "rgba(59,130,246,1)");

        weightChart.data.labels = labels;
        weightChart.data.datasets = buildDatasets(filteredChildren, "weight", weightCtx, "rgba(34,197,94,1)");

        bmiChart.update();
        heightChart.update();
        weightChart.update();
    }
document.querySelectorAll(".child-select-bmi .select-item").forEach(item => {

    item.addEventListener("click", function () {

        const childId = this.dataset.value;

        let filtered = childId === "all-children"
            ? growthData
            : growthData.filter(child => child.id == childId);

        const noData = !filtered.length || !filtered[0].bmi.length;

        const canvas = document.getElementById("bmiChart");
        const msg = document.querySelector(".bmi-no-data");

        if (noData) {
            canvas.style.display = "none";
            msg.style.display = "block";
            return;
        }

        canvas.style.display = "block";
        msg.style.display = "none";

        bmiChart.data.labels = filtered[0].labels ?? [];
        bmiChart.data.datasets = buildDatasets(
            filtered,
            "bmi",
            bmiCtx,
            "rgba(168,85,247,1)"
        );

        bmiChart.update();
    });

});

    document.querySelectorAll(".child-select-height .select-item").forEach(item => {

    item.addEventListener("click", function () {

        const childId = this.dataset.value;

        let filtered = childId === "all-children"
            ? growthData
            : growthData.filter(child => child.id == childId);

        const noData = !filtered.length || !filtered[0].height.length;

        const canvas = document.getElementById("heightChart");
        const msg = document.querySelector(".height-no-data");

        if (noData) {
            canvas.style.display = "none";
            msg.style.display = "block";
            return;
        }

        canvas.style.display = "block";
        msg.style.display = "none";

        heightChart.data.labels = filtered[0].labels ?? [];
        heightChart.data.datasets = buildDatasets(
            filtered,
            "height",
            heightCtx,
            "rgba(59,130,246,1)"
        );

        heightChart.update();
    });

});
   document.querySelectorAll(".child-select-weight .select-item").forEach(item => {

    item.addEventListener("click", function () {

        const childId = this.dataset.value;

        let filtered = childId === "all-children"
            ? growthData
            : growthData.filter(child => child.id == childId);

        const noData = !filtered.length || !filtered[0].weight.length;

        const canvas = document.getElementById("weightChart");
        const msg = document.querySelector(".weight-no-data");

        if (noData) {
            canvas.style.display = "none";
            msg.style.display = "block";
            return;
        }

        canvas.style.display = "block";
        msg.style.display = "none";

        weightChart.data.labels = filtered[0].labels ?? [];
        weightChart.data.datasets = buildDatasets(
            filtered,
            "weight",
            weightCtx,
            "rgba(34,197,94,1)"
        );

        weightChart.update();
    });

});
</script>


@endsection