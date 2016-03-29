<?php
namespace Drupal\vacancy\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VacancyController extends ControllerBase implements ContainerInjectionInterface {  
  
  /**
   * The _title_callback for the vacancy route.
   *
   * @param int $vacancy_id
   *   The current id.
   * @param string $vacancy_name
   *   The current name.
   *
   * @return string
   *   The page title.
   */
  public function addPageTitle($vacancy_id, $vacancy_name) {
    $vacancy = $this->getVacancy($vacancy_id);
    return $vacancy->title;
  }
  
  /**
   * The _controller_callback for the vacancy route.
   *
   * @param int $vacancy_id
   *   The current id.
   * @param string $vacancy_name
   *   The current name.
   *
   * @return array
   *   Renderable array.
   */
  public function content($vacancy_id, $vacancy_name) {
    $vacancy = $this->getVacancy($vacancy_id);
    if ($vacancy === FALSE) {
      throw new NotFoundHttpException();
    }
    return array(
      '#theme' => 'vacancy_page',
      '#title' => $vacancy->title,
      '#description' => $vacancy->description,
      '#link' => $vacancy->link,
    );
  }
  
  /**
   * Get a vacancy
   * @param int $vacancy_id
   * @return object
   */
  protected function getVacancy($vacancy_id) {
    $service = \Drupal::service('vacancy.vacancy_service');
    return $service->getVacancyById($vacancy_id);
  }
}
