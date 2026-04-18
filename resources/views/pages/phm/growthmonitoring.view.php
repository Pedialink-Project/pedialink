@extends('layout/portal')

@section('title')
PHM Growth Tracking
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
        <div class="container phm-growth-container">
            <div class="phm-growth-filter">
                <c-select class="child-select-shared" searchable placeholder="Select Child">
                    @if(!empty($childrenList))
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
                                Track selected child's BMI over time
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
                            <span class="card-subtitle" id="heightSubtitle">Track selected child's Height over time</span>
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
                            <span class="card-subtitle" id="weightSubtitle">Track selected child's Weight over time</span>
                        </div>
                    </div>
                    <hr class="divider">
                    <div class="card-body">
                        <canvas id="weightChart">

                        </canvas>
                    </div>
                </c-card>
            </div>
        </div>
    @endif

<script>
    const growthData = <?php echo json_encode($growthData); ?>;

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

        function metricHasData(child, type) {
            return Array.isArray(child?.[type]) && child[type].length > 0;
        }

        function getDefaultChild() {
            const firstWithRecords = growthData.find(child =>
                metricHasData(child, "bmi") ||
                metricHasData(child, "height") ||
                metricHasData(child, "weight")
            );

            return firstWithRecords || growthData[0] || null;
        }

        function buildDatasetForChild(child, type, ctx, color) {
            if (!child) {
                return [];
            }

            return [{
                label: child.name,
                data: Array.isArray(child[type]) ? child[type] : [],
                borderColor: color,
                backgroundColor: createGradient(color, ctx),
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6,
            }];
        }

        function getLabels(child) {
            return Array.isArray(child?.labels) ? child.labels : [];
        }

        function createChart(ctx, color, type) {
            return new Chart(ctx, {
                type: "line",
                data: {
                    labels: [],
                    datasets: buildDatasetForChild(null, type, ctx, color)
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

        const bmiChart = createChart(bmiCtx, "rgba(168,85,247,1)", "bmi");
        const heightChart = createChart(heightCtx, "rgba(59,130,246,1)", "height");
        const weightChart = createChart(weightCtx, "rgba(34,197,94,1)", "weight");

        function updateSubtitles(child) {
            const childName = child?.name ?? "Child";
            document.getElementById("bmiSubtitle").textContent = `Track ${childName}'s BMI over time`;
            document.getElementById("heightSubtitle").textContent = `Track ${childName}'s Height over time`;
            document.getElementById("weightSubtitle").textContent = `Track ${childName}'s Weight over time`;
        }

        function updateCharts(child) {
            const labels = getLabels(child);

            bmiChart.data.labels = labels;
            bmiChart.data.datasets = buildDatasetForChild(child, "bmi", bmiCtx, "rgba(168,85,247,1)");

            heightChart.data.labels = labels;
            heightChart.data.datasets = buildDatasetForChild(child, "height", heightCtx, "rgba(59,130,246,1)");

            weightChart.data.labels = labels;
            weightChart.data.datasets = buildDatasetForChild(child, "weight", weightCtx, "rgba(34,197,94,1)");

            bmiChart.update();
            heightChart.update();
            weightChart.update();
        }

        function applyChildSelection(childId) {
            const child = growthData.find(c => String(c.id) === String(childId)) || null;
            updateSubtitles(child);
            updateCharts(child);
        }

        const sharedSelect = document.querySelector(".child-select-shared");
        const selectItems = document.querySelectorAll(".child-select-shared .select-item:not(.disabled)");
        const sharedHiddenInput = sharedSelect?.querySelector("input[type='hidden']");

        const defaultChild = getDefaultChild();

        if (defaultChild) {
            updateSubtitles(defaultChild);
            updateCharts(defaultChild);

            if (sharedHiddenInput) {
                sharedHiddenInput.value = defaultChild.id;
            }

            const selectedLabel = sharedSelect?.querySelector(".select-label");
            if (selectedLabel) {
                selectedLabel.textContent = defaultChild.name;
            }
        }

        selectItems.forEach(item => {
            item.addEventListener("click", function() {
                applyChildSelection(this.dataset.value);
            });
        });
    }
</script>


@endsection