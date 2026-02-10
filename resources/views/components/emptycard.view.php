<?php

?>

<div class="no-data-card">
   <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
      <path d="M3 12h4l3-9 4 18 3-9h4"/>
   </svg>
   <h3 class="no-data-card-title">
      @if (!empty($title))
         {{ $title }}
      @else 
         No data yet
      @endif
   </h3>
   <p class="no-data-card-description">
      @if (!empty($description))
         {{ $description }}
      @else 
         No data is available yet
      @endif
   </p>
</div>