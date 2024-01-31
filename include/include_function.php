<?php 
  function formatDateYmd($dateString)
  {
    $date = new DateTime($dateString);
      $newFormat =$date ->format('Y-m-d');
      return $newFormat;
  }

  function formatDateDmy($dateString)
  {
      $date = new DateTime($dateString);
      $newFormat =$date ->format('m/d/Y');
      return $newFormat;
  }

  function formatDateJFY($dateString)
{
    $date = new DateTime($dateString);
    $newFormat = $date->format('j F Y');
    return $newFormat;
}

function formatDateHIS($dateString)
{
    // Create a DateTime object from the date string
    $date = new DateTime($dateString);

    // Format the date in 'H:i:s' format
    $newFormat = $date->format('H:i:s');

    return $newFormat;
}

function formatDateHI($dateString)
{
    // Create a DateTime object from the date string
    $date = new DateTime($dateString);

    // Format the date in 'H:i:s' format
    $newFormat = $date->format('H:i');

    return $newFormat;
}





  
  
  


    ?>