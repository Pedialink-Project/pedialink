@extends('layout/portal')

@section('title')
Test Portal
@endsection

@section('header')
    Test Table
@endsection

@section('content')
    <?php
    $events = [
        [
            'date' => date('Y-m-d', strtotime('+2 days')),
            'title' => 'MMR - Dose 1',
            'items' => [
            ['child' => 'Kamal', 'time' => '09:00', 'vaccine' => 'MMR'],
            ['child' => 'Saman', 'time' => '09:30', 'vaccine' => 'MMR']
            ],
            'color' => 'linear-gradient(90deg,#10b981,#06b6d4)'
        ],
        [
            'date' => date('Y-m-d'),
            'title' => 'BCG / DTP',
            'items' => [
            ['child' => 'Nimal', 'time' => '11:00', 'vaccine' => 'DTP']
            ],
            'color' => '#f59e0b'
        ]
    ];
    ?>

<c-calendar :events='json_encode($events) ' modalId="myVaccModal" />

<c-modal id="myVaccModal">
    <c-slot name="header">Vaccine</c-slot>
    <c-slot name="footer">
        <c-button variant="primary">Ok</c-button>
    </c-slot>
</c-modal>

@endsection