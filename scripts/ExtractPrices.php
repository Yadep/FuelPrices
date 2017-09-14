<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
define("url",             'https://donnees.roulez-eco.fr/opendata/jour');
define("FileDestination", './zip/myFile.zip');



$file = file_get_contents(url);
var_dump($file);
file_put_contents(FileDestination, $file);

$zip = new ZipArchive;
if ($zip->open(FileDestination) === TRUE) {
    $zip->extractTo('./zip/');
    $zip->close();
    echo 'Archive extraite';
} else {
    echo "Problème lors de l\'ouverture de l\'archive";
}
?>