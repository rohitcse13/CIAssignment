<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

class AuthMiddleware implements FilterInterface
{
    private $secretKey = 'secret1234xyz';
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');
        $response = service('response');
        $session = session();

        $token = null;
        $decoded = null;

        if ($authHeader) {
            $token = str_replace('Bearer ', '', $authHeader);
            try {
                $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
                if ($decoded->exp < time()) {
                    return $this->unauthorizedResponse('Token expired. Please login.');
                }
                return $request;
            } catch (ExpiredException $e) {
                return $this->unauthorizedResponse('Token expired. Please login.');
            } catch (SignatureInvalidException $e) {
                return $this->unauthorizedResponse('Invalid token signature.');
            } catch (Exception $e) {
                return $this->unauthorizedResponse('Invalid token: ' . $e->getMessage());
            }
        }
        if (!$session->get('isLoggedIn')) {
            return $this->unauthorizedResponse('Please login.');
        }
        if (!$this->checkPermissions($request)) {
            return $this->unauthorizedResponse('Unauthorized user.');
        }
        return $request;
    }


    private function unauthorizedResponse($message)
    {
        return service('response')
            ->setStatusCode(401)
            ->setContentType('application/json')
            ->setJSON([
                'status' => false,
                'data' => null,
                'message' => $message,
                'statusCode' => 401
            ]);
    }



    protected function checkPermissions(RequestInterface $request)
    {
        // Implement your permission checking logic here
        // For example, you could check if the user has a specific role or permission
        // based on the current route or user data

        return true; // Allow access by default
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
