@extends('layout/main')

@section('title')
Home
@endsection

@section('css')
<link rel="stylesheet" href="css/pages/home.css">
@endsection



@section('content')

<!-- Navbar -->
<div class="navbar">
  <!-- Logo -->
  <img src="assets/logo.png" alt="PediaLink Logo">
  <!-- Navigation Menu -->
  <div class="navmenu">
    <a href="">About</a>
    <a href="">For Parents</a>
    <a href="">For PHM Doctors</a>
    <a href="">For Resources</a>
    <c-button $type="primary">
      Get Started
    </c-button>
  </div>
</div>
<!-- Body -->
<div class="main-body">
  <!-- Main Content -->
  <div class="main-content">
    <!-- Hero Section -->
    <div class="hero">
      <h1>Your Partmer in Parenthood</h1>
      <p>Connect with top PHM & doctors, monitor your child's health, and receive personalized support every step of the
        way.</p>
      <c-button $type="primary">
        Get Started
      </c-button>


    </div>

    <!-- About Section -->
    <div class="section">
      <h2>About PediaLink</h2>
      <p>PediaLink is a web application designed to support parents and PHM doctors in providing the best possible care
        for children. We offer a range of tools and resources to help you monitor your child's health, communicate
        effectively, and access expert guidance.</p>
      <!-- Card Container -->
      <div class="cards-container">
        <!-- Card-1 -->
        <div class="card">
          <img src="assets/icons/favourite.svg" class="icon" alt="">
          <h3>Comprehensive Health Monitoring</h3>
          <p>Track your child's growth, development, and milestones with our intuitive monitoring tools.</p>
        </div>
        <!-- Card-2 -->
        <div class="card">
          <img src="assets/icons/user-multiple.svg" class="icon" alt="">
          <h3>Expert PHM Doctor Network</h3>
          <p>Connect with a network of experienced PHM doctors specializing in child and maternal care.</p>
        </div>
        <!-- Card-3 -->
        <div class="card">
          <img src="assets/icons/bubble-chat.svg" class="icon" alt="">
          <h3>Seamless Communication & Support</h3>
          <p>Communicate with your PHM doctor, schedule appointments, and access support resources easily.</p>
        </div>
      </div>
    </div>
    <!-- About Section -->
    <div class="section">
      <h2>About PediaLink</h2>
      <p>PediaLink is a web application designed to support parents and PHM doctors in providing the best possible care
        for children. We offer a range of tools and resources to help you monitor your child's health, communicate
        effectively, and access expert guidance.</p>
      <!-- Card Container -->
      <div class="cards-container">
        <!-- Card-1 -->
        <div class="card">
          <img src="" class="icon" alt="">
          <h3>Comprehensive Health Monitoring</h3>
          <p>Track your child's growth, development, and milestones with our intuitive monitoring tools.</p>
        </div>
        <!-- Card-2 -->
        <div class="card">
          <img src="" class="icon" alt="">
          <h3>Comprehensive Health Monitoring</h3>
          <p>Track your child's growth, development, and milestones with our intuitive monitoring tools.</p>
        </div>
        <!-- Card-3 -->
        <div class="card">
          <img src="" class="icon" alt="">
          <h3>Comprehensive Health Monitoring</h3>
          <p>Track your child's growth, development, and milestones with our intuitive monitoring tools.</p>
        </div>
      </div>
    </div>
  </div>
</div>



@endsection