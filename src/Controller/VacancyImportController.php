<?php
namespace Drupal\vacancy\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;

class VacancyImportController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Import vacancies
   * 
   * @todo Run this in cron.
   * @return Renderable array.
   */
  public function content() {
    $filename = drupal_get_path('module', 'vacancy') . '/demo/vacatures.xml';
    $data = file_get_contents($filename);

    $service = \Drupal::service('vacancy.vacancy_service');
    
    $xml = new \SimpleXMLElement($data);
    $num = 0;
    foreach($xml->vacature as $vacancy) {
      if ($service->getVacancyByGuid($vacancy->id) === FALSE) {
        $row = array(
          'title' => (string)$vacancy->titel,
          'description' => (string)$vacancy->functie_omschrijving,
          'link' => (string)$vacancy->sollicitatie_link,
          'guid' => (int)$vacancy->id,
        );
        $service->saveVacancy($row);
        $num++;
      }
    }

    return array(
      '#markup' => 'Imported ' . $num . ' vacancies',
    );
  }
}
