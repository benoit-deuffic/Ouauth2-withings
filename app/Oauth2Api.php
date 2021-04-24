<?php
/** the hhtp client interfe to deb declined to specific http and client softs
 */
interface HttpClient {

    public function get();

}

/**
 * CurlWithingHttpClient specig client whit curl enabled
 * funciton get to vérify client id
*/

class CurlWithingHttpClient implements HttpClient {

    CONST URL =  'https://wbsapi.withings.net/v2/oauth2';
    CONST REDIRECT_URL = 'https://www.withings.com';

    public function get() :bool {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::URL);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'action' => 'requesttoken',
            'grant_type' => 'authorization_code',
            'client_id' =>Parameters::CLIENT_ID,
            'client_secret' => Parameters::CLIENT_SECRET,
            'code' => Parameters::CODE,
            'redirect_uri' => self::REDIRECT_URL,
        ]));
        $rsp = curl_exec($ch);
        curl_close($ch);

        return $rsp;

    }

}

interface IOAuthProvider {

    public function authorize() :string;

}


/**
 * Class Oauth2Api
 * const SUCCESS, well done !
 * cons FAIL, OMB
 * @return string
 *
 */

class Oauth2Api implements IOAuthProvider {

    const SUCCESS = "you are well authenticated";
    const FAIL = "fail, ohhh my bad";

     public function authorize() :string {
          $provider = new CurlWithingHttpClient();
          $rsp = $provider->get();

          if ($rsp===true) {
              return self::SUCCESS;
          }
          else {
              return self::FAIL;
          }

     }
}

/** Class Controller
 * input $client _id, $client secret, $code)
 * output : returns string $oauth->autorize
 * @return string;
 */

class Controller {

    public function authorize () :string {

        $oauth = new Oauth2Api();
        return $oauth->authorize();

    }


}


/** Class Parameters
 *static ond singleton class to bring gparamters
 */

class Parameters {
    const CLIENT_ID = '7573fd4a4c421ddd102dac406dc6e0e0e22f683c4a5e81ff0a5caf8b65abed67';
    const CLIENT_SECRET = 'd9286311451fc6ed71b372a075c58c5058be158f56a77865e43ab3783255424f';
    const CODE = 'mtwsikawoqleuroqcluggflrqilrnqbgqvqeuhhh';

}

$controller = new Controller();
echo $controller->authorize();


