<?php

namespace Drupal\mydata\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

/**
 * Class DisplayTableController.
 *
 * @package Drupal\mydata\Controller
 */
class DisplayTableController extends ControllerBase {


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
    /**return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: display with parameter(s): $name'),
    ];*/

    //create table header
    $header_table = array(
     'id'=>    t('No'),
      'name' => t('Nombre de la ciudad'),
        'code' => t('Código del pais'),
        'opt' => t(''),
        'opt1' => t(''),
    );

//select records from table
    $query = \Drupal::database()->select('cities', 'c');
      $query->fields('c', ['id','name','code']);
      $results = $query->execute()->fetchAll();
        $rows=array();
    foreach($results as $data){
        $delete = Url::fromUserInput('/mydata/City/delete/'.$data->id);
        $edit   = Url::fromUserInput('/mydata/City?num='.$data->id);

      //print the data from table
             $rows[] = array(
            'id' =>$data->id,
                'name' => $data->name,
                'code' => $data->code,
                 \Drupal::l('Delete', $delete),
                 \Drupal::l('Edit', $edit),
            );
    }
    //display data in site
      $form['nid'] = array(
            '#type'     => 'markup',
            '#prefix' => '<a href="'.\Drupal::url('mydata.mydata_form').'" class="button button-action button--primary button--small" data-drupal-link-system-path="node/add/event">Nueva ciudad',
            '#suffix' => '</a>',
            );
    $form['table'] = [
            '#type' => 'table',
            '#header' => $header_table,
            '#rows' => $rows,
            '#empty' => t('No existen cuidades insertadas'),
        ];
//        echo '<pre>';print_r($form['table']);exit;
        return $form;

  }

}
