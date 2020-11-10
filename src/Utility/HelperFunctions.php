<?php


namespace Drupal\sharpspring_integration\Utility;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use GuzzleHttp\Exception\RequestException;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityInterface;

class HelperFunctions {
  /**
   * Get form values from form fields specified in Sharpspring forms settings
   * Loop over form settings and get all configured form field values
   *
   * @param EntityInterface $form_fields Lead Form config entity
   * @param FormStateInterface $form_state
   * @return array
   */
  public function get_values($form_fields, FormStateInterface $form_state) {
    $form_values = [];

    foreach ($form_fields as $lead_setting => $lead_setting_value) {
      switch ($lead_setting) {
        case ($lead_setting == 'uuid' || $lead_setting == 'id'):
        {
          break;
        }
        default:
        {
          if (!empty($lead_setting_value)) {
            $form_values[$lead_setting] = $form_state->getValue($lead_setting_value);
          }
        }
      }
    }

    return $form_values;
  }

  /**
   * Send the collected data to Sharpspring Api
   *
   * @param string $url Api address
   * @param array $request request data
   * @return boolean
   * @throws InvalidPluginDefinitionException
   * @throws PluginNotFoundException
   */
  public function send_request($url, &$request) {
    $logger = \Drupal::logger('sharpspring_integration');

    // api config
    $api = \Drupal::entityTypeManager()->getStorage('api')->load('default');
    $account_id = (string) $api->accountId;
    $account_secret = (string) $api->secretKey;

    if (empty($account_id) || empty($account_secret)) {
      $logger->error("Could not create Lead. Please check your API settings");
      return false;
    }

    try {
      $response = \Drupal::httpClient()->post($url, ['json' => $request]);
      $data = json_decode($response->getBody());
      // DBLog messages
      if ($response->getStatusCode() == 200) {
        if ($data->success) {
          $logger->notice('Lead created successfully');
        }
        else {
          $logger->warning('Lead API error: ' . $data->error);
        }
      }
      else {
        $logger->error('Sharpspring API error: <br />' . $data->error);
      }
    } catch (RequestException $e) {
      $logger->error($e->getMessage());
    }
  }


}