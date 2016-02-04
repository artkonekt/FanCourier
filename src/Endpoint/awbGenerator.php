<?php

namespace FanCurier\Endpoint;

use FanCurier\Endpoint\endpointInterface;
use FanCurier\Plugin\csv\csvGenerator;
use FanCurier\Plugin\Curl;

/**
 * Description of fanCurier
 *
 * @author csaba.balint@reea.net
 */
class awbGenerator extends csvGenerator implements endpointInterface {

  protected $url = 'https://www.selfawb.ro/import_awb_integrat.php';
  protected $user;

  public static function setUp($user) {
    return new awbGenerator($user);
  }

  public function __construct($user) {
    $this->user = $user;
  }

  public function getAwb() {

    $post = array(
      'username' => $this->user->name,
      'client_id' => $this->user->id,
      'user_pass' => $this->user->pass,
      'fisier' => isset($file) ? $file : $this->getFile(),
    );

    $curl = new Curl($this->url);
    $rp = $curl->curlRequest($post);
    if ($rp['info']['http_code'] == 200) {
      $response = str_getcsv($rp['response'], "\n");
      return $response;
    }
    else {
      throw new Exception($rp['response']);
    }
  }

}
