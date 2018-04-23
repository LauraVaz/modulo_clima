<?php
/**
 * @file
 * Contains \Drupal\resume\Form\ResumeForm.
 */
namespace Drupal\mydata\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use GuzzleHttp\Exception\RequestException;

class CheckWeatherForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'resume_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {


//    $form['candidate_gender'] = array (
//      '#type' => 'select',
//      '#title' => ('Gender'),
//      '#options' => array(
//        'Female' => t('Female'),
//        'male' => t('Male'),
//      ),
//    );

//    $form['candidate_confirmation'] = array (
//      '#type' => 'radios',
//      '#title' => ('Are you above 18 years old?'),
//      '#options' => array(
//        'Yes' =>t('Yes'),
//        'No' =>t('No')
//      ),
//    );

//    $form['candidate_copy'] = array(
//      '#type' => 'checkbox',
//      '#title' => t('Send me a copy of the application.'),
//    );

      //Query DB for Rows
//      $query = db_select('hp_education_years');
//      $query->fields('hp_education_years', array('id', 'years',));
//      $query->orderBy('years', 'ASC');
//      $results = $query->execute();

      $conn = Database::getConnection();
      $record = array();
      $query = $conn->select('cities', 'c')
          ->fields('c',array('id','name', 'code',))
          ->orderBy('name', 'ASC');
      $results = $query->execute();

      //define rows
      $options = array();
      foreach ($results as $result) {
          $options[$result->name.','.$result->code] =
              $result->name
          ;
      }

      $form['data_set'] = array(
          '#type' => 'select',
          '#title' => t('Please select a city'),
          '#options' =>  $options,
          '#required' => TRUE,
          '#ajax' => array(
          'callback' => '::methodChangeAjax',
          'wrapper' => 'edit-fieldsset',
          'method' => 'replace',
          'effect' => 'fade',
          'event' => 'change')
      );

//    $form['actions']['submit'] = array(
//      '#type' => 'submit',
//      '#value' => $this->t('Save'),
//      '#button_type' => 'primary',
//    );

//Ajax changable fields container
//      $form['fieldsset'] = array(
//          '#type' => 'fieldset',
//          '#title' => $this->t('Fields'),
//          '#default_value' => "",
//          '#prefix' => '<div id="edit-fieldsset">',
//          '#suffix' => '</div>',
//      );


      $rows=array();
      $form['table'] = [
          '#type' => 'table',
          '#header' => '',
          '#rows' => '',
          '#prefix' => '<div id="edit-fieldsset">',
          '#suffix' => '</div>',
      ];
    return $form;
  }

    function methodChangeAjax($form, FormStateInterface $form_state) {
        $method = $form_state->getValue('data_set');
        $config = \Drupal::config('weather_module.settings');
      if($method != '') {
          if ($config->get('url') != '') {
              $client = \Drupal::httpClient();
              $url = $config->get('url') . '/data/2.5/weather?q=' . $form_state->getValue('data_set') . '&appid=' . $config->get('text');

              //$a = '{"coord":{"lon":-0.13,"lat":51.51},"weather":[{"id":721,"main":"Haze","description":"haze","icon":"50d"},{"id":701,"main":"Mist","description":"mist","icon":"50d"},{"id":741,"main":"Fog","description":"fog","icon":"50d"},{"id":300,"main":"Drizzle","description":"light intensity drizzle","icon":"09d"}],"base":"stations","main":{"temp":281.87,"pressure":1006,"humidity":87,"temp_min":281.15,"temp_max":283.15},"visibility":2700,"wind":{"speed":2.1,"deg":20},"clouds":{"all":90},"dt":1523472600,"sys":{"type":1,"id":5091,"message":0.0072,"country":"GB","sunrise":1523423523,"sunset":1523472704},"id":2643743,"name":"London","cod":200}';
              //$body = \GuzzleHttp\json_decode($a, true);
              try {
                   $response = $client->get($url);
                   $data = $response->getBody();
                   $body= \GuzzleHttp\json_decode($data,true);
                  //create table header
                  $header_table = array(
                      'name' => t('Parámetros del clima'),
                      'code' => t('Valores'),
                  );

                  foreach ($body['main'] as $key => $val) {
                      if ($config->get($key) != 0) {
                          $rows[] = array(
                              'name' => $key,
                              'code' => $val,
                          );
                      }
                  }

                  $form['table']['#rows'] = $rows;
                  $form['table']['#header'] = $header_table;
              } catch (RequestException $e) {
                drupal_set_message($e->getMessage());
              }




              return $form['table'];
          }
          else{
              drupal_set_message('Ud. debe configurar el EndPoint del Api. Verifique.','error');
          }
      }
        //drupal_set_message('llll');
        return $form['table'] ;
    }

  /**
   * {@inheritdoc}
   */
    public function validateForm(array &$form, FormStateInterface $form_state) {

//      if (strlen($form_state->getValue('candidate_number')) < 10) {
//        $form_state->setErrorByName('candidate_number', $this->t('Mobile number is too short.'));
//      }

    }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

      $config = \Drupal::config('weather_module.settings');
      $client=\Drupal::httpClient();
      $url=$config->get('url').'/data/2.5/weather?q='.$form_state->getValue('data_set').'&appid='.$config->get('text');
     // $request= $client->request('GET', $url);
//      try {
//          $response = $client->get($url);
//          $data = $response->getBody();
//      }
//      catch (RequestException $e) {
//          drupal_set_message($e->getMessage());
//      }
//      $request= $client->get($url);
     $a='{"coord":{"lon":-0.13,"lat":51.51},"weather":[{"id":721,"main":"Haze","description":"haze","icon":"50d"},{"id":701,"main":"Mist","description":"mist","icon":"50d"},{"id":741,"main":"Fog","description":"fog","icon":"50d"},{"id":300,"main":"Drizzle","description":"light intensity drizzle","icon":"09d"}],"base":"stations","main":{"temp":281.87,"pressure":1006,"humidity":87,"temp_min":281.15,"temp_max":283.15},"visibility":2700,"wind":{"speed":2.1,"deg":20},"clouds":{"all":90},"dt":1523472600,"sys":{"type":1,"id":5091,"message":0.0072,"country":"GB","sunrise":1523423523,"sunset":1523472704},"id":2643743,"name":"London","cod":200}';
    //$body= (string)$a->getBody();

    $body= \GuzzleHttp\json_decode($a,true);
    foreach ($form_state->getValues() as $key => $value) {
      drupal_set_message($key . ': ' . $body['coord']['lon']);
    }
      //print_f($body);
     // kint($body);
   }
}