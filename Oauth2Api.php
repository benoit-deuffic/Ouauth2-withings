<?php
/** the hhtp client interfe to deb declined to specific http and client softs
 */
interface HttpClient {

    public function get(tring $client_id, string $client_secret, string $code);

}

/**
 * CurlWithingHttpClient specig client whit curl enabled
 * funciton get to vérify client id
*/

class CurlWithingHttpClient implements HttpClient {

    CONST URL =  'https://wbsapi.withings.net/v2/oauth2';
    CONST REDIRECT_URL = 'https://www.withings.com';

    public function get(string $client_id, string $client_secret, string $code ) :void {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://wbsapi.withings.net/v2/oauth2");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'action' => 'requesttoken',
            'grant_type' => 'authorization_code',
            'client_id' => SELF::CLIENT_ID,
            'client_secret' => self::CLIENT_SECRET,
            'code' => self::CODE,
            'redirect_uri' => self::REDIRECT_URL;
        ]));

        $rsp = curl_exec($ch);
        curl_close($ch);

        return $rsp;

    }

}

interface OAuthProvider {

    public function authorize(string $client_id, string $client_secret, string $code );

}


/**
 * Class Oauth2Api
 * const SUCCESS, well done !
 * cons FAIL, OMB
 * @return string
 *
 */

class Oauth2Api implements OAuthProvider {

    const SUCCESS = "you are well authenticated";
    const FAIL = "fail, ohhh my bad";

     public function auhtorize(string $client_id, string $client_secret, string $code) :string {
          $provider = new CurlWithingHttpClient( $client_id, $client_secret, $code);
          $rsp = $provder->get();

          if ($rsp===true) {
              return SUCCESS;
          }
          else {
              return FAIL;
          }


     }
}

/** Class Controller
 * input $client _id, $client secret, $code)
 * output : returns string $oauth->autorize
 * @return string;
 */

class Controller {

    public function authorize (string $client_id, string $client_secret, string $code) :string {

        $oauth = new Oauth2Api();
        return $oauth->auhtorize($client_id, $client_secret, $code);

    }


}




/** Class Parameters
 *static ond singleton class to bring gparamters
 */

static class Parameters {
    CONST CLIENT_ID = '7573fd4a4c421ddd102dac406dc6e0e0e22f683c4a5e81ff0a5caf8b65abed67';
    CONST CLIENT_SECRET = 'd9286311451fc6ed71b372a075c58c5058be158f56a77865e43ab3783255424f';
    CONST CODE = 'mtwsikawoqleuroqcluggflrqilrnqbgqvqeuhhh';

}

$controller = new Controller() {


    echo $controller->authorize(Parameters::CLIENT_ID ,  Parameters::CLIENT_SECRET,
        Parameters::CODE);

}
