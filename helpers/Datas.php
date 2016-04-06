<?php

$d = new DateTime( 'NOW', new DateTimeZone( 'America/Sao_Paulo' ) );
//echo $d->format('d/m/Y');
$d1 = new DateTime( "18-07-2013" );
$d2 = new DateTime( "15-09-2013" );
$diff = $d2->diff( $d1 );

$data = new DateTime();
$hoje = sprintf(
        "Hoje e %s/%s/%s", $data->format( 'd' ), $data->format( 'M' ), $data->format( 'Y' )
);
echo $hoje;
?>