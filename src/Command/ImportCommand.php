<?php

namespace TripNWin\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command {

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

      $pois[] = createPOI($jsonPOI);


    }

  }

  protected function createPOI($json) {

    $poi = array(
      'name' => $json['nom'],
      'description' => $json['descriptif'],
      'latitude' => $json['coord_geo_latitude'],
      'longitude' => $json['coord_geo_longitude']
    );

    return $poi;

  }
}
