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
    description="No growth tracking data available. Start recording your child's height, weight, and BMI measurements to view their growth progress here." />
@else
<main class="container parent-growth-container">
    <div class="parent-growth-filter">
        <c-select class="child-select-shared" searchable placeholder="Select Child" value="all-children">
            @if(!empty($childrenList))
            <li class="select-item" data-value="all-children">
                All Children
            </li>
            @foreach ($childrenList as $child)
            <li class="select-item" data-value="{{ $child['id'] }}">
                {{ $child['name'] }}
            </li>
            @endforeach
            @else
            <li class="select-item disabled">
                No children available
            </li>
            @endif
        </c-select>
    </div>

    <!-- BMI Chart -->

    <div class="left-col">

        <c-card class="card bmi-card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">Child BMI Tracking</span>
                    <span id="bmiSubtitle" class="card-subtitle">
                        Track All Children's BMI over time
                    </span>
                </div>
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
                    <span class="card-subtitle" id="heightSubtitle">Track All Children Height over time</span>
                </div>
            </div>
            <hr class="divider">
            <div class="card-body">
                <canvas id="heightChart">


                </canvas>

            </div>
        </c-card>

    </div>

    <div class="right-col">


        <!-- Weight Chart -->
        <c-card class="card weight-card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">Child Weight Tracking</span>
                    <span class="card-subtitle" id="weightSubtitle">Track All Children Weight over time</span>
                </div>
            </div>
            <hr class="divider">
            <div class="card-body">
                <canvas id="weightChart">

                </canvas>
            </div>
        </c-card>
    </div>


</main>
@endif

<script>
    const growthData = <?php echo json_encode($growthData); ?>;

    const childrenList = <?php echo json_encode($childrenList ?? []); ?>;

    const bmiCanvas = document.getElementById("bmiChart");
    const heightCanvas = document.getElementById("heightChart");
    const weightCanvas = document.getElementById("weightChart");

    if (bmiCanvas && heightCanvas && weightCanvas) {
        function createGradient(color, ctx) {
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, color.replace("1)", "0.1)"));
            gradient.addColorStop(1, color.replace("1)", "0)"));
            return gradient;
        }

        const bmiCtx = bmiCanvas.getContext("2d");
        const heightCtx = heightCanvas.getContext("2d");
        const weightCtx = weightCanvas.getContext("2d");

        const growthDataById = new Map(growthData.map(child => [String(child.id), child]));
        const allChildrenOrder = childrenList.map(child => ({
            id: String(child.id),
            name: child.name
        }));

        function getCombinedLabels(children) {
            const allLabels = [];
            children.forEach(child => {
                (Array.isArray(child?.labels) ? child.labels : []).forEach(label => {
                    if (!allLabels.includes(label)) {
                        allLabels.push(label);
                    }
                });
            });

            return allLabels;
        }

        function alignDataByLabels(child, metric, combinedLabels) {
            if (!child || !Array.isArray(child.labels) || !Array.isArray(child[metric])) {
                return combinedLabels.map(() => null);
            }

            const valueByLabel = new Map();
            child.labels.forEach((label, index) => {
                valueByLabel.set(label, child[metric][index] ?? null);
            });

            return combinedLabels.map(label => valueByLabel.has(label) ? valueByLabel.get(label) : null);
        }

        function buildDatasets(selectedChildrenMeta, metric, combinedLabels, ctx, color) {
            return selectedChildrenMeta.map(childMeta => {
                const fullRecord = growthDataById.get(String(childMeta.id));

                return {
                    label: childMeta.name,
                    data: alignDataByLabels(fullRecord, metric, combinedLabels),
                    borderColor: color,
                    backgroundColor: createGradient(color, ctx),
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                };
            });
        }

        function createChart(ctx, color) {
            return new Chart(ctx, {
                type: "line",
                data: {
                    labels: [],
                    datasets: []
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
        }

        const bmiChart = createChart(bmiCtx, "rgba(168,85,247,1)");
        const heightChart = createChart(heightCtx, "rgba(59,130,246,1)");
        const weightChart = createChart(weightCtx, "rgba(34,197,94,1)");

        function updateSubtitles(selectionType, selectedChild) {
            if (selectionType === "all") {
                document.getElementById("bmiSubtitle").textContent = "Track All Children's BMI over time";
                document.getElementById("heightSubtitle").textContent = "Track All Children's Height over time";
                document.getElementById("weightSubtitle").textContent = "Track All Children's Weight over time";
                return;
            }

            const childName = selectedChild?.name ?? "Child";
            document.getElementById("bmiSubtitle").textContent = `Track ${childName}'s BMI over time`;
            document.getElementById("heightSubtitle").textContent = `Track ${childName}'s Height over time`;
            document.getElementById("weightSubtitle").textContent = `Track ${childName}'s Weight over time`;
        }

        function updateAllCharts(selectedChildrenMeta) {
            const selectedFullChildren = selectedChildrenMeta
                .map(childMeta => growthDataById.get(String(childMeta.id)))
                .filter(Boolean);

            const combinedLabels = getCombinedLabels(selectedFullChildren);

            bmiChart.data.labels = combinedLabels;
            bmiChart.data.datasets = buildDatasets(selectedChildrenMeta, "bmi", combinedLabels, bmiCtx, "rgba(168,85,247,1)");

            heightChart.data.labels = combinedLabels;
            heightChart.data.datasets = buildDatasets(selectedChildrenMeta, "height", combinedLabels, heightCtx, "rgba(59,130,246,1)");

            weightChart.data.labels = combinedLabels;
            weightChart.data.datasets = buildDatasets(selectedChildrenMeta, "weight", combinedLabels, weightCtx, "rgba(34,197,94,1)");

            bmiChart.update();
            heightChart.update();
            weightChart.update();
        }

        function applySelection(value) {
            if (!value || value === "all-children") {
                updateSubtitles("all");
                updateAllCharts(allChildrenOrder);
                return;
            }

            const childMeta = allChildrenOrder.find(child => String(child.id) === String(value));
            updateSubtitles("single", childMeta);
            updateAllCharts(childMeta ? [childMeta] : []);
        }

        const sharedSelect = document.querySelector(".child-select-shared");
        const selectItems = document.querySelectorAll(".child-select-shared .select-item:not(.disabled)");
        const sharedHiddenInput = sharedSelect?.querySelector("input[type='hidden']");

        if (sharedHiddenInput) {
            sharedHiddenInput.value = "all-children";
        }

        const selectedLabel = sharedSelect?.querySelector(".select-label");
        if (selectedLabel) {
            selectedLabel.textContent = "All Children";
        }

        applySelection("all-children");

        selectItems.forEach(item => {
            item.addEventListener("click", function() {
                applySelection(this.dataset.value);
            });
        });
    }
</script>


@endsection