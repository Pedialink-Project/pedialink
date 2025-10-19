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
    
    <c-link $type="primary">
      Get Started
    </c-link>
  </div>
</div>
<!-- Body -->
<div class="main-body">
  <!-- Main Content -->
  <div class="main-content">
    <!-- Hero Section -->
    <div class="hero">
      <h1>Your Partner in Parenthood</h1>
      <p>Connect with top PHM & doctors, monitor your child's health, and receive personalized support every step of the
        way.</p>
      <c-button $type="primary">
        Get Started
      </c-button>
    </div>

    <!-- About Section -->
    <div id="about" class="section">
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

    <!-- For Parents Section -->
    <div id="for-parents" class="section">
      <h2>For Parents</h2>
      <p>Manage your child's health, connect with PHM doctors, and access personalized support.</p>
      <!-- Card Container -->
      <div class="cards-container">
        <!-- Card-1 -->
        <div class="image-card">
          <img src="assets/home/parent-01.png" class="icon" alt="">
          <h3>Health Monitoring</h3>
          <p>Track growth, development, and milestones with easy-to-use tools.</p>
        </div>
        <!-- Card-2 -->
        <div class="image-card">
          <img src="assets/home/parent-02.png" alt="">
          <h3>PHM Doctor Consultations</h3>
          <p>Schedule virtual consultations with experienced PHM doctors.</p>
        </div>
        <!-- Card-3 -->
        <div class="image-card">
          <img src="assets/home/parent-03.png" class="" alt="">
          <h3>Personalized Support</h3>
          <p>Receive tailored advice and support for your child's specific needs.</p>
        </div>
      </div>
    </div>

    <!-- For PHM & Doctor Section -->
    <div id="for-doctors" class="section">
      <h2>For PHM & Doctors</h2>
      <p>Provide expert care, manage patient information, and collaborate effectively.</p>
      <!-- Card Container -->
      <div class="cards-container">
        <!-- Card-1 -->
        <div class="image-card">
          <img src="assets/home/phm-doc-01.png" alt="">
          <h3>Patient Management</h3>
          <p>Access and manage patient health information securely.</p>
        </div>
        <!-- Card-2 -->
        <div class="image-card">
          <img src="assets/home/phm-doc-02.png" alt="">
          <h3>Virtual Consultations</h3>
          <p>Conduct virtual consultations with parents and provide expert guidance.</p>
        </div>
        <!-- Card-3 -->
        <div class="image-card">
          <img src="assets/home/phm-doc-03.png" alt="">
          <h3>Collaboration Tools</h3>
          <p>Collaborate with other healthcare professionals for comprehensive care.</p>
        </div>
      </div>
    </div>

    

    <!-- How It Works-->
    <div id="hiw" class="section">
      <h2>How It Works</h2>
      <p>Get started with PediaLink in a few simple steps.</p>
      <!-- Card Container -->
      <div class="cards-container">
        <!-- Card-1 -->
        <div class="image-card">
          <img src="assets/home/hiw-01.png" alt="">
          <h3>Sign Up</h3>
          <p>Create your account and complete your profile.</p>
        </div>
        <!-- Card-2 -->
        <div class="image-card">
          <img src="assets/home/hiw-02.png" alt="">
          <h3>Connect with a PHM Doctor</h3>
          <p>Choose a PHM doctor from our network or get matched with one.</p>
        </div>
        <!-- Card-3 -->
        <div class="image-card">
          <img src="assets/home/hiw-03.png" alt="">
          <h3>Start Monitoring</h3>
          <p>Begin tracking your child's health and accessing support.</p>
        </div>
      </div>
    </div>

    
    <!-- Call to Action -->
    <div class="section cta">
      <h2>Ready to Get Started?</h2>
      <p>Join PediaLink today and experience the difference in child and mother care.</p>
      <c-button $type="primary">
        Get Started
      </c-button>
    </div>


    <!-- Footer -->
    <div class="section footer">
      <div class="footer-links">
        <a href="">Privacy Policy</a>
        <a href="">Terms of Service</a>
        <a href="">Contact Us</a>
      </div>
      <p>&copy; 2023 PediaLink. All rights reserved.</p>
    </div>
  </div>
</div>

@endsection