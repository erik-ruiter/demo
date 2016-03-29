<?php

namespace Drupal\vacancy\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;

/**
 * Provides a 'Vacancy' block.
 *
 * @Block(
 *   id = "vacancy_block",
 *   admin_label = @Translation("Vacancy block"),
 * )
 */
class VacancyBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = $this->buildVacancyTable();

    return array(
      '#markup' => drupal_render($build),
    );
  }
  
  /**
   * Create the html table
   * 
   * @return array Renderable array
   */
  protected function buildVacancyTable() {
    $service = \Drupal::service('vacancy.vacancy_service');
    $vacancies = $service->getVacancies();

    $build['vacancy'] = array(
      '#type' => 'table',
      '#header' => array(t('Title'), ''),
    );

    foreach($vacancies as $vacancy) {
      $id = $vacancy->id;
      
      $name = $service->slugify($vacancy->title);
      $url = Url::fromRoute('vacancies.vacancy', 
          array('vacancy_id' => $id, 'vacancy_name' => $name)
      );
      
      $build['vacancy'][$id]['title'] = array(
        '#type' => 'markup',
        '#markup' => $vacancy->title,
      );
      $build['vacancy'][$id]['link'] = array(
        '#type' => 'link',
        '#url' => $url,
        '#title' => t('Read more')
      );
    }

    return $build;
  }
}
