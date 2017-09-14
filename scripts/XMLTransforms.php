<?php
//Erreur dans ce script : 5 Erreur : Les attributs existe déjà => Pas d'insidence chercher quels pdv sont concerner + gestion des logs a ajouté
//Ajouter la rupture carburant 
//
//
//Chargement du fichier XML d'entrée pris sur le site .gouv avec la date du jour
$Oldxml=simplexml_load_file("http://localhost/FuelPrices/scripts/zip/PrixCarburants_quotidien_".    date("Ymd", strtotime("-1 day"))        .".xml") or die("Error: Cannot create object");

// Ajouter le header <?xml version="1.0" encoding="utf-8"? 
$xml = new SimpleXMLElement("<markers></markers>");

//Pour chaque point de vente on ajoute un enfant <marker />
foreach($Oldxml->pdv as $pdv) {
    //Creation de la ligne <marker /> du point de vente
    $marker = $xml->addChild("marker");
    
    //Ajout de l'id du pdv (Id dicter par le site .gouv)
    $marker->addAttribute("id",$pdv['id']);
    //Ajout du nom du pdv : On choisi l'ID du pdv car les noms des stations ne sont pas disponible : A supprimer certainement -> Info repeter finalement
    $marker->addAttribute('name', $pdv['id']);
    
    //Calcul et ajout des coords geo : on divise par 100000 pour avoir la latitude/longitude en coordonnées GeoDecimal (WSG84)
    $latitude = $pdv['latitude'] / 100000;
    $longitude = $pdv['longitude'] / 100000;
    $marker->addAttribute('lat', $latitude );
    $marker->addAttribute('lng', $longitude );
    //Cast et ajout de l'adresse
    $addresse = $pdv->adresse ." " .  $pdv->ville ." " . $pdv['cp'];
    $marker->addAttribute('address', $addresse);

    //Boucle pour enumérer tout les services dans la station
    $numservice = 0 ;  //Init de la variable qui sert a compter le nombre de service
    // On parcours tout les services de la station
    foreach ( $pdv->services->service as $service ) 
    {
        //On ajout le service dans le XML sous le nom attribut serviceX X correspondant a la variable $numservice -> Evite d'avoir plusieurs fois le même nom d'attribut
        $marker->addAttribute('service'.$numservice , $service); 
        $numservice = $numservice + 1;
    }
    
    //Ajout des horraires d'ouverture
    $marker->addAttribute('ouverture', $pdv->ouverture['debut']);
    $marker->addAttribute('fermeture', $pdv->ouverture['fin']);
    $marker->addAttribute('saufjour', $pdv->ouverture['saufjour']);
    
    //On boucle pour récuperer tout les prix 
    foreach ($pdv->prix as $prix){
        //Selon le nom du carburant on utilise un case 
        //Les prix sont stockée dans une variable et diviser par 1000 pour les mettres en bon format (1.333 par exemple)
        switch ($prix["nom"]){
            case "Gazole" :
                $prixGazole = $prix["valeur"] / 1000;
                $marker->addAttribute("gazole", $prixGazole);
                break;
                
            case "SP95" :
                $prixSP95 = $prix["valeur"] / 1000;
                $marker->addAttribute("SP95", $prixSP95);
                break;
                
            case "SP98" :
                $prixSP98 = $prix["valeur"] / 1000;
                $marker->addAttribute("SP98", $prixSP98);
                break;
                
            case "GPLc" :
                $prixGPLc = $prix["valeur"] / 1000;
                $marker->addAttribute("GPLc", $prixGPLc);     
                break;
                
            case "E10" :
                $prixE10 = $prix["valeur"] / 1000;
                $marker->addAttribute("E10", $prixE10);
                break;
                
             case "E85" :
                $prixE85 = $prix["valeur"] / 1000;
                $marker->addAttribute("E85", $prixE85);   
                break;    
        }
    }
//    var_dump($pdv->prix);
   // var_dump($pdv);
}

//Sauvegarde le fichier : Ajouter la date actuelle dans le nom ? en string dd:mm:yyyy
$xml->saveXML("test23.xml");
//$xml->saveXML(date("Ymd", strtotime("-1 day"))."-markers.xml");
?>