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

Growth of {{ $name .' (C-00'.$id.')' }}
</div>
@endsection

@section('content')
@if(empty($growthData))
<c-emptytable
    alt="No Growth Data"
    title="No Growth Records Yet"
    description="No growth tracking data available. Start recording your child's height, weight, and BMI measurements to view their growth progress here." />
@else
<main class="container">
    <div class="left-col">

      <c-card class="card bmi-card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">BMI Tracking</span>
                    <span class="card-subtitle">Track {{$child['name']}}'s BMI over time</span>
                </div>
                <c-link type="secondary" size="sm" href="{{route('phm.growth.monitoring')}}">View All</c-link>

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
                    <span class="card-title">Height Tracking</span>
                    <span class="card-subtitle">Track {{$child['name']}}'s Height over time</span>
                </div>
                <c-link type="secondary" size="sm" href="{{route('phm.growth.monitoring')}}">View All</c-link>

            </div>
            <hr class="divider">
            <div class="card-body">
                <canvas id="heightChart">

                </canvas>
                 <div class="no-data-message height-no-data" style="display:none;">
                    No Height records available for this child
                </div>
            </div>
        </c-card>


    <div class="right-col">


        <!-- Weight Chart -->
          <c-card class="card weight-card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">Weight Tracking</span>
                    <span class="card-subtitle">Track {{$child['name']}}'s Weight over time</span>
                </div>
                <c-link type="secondary" size="sm" href="{{route('phm.growth.monitoring')}}">View All</c-link>

            </div>
            <hr class="divider">
            <div class="card-body">
                <canvas id="weightChart">

                </canvas>
                 <div class="no-data-message weight-no-data" style="display:none;">
                    No Weight records available for this child
                </div>
            </div>
        </c-card>
    </div>


</main>
@endif

<script>

const growthData = <?php echo json_encode($growthData); ?>;



function createGradient(ctx,color){
    const gradient = ctx.createLinearGradient(0,0,0,400);
    gradient.addColorStop(0,color.replace("1)","0.1)"));
    gradient.addColorStop(1,color.replace("1)","0)"));
    return gradient;
}



function handleNoData(chartId,messageClass,data){

    if(!data || data.length === 0){

        document.getElementById(chartId).style.display="none";
        document.querySelector(messageClass).style.display="block";
        return true;

    }

    return false;

}



if(!handleNoData("bmiChart",".bmi-no-data",growthData.bmi)){

    const ctx = document.getElementById("bmiChart").getContext("2d");

    new Chart(ctx,{
        type:"line",
        data:{
            labels:growthData.labels,
            datasets:[{
                label:"BMI",
                data:growthData.bmi,
                borderColor:"rgba(168,85,247,1)",
                backgroundColor:createGradient(ctx,"rgba(168,85,247,1)"),
                tension:0.4,
                fill:true,
                pointRadius:4
            }]
        },
        options:{
            responsive:true,
            plugins:{legend:{display:false}},
            scales:{y:{beginAtZero:true}}
        }
    });

}



if(!handleNoData("heightChart",".height-no-data",growthData.height)){

    const ctx = document.getElementById("heightChart").getContext("2d");

    new Chart(ctx,{
        type:"line",
        data:{
            labels:growthData.labels,
            datasets:[{
                label:"Height",
                data:growthData.height,
                borderColor:"rgba(59,130,246,1)",
                backgroundColor:createGradient(ctx,"rgba(59,130,246,1)"),
                tension:0.4,
                fill:true,
                pointRadius:4
            }]
        },
        options:{
            responsive:true,
            plugins:{legend:{display:false}},
            scales:{y:{beginAtZero:true}}
        }
    });

}



if(!handleNoData("weightChart",".weight-no-data",growthData.weight)){

    const ctx = document.getElementById("weightChart").getContext("2d");

    new Chart(ctx,{
        type:"line",
        data:{
            labels:growthData.labels,
            datasets:[{
                label:"Weight",
                data:growthData.weight,
                borderColor:"rgba(34,197,94,1)",
                backgroundColor:createGradient(ctx,"rgba(34,197,94,1)"),
                tension:0.4,
                fill:true,
                pointRadius:4
            }]
        },
        options:{
            responsive:true,
            plugins:{legend:{display:false}},
            scales:{y:{beginAtZero:true}}
        }
    });

}

</script>


@endsection