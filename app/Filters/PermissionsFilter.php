<?php
// Create a new filter in the Filters folder: PermissionFilter.php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

use App\Helpers\TokenHelper;

class PermissionsFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check for the required permissions in the header
        $requiredPermissions = $arguments ?? null; // Required permissions are coming from the route definition

        // Access token data from the request header
        $tokenData = $request->getHeaderLine('X-Token-Data');

        if (empty($tokenData)) {
            $response = service('response');
            $response->setStatusCode(401);
            $response->setContentType('application/json');
            $response->setBody(json_encode(array("error" => "Unauthorized : Token validation failed. Invalid or Empty Token.", "code" => "00X4")));
            return $response;
        } else {
            // Get array of permissions from the token data
            $tokenHelper = new TokenHelper();
            $tokenPermissions = $tokenHelper->GetUserPermissions($tokenData);

            // Check if the token has the necessary permissions
            foreach ($requiredPermissions as $permission) {
                if (!in_array($permission, $tokenPermissions)) {
                    // The user doesn't have the required permission
                    $response = service('response');
                    $response->setStatusCode(403);
                    $response->setContentType('application/json');
                    $response->setBody(json_encode(array("error" => "Forbidden : You don't have the required permission to access this resource.", "code" => "00X5")));
                    return $response;
                }
            }

            return $request; // Allow the request to proceed if permissions match
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
