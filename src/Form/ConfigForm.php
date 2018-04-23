<?php

/**
* @file
* Contains \Drupal\my_module\Form\MyModuleForm.
*/

namespace Drupal\mydata\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class ConfigForm extends ConfigFormBase {
  /**
  * {@inheritdoc}
  */
  public function getFormId() {
    return 'weather_module_form';
  }

  /**
  * {@inheritdoc}
  */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('weather_module.settings');

    $form['api_key_text'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Weather Api key:'),
      '#default_value' => $config->get('text'),
      '#required' => TRUE
    );
    $form['api_url'] = [
          '#type' => 'url',
          '#title' => $this->t('Url'),
          '#default_value' => $config->get('url'),
           '#required' => TRUE
//          '#options' => ['external' => TRUE]
      ];

      $form['temp'] = array(
          '#type' => 'checkbox',
          '#title' => $this->t('Temperature'),
          '#default_value' => $config->get('temp'),
          '#prefix' => '<div > <b>Parametes to show:<b/>',

      );
      $form['pressure'] = array(
          '#type' => 'checkbox',
          '#title' => $this->t('Pressure'),
          '#default_value' => $config->get('pressure'),
      );
      $form['humidity'] = array(
          '#type' => 'checkbox',
          '#title' => $this->t('Humidity'),
          '#default_value' => $config->get('humidity'),
      );
      $form['temp_min'] = array(
          '#type' => 'checkbox',
          '#title' => $this->t('Min Temperature'),
          '#default_value' => $config->get('temp_min'),
      );
      $form['temp_max'] = array(
          '#type' => 'checkbox',
          '#title' => $this->t('Max Temperature'),
          '#default_value' => $config->get('temp_max'),
          '#suffix' => '</div>',
      );
    return parent::buildForm($form, $form_state);
  }

  /**
  * {@inheritdoc}
  */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
      $config = $this->config('weather_module.settings');
      $total = $form_state->getValue('temp') + $form_state->getValue('pressure') +
          $form_state->getValue('humidity') + $form_state->getValue('temp_min') +
          $form_state->getValue('temp_max');
      if ($total != 0) {

      $config->set('text', $form_state->getValue('api_key_text'))
          ->set('url', $form_state->getValue('api_url'))
          ->set('temp', $form_state->getValue('temp'))
          ->set('pressure', $form_state->getValue('pressure'))
          ->set('humidity', $form_state->getValue('humidity'))
          ->set('temp_min', $form_state->getValue('temp_min'))
          ->set('temp_max', $form_state->getValue('temp_max'))
          ->save();
      }else
      {
          drupal_set_message('You must select at least one paremeter','error');

      }
  }

    /**
     * {@inheritdoc}
     */
    public  function validateForm(array &$form, FormStateInterface $form_state) {
        parent::validateForm($form,$form_state);
//        if(empty($form_state->getValue('api_key_text')) ){
//            $form_state->setError($form['api_key_text'],'You must define the API key.');
//        }
    }

    protected function getEditableConfigNames() {
        return [
            'weather_module.settings',
        ];
    }
}
