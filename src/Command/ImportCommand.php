<?php

namespace TripNWin\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Silex\Application;

class ImportCommand extends Command {

  public function __construct(Application $app, $name = null) {
          parent::__construct($name);
          $this->app = $app;
  }


  protected function configure() {

    $this
    ->setName('importPOI')
    ->setDescription('Import POIs Découvertes et divertissement');

  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    $url = 'http://172.16.115.104/201503/Pivot/json/pivot_querier_result_55092df7f04d1.json';

    $json = file_get_contents($url);

    $data = json_decode($json, TRUE);

    $pois = array();

    foreach($data['pivot']['offre'] as $jsonPOI){

      $poi = $this->createPOI($jsonPOI);
      // $this->app['poi_persister']->create($poi);

      print_r($poi);

    }

  }

  protected function createPOI($json) {

    $general = $json['infos_generales'][0];
    $complements = $json['infos_complementaires'];

    $description = $general['descriptif'];
    $nom = $general['nom'];

    if(is_array($description)) {
      $description = json_encode($description);
    }

    if(is_array($nom)) {
      $nom = json_encode($nom);
    }

    if(is_null($description)){
      $description = 'Aucune description';
    };

    if(is_null($nom)){
      $nom = 'Aucun nom';
    }

    $url = '';
    $email = '';
    $tel = '';

    foreach($complements['tmoyencoms'] as $moyen) {
      switch ($moyen['type']) {
        case 'Site internet' :
          $url = $moyen['coordonnees_moyen_com'];
          break;
        case 'Courriel' :
          $email = $moyen['coordonnees_moyen_com'];
          break;
        case 'Téléphone' :
          $tel = $moyen['coordonnees_moyen_com'];
          break;
      }
    }

    $latitude = floatval($general['coord_geo_latitude']);
    $longitude = floatval($general['coord_geo_longitude']);

    $poi = array(
      'name' => html_entity_decode($nom),
      'description' =>  html_entity_decode($description),
      'rue' => $general['rue'],
      'localite' => $general['localite'],
      'commune' => $general['commune'],
      'province' => $general['province'],
      'photo' => $complements['tannexess'][0]['url'],
      'url' => $url,
      'email' => $email,
      'tel' => $tel,
      'latitude' => $latitude,
      'longitude' => $longitude,
      'latitude_sin' => sin(deg2rad($latitude)),
      'latitude_cos' => cos(deg2rad($latitude)),
      'longitude_rad' => deg2rad($longitude)
    );

    return $poi;

  }
}
