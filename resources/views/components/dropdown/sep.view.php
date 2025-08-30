<?php
// resources/views/components/dropdown.sep.view.php
// simple separator
$uid = 'ddsep_' . bin2hex(random_bytes(5));
?>
<div id="{{ $uid }}" class="dropdown-sep" role="separator" aria-hidden="true"></div>
