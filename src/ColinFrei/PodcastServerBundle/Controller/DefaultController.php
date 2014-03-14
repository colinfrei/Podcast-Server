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
        $url = $request->query->get('url');

        /** @var Browser $buzz */
        $buzz = $this->get('buzz');
        $response = $buzz->get($url);

        // TODO: could add some more checking by adding an Access-Control-Request-Headers field:
        // https://developer.mozilla.org/en/docs/HTTP/Access_control_CORS#Access-Control-Request-Headers
        return new Response($response->getContent(), 200, array('Access-Control-Allow-Origin' => '*'));
    }
}
