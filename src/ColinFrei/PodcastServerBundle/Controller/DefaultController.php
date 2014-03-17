<?php

namespace ColinFrei\PodcastServerBundle\Controller;

use Buzz\Browser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * For now this just passes the response from the external server on
     *
     * @param Request $request
     *
     * @return Response
     */
    public function forwardAction(Request $request)
    {
        $logger = $this->get('logger');
        $url = $request->query->get('url');
        $logger->debug('Url requested: ' . $url);

        /** @var Browser $buzz */
        $buzz = $this->get('buzz');
        $requestHeaders = array();
        foreach ($request->headers->all() as $header => $content) {
            if ($header == 'if-modified-since') {
                continue;
            }

            $requestHeaders[$header] = $content[0];
        }
        $logger->debug('Request headers sent to remote server', $requestHeaders);

        $response = $buzz->get($url, $requestHeaders);

        $headers = array();
        foreach ($response->getHeaders() as $responseHeader) {
            $header = substr($responseHeader, 0, strpos($responseHeader, ' '));
            $value = substr($responseHeader, strpos($responseHeader, ' '));
            if ($header == 'HTTP/1.0') {
                continue;
            }

            $headers[rtrim($header, ':')] = $value;
        }
        $headers['Access-Control-Allow-Origin'] = '*';
        $headers['Expires'] = '-1';
        $headers['Cache-Control'] = 'private, max-age=0';

        //TODO: handle caching better

        // TODO: could add some more checking by adding an Access-Control-Request-Headers field:
        // https://developer.mozilla.org/en/docs/HTTP/Access_control_CORS#Access-Control-Request-Headers
        return new Response($response->getContent(), $response->getStatusCode(), $headers);
    }
}
