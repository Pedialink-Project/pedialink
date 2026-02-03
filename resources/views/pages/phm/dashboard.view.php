@extends('layout/portal')

@section('title')
PHM Dashboard
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/parent/dashboard.css') }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endsection



@section('content')
<div class="top-section">

    <section class="greet">
        <h1>Good Moring <span class="user-name">{{ auth()->check() ? auth()->user()->name : 'Parent Name'}}</span>
        </h1>
    </section>
    <section class="pill-container">
        <c-pill>
            <c-slot name="title">Children Profiles</c-slot>
            <c-slot name="number">{{ $linkedChildrenCount ?? 0 }}</c-slot>
            <c-slot name="icon">
                <img src="{{asset('assets/icons/baby-01.svg')}}">
            </c-slot>
        </c-pill>
        <c-pill>
            <c-slot name="title">Maternal Profiles</c-slot>
            <c-slot name="number">{{ $maternalprofileCount ?? 0 }}</c-slot>
            <c-slot name="icon">
                <img src="{{asset('assets/icons/profile.svg')}}">
            </c-slot>
        </c-pill>
        <c-pill>
            <c-slot name="title">Vaccinations</c-slot>
            <c-slot name="number">{{ $vaccinationsCount ?? 0 }}</c-slot>
            <c-slot name="icon">
                <img src="{{asset('assets/icons/vaccine.svg')}}">
            </c-slot>
        </c-pill>
    </section>
</div>

<main class="container">
    <div class="left-col">


        <!-- Upcoming Events Card -->
        <c-card class="card risk-card">
        <div class="header">
            <div class="title-section">
                <span class="card-title">Maternal Health Status</span>
                <span class="card-subtitle">Maternal health status grouped by age</span>
            </div>
        </div>

        <div class="card-body">
            <canvas id="riskChart"></canvas>
        </div>
    </c-card>

        <!-- Upcoming Appoinments Card -->
        <c-card class="card appoinment-card">
        <div class="header">
            <div class="title-section">
                <span class="card-title">Upcoming Appoinments</span>
                <span class="card-subtitle">Your scheduled visits to the clinic</span>
            </div>
            <c-link type="secondary"  href="{{ route('phm.appointments')}}">View Schedule</c-link>
        </div>
        <div class="card-body">
            @if (count($appointments ?? []) === 0)
                <div class="table-empty">No appointments yet</div>
            @else
                @foreach ($appointments as $appointment)
                    <div class="row appoinment">
                        <div class="primary-details">
                            <div class="name">
                                {{ $appointment['name'] ?? 'Appointment' }}
                                @if (!empty($appointment['title']))
                                    - <span>{{ $appointment['title'] }}</span>
                                @endif
                            </div>
                            <div class="sub-details">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M8.32468 4.66309L8.35218 4.54309C8.40468 4.32009 8.49068 3.99209 8.66818 3.66909C8.84918 3.34009 9.12968 3.00659 9.56968 2.78709C10.0082 2.56859 10.5577 2.48709 11.2372 2.57709C11.9872 2.67709 13.4937 2.92509 14.8247 3.60209C16.1632 4.28309 17.4172 5.45459 17.4172 7.40559C17.4172 8.41359 17.0272 9.49509 16.6492 10.2001C16.4677 10.5391 16.2522 10.8631 16.0362 11.0376C15.9867 11.0776 15.9162 11.1276 15.8287 11.1626C15.5887 11.9514 15.1109 12.6468 14.4607 13.1537C13.8104 13.6607 13.0195 13.9544 12.196 13.9948C11.3725 14.0352 10.5566 13.8202 9.85994 13.3793C9.16323 12.9384 8.61971 12.2931 8.30368 11.5316L8.29168 11.5336L8.11418 11.3181L8.11368 11.3171L8.11268 11.3161L8.11018 11.3126L8.10168 11.3026C8.05892 11.2493 8.01774 11.1948 7.97818 11.1391C7.87046 10.9892 7.76871 10.835 7.67318 10.6771C7.43818 10.2881 7.15468 9.73409 6.97318 9.08759C6.79218 8.44109 6.70918 7.68309 6.89918 6.90109C7.08418 6.14009 7.52068 5.39109 8.31118 4.72209L8.32468 4.66309Z"
                                        fill="#71717A" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M8.957 14.4274C8.851 14.2164 8.7205 13.9559 8.5 14.0064C5.798 14.6214 3 16.3969 3 18.2849V20.9999H21V18.2849C21 16.7979 19.264 15.3804 17.2065 14.5449L17.204 14.5399L17.197 14.5264L17.1805 14.5344C16.634 14.3144 16.065 14.1344 15.5 14.0064C15.2485 13.9489 14.9885 14.2949 14.875 14.5114H9L8.957 14.4274Z"
                                        fill="#71717A" />
                                </svg>
                                <div class="sub-name">{{ $appointment['doctor'] ?? 'Clinic' }}</div>
                            </div>
                        </div>
                        <div class="secondary-details">
                            <div class="date">{{ $appointment['date'] ?? '-' }}</div>
                            <c-badge type="primary">{{ $appointment['time'] ?? '-' }}</c-badge>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </c-card>

    </div>

    <div class="right-col">
        <!-- Growth Chart Card -->
        <c-card class="card vaccine-card">

            <div class="header">
                <div class="title-section">
                    <span class="card-title">Monthly Vaccinations Completed</span>
                    <span class="card-subtitle">Tracking vaccination completion rates over time</span>
                </div>
            </div>

            <div class="card-body">
                <div class="chart-wrapper">
                    <canvas id="vaccChart" height="255px"></canvas>
                </div>
            </div>
        </c-card>



        <!-- Upcoming Events Card -->
        <c-card class="card vaccine-card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">Upcoming Vaccinations</span>
                    <span class="card-subtitle">Vaccines due for your children</span>
                </div>
                <c-link type="secondary"  href="{{ route('phm.vaccination')}}">View All</c-link>
            </div>


            <div class="card-body">
                @if (count($vaccinations ?? []) === 0)
                    <div class="table-empty">No vaccinations yet</div>
                @else
                    @foreach ($vaccinations as $vaccination)
                        <div class="row vaccine-row">
                            <div class="primary-details">
                                <div class="name">{{ $vaccination['name'] ?? 'Child' }}</div>
                                <div class="sub-details">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M14.5 9C14.5 10.3807 13.3807 11.5 12 11.5C10.6193 11.5 9.5 10.3807 9.5 9C9.5 7.61929 10.6193 6.5 12 6.5C13.3807 6.5 14.5 7.61929 14.5 9Z"
                                            stroke="#71717A" stroke-width="1.5" />
                                        <path
                                            d="M13.2574 17.4936C12.9201 17.8184 12.4693 18 12.0002 18C11.531 18 11.0802 17.8184 10.7429 17.4936C7.6543 14.5008 3.51519 11.1575 5.53371 6.30373C6.6251 3.67932 9.24494 2 12.0002 2C14.7554 2 17.3752 3.67933 18.4666 6.30373C20.4826 11.1514 16.3536 14.5111 13.2574 17.4936Z"
                                            stroke="#71717A" stroke-width="1.5" />
                                        <path d="M18 20C18 21.1046 15.3137 22 12 22C8.68629 22 6 21.1046 6 20" stroke="#71717A"
                                            stroke-width="1.5" stroke-linecap="round" />
                                    </svg>
                                    <div class="sub-name">{{ $vaccination['location'] ?? 'RHU Center' }}</div>
                                </div>
                            </div>
                            <c-badge type="blue">{{ $vaccination['type'] ?? 'Vaccine' }}</c-badge>
                            <div class="secondary-deatails">
                                <div class="date">{{ $vaccination['date'] ?? '-' }}</div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </c-card>
    </div>

