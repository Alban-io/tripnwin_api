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
      $this->app['poi_persister']->create($poi);

    }

  }

  protected function createPOI($json) {

    $json = $json['infos_generales'][0];

    $poi = array(

      'name' => $json['nom'],
      'description' => $json['descriptif'],
      'latitude' => $json['coord_geo_latitude'],
      'longitude' => $json['coord_geo_longitude']
    );

    return $poi;

  }
}
