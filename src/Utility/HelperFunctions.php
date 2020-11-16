<?php


namespace Drupal\sharpspring_integration\Utility;

use GuzzleHttp\Exception\RequestException;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Component\Serialization\Json;

class HelperFunctions {

  /**
   * Get submitted form values from fields configured in Sharpspring forms.
   * Loop over form settings and get all configured field values
   *
   * @param EntityInterface $form_fields Lead Form config entity
   * @param FormStateInterface $form_state
   *
   * @return array
   */
  public function get_values($form_fields, FormStateInterface $form_state) {
    $lead = [];

    foreach ($form_fields as $lead_api_field => $form_field) {
      switch ($lead_api_field) {
        case ($lead_api_field == 'uuid' || $lead_api_field == 'id'):
        {
          break;
        }
        case ($lead_api_field == 'leadStatus' || $lead_api_field == 'ownerID'):
        {
          if (!empty($form_field)) {
            $lead[$lead_api_field] = $form_field;
          }
          break;
        }
        case ($lead_api_field == 'isUnsubscribed'):
        {
          if (isset($form_field)) {
            $lead[$lead_api_field] = (int) !$form_field;
          }
          break;
        }
        default:
        {
          if (!empty($form_field)) {
            $lead_value = $form_state->getValue($form_field);

            if (!empty($lead_value)) {
              $lead[$lead_api_field] = $lead_value;
            }
          }
        }
      }
    }

    return $lead;
  }

  /**
   * Send lead to Sharpspring API using specified method
   *
   * @param string $url request url
   * @param string $method method used in API request
   * @param array $request request body
   *
   * @return int
   */
  public function send_lead($url, $method, $request) {
    $logger = \Drupal::logger('sharpspring_integration');
    $data = $this->form_request($method, $request);
    $status_code = 0;
    $success = FALSE;

    try {
      $response = \Drupal::httpClient()->post($url, [
        'headers' => [
          'Content-Type' => 'application/json',
          'Content-Length' => strlen($data),
          'Expect',
        ],
        'body' => $data,
      ]);

      $body = json_decode($response->getBody());

      if (empty($body)) {
        return 0;
      }

      switch ($method) {
        case 'createLeads':
        {
          $body = $body->result->creates[0];
          if (!empty($body->success)) {
            $success = $body->success;
          }
          $status_message = 'Lead has been created';

          break;
        }
        case 'updateLeads':
        {
          $body = $body->result->updates[0];
          if (!empty($body->success)) {
            $success = $body->success;
          }
          $status_message = 'Lead has been updated';

          break;
        }
      }

      switch ($response->getStatusCode()) {
        case 200:
        {
          switch ($success) {
            case TRUE:
            {
              $status_code = 200;
              $logger->notice($status_message);

              break;
            }
            default:
            {
              $status_code = $body->error->code;
              $status_message = $body->error->message;

              break;
            }
          }

          break;
        }
        default :
        {
          $logger->notice("API error: <br><br>" . "API responded with status code " .
            $response->getStatusCode());

          break;
        }
      }

      if ($method == 'createLeads' && ($status_code != 301 && $status_code != 200)) {
        $logger->notice("API method createLeads returned error " .
          $status_code . ": <br>" . $status_message);
      }
    } catch (RequestException $e) {
      $logger->error($e->getMessage());
    }

    return $status_code;
  }

  /**
   * @param string $method Method used in request
   * @param array $request request body
   *
   * @return false|string
   */
  public function form_request($method, $request) {
    $request_id = substr(md5(time()), 0, 14); //random string

    $data = [
      'method' => $method,
      'params' => ['objects' => [$request]],
      'id' => $request_id,
    ];

    return Json::encode($data);
  }

}