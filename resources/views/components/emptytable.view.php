<?php
$alt = !empty($alt) ? $alt : "No data"

?>

<div class="no-data-box">
   <img src="{{ asset('assets/icons/empty-data.svg') }}" alt="No Children" />
   <h2>
      @if (!empty($title))
         {{ $title }}
      @else 
         No data yet
      @endif
   </h2>
   <p>
      @if (!empty($description))
         {{ $description }}
      @else 
         No data is available yet
      @endif
   </p>
</div>