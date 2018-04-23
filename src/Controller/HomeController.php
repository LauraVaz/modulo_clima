<?php

namespace Drupal\mydata\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

/**
 * Class HomeController.
 *
 * @package Drupal\mydata\Controller
 */
class HomeController extends ControllerBase {


  public function getContent() {
    // First we'll tell the user what's going on. This content can be found
    // in the twig template file: templates/description.html.twig.
    // @todo: Set up links to create nodes and point to devel module.
    $build = [
      'description' => [
        '#theme' => 'mydata_description',
        '#description' => 'foo',
        '#attributes' => [],
      ],
    ];
    return $build;
  }

  /**
   * Display.
   *
   * @return string
   *   Return Hello string.
   */
  public function display() {
      $form = \Drupal::formBuilder()->getForm('Drupal\mydata\Form\MydataForm');
      return $form;
  }

}
