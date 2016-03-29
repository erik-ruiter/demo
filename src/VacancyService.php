<?php

/**
 * @file
 * Contains Drupal\vacancy\VacancyService.
 */

namespace Drupal\vacancy;

class VacancyService {
  
  public function __construct() {}
  
  /**
   * Get vacancy by id
   * @param int $id
   * @return object
   */
  public function getVacancyById($id) {
    $dbh = db_select('vacancy', 'v')->fields('v')->condition('id', $id, '=')->execute();
    return $dbh->fetch();
  }
  
  /**
   * Get vacancy by guid
   * @param int $guid
   * @return object
   */
  public function getVacancyByGuid($guid) {
    $dbh = db_select('vacancy', 'v')->fields('v')->condition('guid', $guid, '=')->execute();
    return $dbh->fetch();
  }
  
  /**
   * Get all vacancies
   * @return array
   */
  public function getVacancies() {
    $dbh = db_select('vacancy', 'v')->fields('v')->execute();
    return $dbh->fetchAll();
  }
  
  /**
   * Save vacancy to DB
   * 
   * @param array $vacancy
   * @return int $id
   */
  public function saveVacancy($vacancy) {    
    $txn = db_transaction();

    try {
      $id = db_insert('vacancy')->fields($vacancy)->execute();

      return $id;
    }
    catch (Exception $e) {
      $txn->rollback();

      // Log the exception to watchdog.
      watchdog_exception('vacancy', $e);
    }
  }
  
  /**
   * Transform title to valid url name
   * 
   * @param string $text
   * @return string $text
   */
  public function slugify($text) { 
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
    $text = trim($text, '-');
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = strtolower($text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    return $text;
  }
}
