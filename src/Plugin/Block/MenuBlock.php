<?php

namespace Drupal\mydata\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'MenuBlock' block.
 *
 * @Block(
 *  id = "menu_block",
 *  admin_label = @Translation("Menu block"),
 * )
 */
class MenuBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
  //$form = \Drupal::formBuilder()->getForm('Drupal\mydata\Form\MydataForm');


      $form['cities'] = array(
          '#type'     => 'markup',
          '#prefix' => '<a href="'.\Drupal::url('mydata.display_table_controller_display').'" data-drupal-link-system-path="node/add/event">Ciudades',
          '#suffix' => '</a><br>',
      );
      $form['weather'] = array(
          '#type'     => 'markup',
          '#prefix' => '<a href="'.\Drupal::url('mydata.weather_form').'" data-drupal-link-system-path="node/add/event">Ver clima',
          '#suffix' => '</a>',
      );
      return $form;
  }

}
