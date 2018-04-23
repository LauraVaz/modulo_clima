<?php

namespace Drupal\mydata\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class MydataForm.
 *
 * @package Drupal\mydata\Form
 */
class MydataForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mydata_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $conn = Database::getConnection();
     $record = array();
    if (isset($_GET['num'])) {
        $query = $conn->select('cities', 'c')
            ->condition('id', $_GET['num'])
            ->fields('c');
        $record = $query->execute()->fetchAssoc();

    }

    $form['city_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Nombre de la ciudad:'),
      '#required' => TRUE,
       //'#default_values' => array(array('id')),
      '#default_value' => (isset($record['name']) && $_GET['num']) ? $record['name']:'',
      );
    //print_r($form);die();

    $form['city_code'] = array(
      '#type' => 'textfield',
      '#title' => t('Código del pais:'),
      '#maxlength' => 2,
      '#default_value' => (isset($record['code']) && $_GET['num']) ? $record['code']:'',
      '#required' => TRUE
      );

    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'Guardar',
        //'#value' => t('Submit'),
    ];

    return $form;
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {

         $name = $form_state->getValue('city_name');
          if(preg_match('/[^A-Za-z]/', $name)) {
             $form_state->setErrorByName('city_name', $this->t('El nombre de la ciudad no debe contener espacios'));
          }

          // Confirm that age is numeric.
      if(!preg_match('/[a-z]/', $name)) {
             $form_state->setErrorByName('city_code', $this->t('El código del pais debe ser una cadena de texto'));
            }

         /* $number = $form_state->getValue('candidate_age');
          if(!preg_match('/[^A-Za-z]/', $number)) {
             $form_state->setErrorByName('candidate_age', $this->t('your age must in numbers'));
          }*/



    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $field=$form_state->getValues();
    $name=$field['city_name'];
    $code=  $field['city_code'];

    if (isset($_GET['num'])) {
          $field  = array(
              'name'   => $name,
              'code' =>  $code,
          );
        if(!$this->exists($name)) {
            $query = \Drupal::database();
            $query->update('cities')
                ->fields($field)
                ->condition('id', $_GET['num'])
                ->execute();
            drupal_set_message("satisfactoriamente actualizado");
            $form_state->setRedirect('mydata.display_table_controller_display');
        }
        else{
            drupal_set_message("* El nombre de la ciudad ya existe",'error');
        }

      }
       else
       {
           $field  = array(
               'name'   => $name,
               'code' =>  $code,
           );
           if(!$this->exists($name)) {
               $query = \Drupal::database();
               $query->insert('cities')
                   ->fields($field)
                   ->execute();
               drupal_set_message("satisfactoriamente insertado");

               $response = new RedirectResponse(\Drupal::url('mydata.display_table_controller_display'));
               $response->send();
           }
           else{
               drupal_set_message("* El nombre de la ciudad ya existe",'error');
           }
       }
     }

    public static function exists($name) {
        $result = \Drupal::database()->select('cities', 'c')
            ->fields('c', ['name'])
            ->condition('name', $name, '=')
            ->execute()
            ->fetchField();
        return (bool) $result;
    }

}
