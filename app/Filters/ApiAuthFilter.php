<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

use Exception;

use Auth0\SDK\Auth0;
use Auth0\SDK\Configuration\SdkConfiguration;
use Auth0\SDK\Exception\InvalidTokenException;

use GuzzleHttp\Client as GuzzleHttpClient;

class ApiAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authorizationHeader = $request->getHeaderLine('Authorization');

        if ($authorizationHeader && preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
            $token = $matches[1]; // Extracting the token part after 'Bearer '

            $config = new SdkConfiguration(
                strategy: SdkConfiguration::STRATEGY_API,
                domain: 'https://dev-n5j153y8u1ov35zc.us.auth0.com',
                audience: ['http://test-api.com']
            );

            $httpClient = new GuzzleHttpClient(['verify' => false]); // Disable SSL verification - !!! MUST enable in Prduction environment

            $config->setHttpClient($httpClient);

            $auth0 = new Auth0($config);
            try {

                $tokenInfo = $auth0->decode($token, null, null, null, null, null, null, \Auth0\SDK\Token::TYPE_TOKEN);
                // get user data from the decoded token info
                $tokenData = $tokenInfo->toJson();
                $request->setHeader('X-Token-Data', $tokenData);

                // proceed with the request
                return $request;

            } catch (InvalidTokenException $e) {
                // Token validation failed, handle unauthorized access
                $response = service('response');
                $response->setStatusCode(401);
                $response->setContentType('application/json');
                $response->setBody(json_encode(array("error" => "Unauthorized : Token validation failed. Invalid Token.", "code" => "00X1")));
                return $response;

            } catch (Exception $e) {
                // Token validation failed, handle unauthorized access
                $response = service('response');
                $response->setStatusCode(401);
                $response->setContentType('application/json');
                $response->setBody(json_encode(array("error" => "Unauthorized : Token validation failed. Invalid Token.", "code" => "00X2")));
                return $response;
            }

        } else {
            // No or invalid Authorization header, handle unauthorized access
            $response = service('response');
            $response->setStatusCode(400);
            $response->setContentType('application/json');
            $response->setBody(json_encode(array("error" => "Bad Request : Token validation failed. Empty Access Token.", "code" => "00X3")));
            return $response;
        }
    }


    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}
