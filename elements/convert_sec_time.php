<?php
function convert_sec_in_time($Time) {
    if ($Time < 3600) { 
      $heures = 0; 
      
    if ($Time < 60) {
        $minutes = 0;
    } 
    else {
        $minutes = round($Time / 60);
    } 
    
    $secondes = floor($Time % 60); 
    } 
    else { 
    $heures = round($Time / 3600); 
    $secondes = round($Time % 3600); 
    $minutes = floor($secondes / 60); 
    } 
    
    $secondes2 = round($secondes % 60); 
    
    $TimeFinal = "$heures h $minutes min $secondes2 s"; 
    return $TimeFinal; 
}