</main>

<script>
    // ---------- Stacked Bar (Antenatal Health Status by Age) ----------
    const riskCtx = document.getElementById('riskChart').getContext('2d');

    const riskData = {
        labels: ['<20 years', '20-24 years', '25-29 years', '30-34 years', '35+ years'],
        datasets: [
            {
                label: 'Good',
                data: {{ json_encode($riskChartData['good'] ?? [0, 0, 0, 0, 0]) }},
                backgroundColor: '#10B981', // green for good
                borderRadius: 6,
                barThickness: 28
            },
            {
                label: 'Bad',
                data: {{ json_encode($riskChartData['bad'] ?? [0, 0, 0, 0, 0]) }},
                backgroundColor: '#F59E0B', // amber for bad
                borderRadius: 6,
                barThickness: 28
            },
            {
                label: 'Critical',
                data: {{ json_encode($riskChartData['critical'] ?? [0, 0, 0, 0, 0]) }},
                backgroundColor: '#EF4444', // red for critical
                borderRadius: 6,
                barThickness: 28
            }
        ]
    };

    console.log('Risk Chart Data:', riskData);

    const riskConfig = {
        type: 'bar',
        data: riskData,
        options: {
            maintainAspectRatio: true,
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
            data: {{ json_encode($vaccinationChartData ?? [0, 0, 0]) }},
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
            ctx.fillText('{{ $vaccinationsCount ?? 0 }}', centerX, centerY - 8);

            // label (lighter)
            ctx.font = '400 13px Inter, Arial';
            ctx.fillStyle = '#6b7280';
            ctx.fillText('Vaccinations', centerX, centerY + 20);
            ctx.restore();
        }
    };

    const vaccConfig = {
        type: 'doughnut',
        data: vaccData,
        options: {
            responsive: false,
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