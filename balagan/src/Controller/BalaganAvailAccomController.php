<?php
/**
 * @file
 * Contains \Drupal\balagan\Controller\BalaganAvailAccomController.
 */

namespace Drupal\balagan\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

class BalaganAvailAccomController extends ControllerBase {
  //
  /**
   * {@inheritdoc}
   */
  public function content() {
    // Get the list of the reservation node ids.
    $storage = \Drupal::entityManager()->getStorage('node');
    $reserved_list = \Drupal::entityQuery('node')
      ->condition('status', 1)
      ->condition('type', 'balagan_reservation')
      ->condition('field_balagan_reserv_accom', 'NULL', '<>')
      ->condition('field_balagan_reserv_customer', 'NULL', '<>')
      ->execute();
    $reserved_accom = array();
    $build = array();
    foreach ($reserved_list as $nid) {
      $reserved_accom[] = $storage->load($nid)->field_balagan_reserv_accom->getValue()[0]['target_id'];
    }
    if (!empty($reserved_accom)) {
      $accommodations = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'balagan_accommodation')
        ->condition('nid', $reserved_accom, 'NOT IN')
        ->execute();

      foreach ($accommodations as $item) {
        $node = node_load($item);
        $locality = $node->field_balagan_accom_address->getValue()[0]['locality'];
        $start = $node->field_balagan_accom_date->getValue()[0]['value'];
        $end = $node->field_balagan_accom_date->getValue()[1]['value'];
        $people = $node->field_balagan_accom_people->getValue()[0]['value'];
        $link = $node->toLink()->toRenderable();
        $build[] = $link;
        $build[] = array(
          'markup' => array(
            '#type' => 'markup',
            '#markup' => ' ' . $locality . ' ' . $start . ' - ' . $end . ' ' . 'Room for: ' . $people . '<BR>',
          ),
        );
      }
    }
    return $build;
  }

}