<?php

namespace Xima\RestApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;

use JMS\Serializer\SerializerBuilder;
use Xima\CoreBundle\Model\InitializableControllerInterface;
use JMS\Serializer\SerializationContext;

class ApiController extends Controller implements InitializableControllerInterface 
{
    protected $version = '0.0.2';
    protected $defaultLimit = 50;
    protected $defaultLang = 'de';

    protected $result = array();
    protected $metadata = array();
    protected $params = array();
    
    protected $request;

    public function initialize(Request $request, SecurityContextInterface $security_context) {

        $this->request = $request;
        $this->params = $this -> request -> query -> all();

        //set default or queried limit
        $this->params['limit'] = $this -> defaultLimit;
        if ($this->request->get('limit') && $this->request->get('limit') < $this -> defaultLimit)
        {
            $this->params['limit'] = $this->request->get('limit');
        }
        
        //set default or queried language
        $this->params['lang'] = $this -> defaultLang;
        if ($this->request->get('lang')) 
        {
            switch ($this->request->get('lang')) {
                case 'cs' :
                    $this->params['lang'] = 'cz';
                    break;
                default :
                    $this->params['lang'] = $this->request->get('lang');
                    break;
            }
        }
        
        //set request locale
        switch ($this->params['lang']) {
            case 'en':
                $request->setLocale('en_US');
                break;
            default :
                $request->setLocale($this->params['lang']);
                break;
        }
    }

    protected function success(SerializationContext $context = null) {
        $this -> metadata['count'] = count($this -> result);
        $this -> metadata['parameters'] = $this -> request -> query -> all();
        
        if (isset($this -> params['debug']) && $this -> params['debug']) {
            $this -> metadata['appliedParameters'] = $this->params;
        } else {
            unset($this -> metadata['lastExecutedQuery']);
        }

        $serializer = SerializerBuilder::create()
                        ->setPropertyNamingStrategy(new \JMS\Serializer\Naming\IdenticalPropertyNamingStrategy())
                        ->build();

        //object gets serialized first to fullfill the serialization annotations, then decoded and again encoded by the JsonResponse
        $data = array(
            'status' => 'OK',
            'metadata' => $this -> metadata,
            'result' => json_decode($serializer -> serialize($this -> result, 'json', $context))
        );

        if (($this->request->get('indent') && $this->request->get('indent') == true) || (isset($this -> params['debug']) && $this -> params['debug'])) {

            $content = '<pre>' . print_r($data, true) . '</pre>';

            $response = new Response();
            $response -> setContent($content);
            $response -> headers -> set('Content-Type', 'text/html; charset=utf-8');
        }
        else {
            $response = new JsonResponse();
            $response -> setData($data);
        }

        return $response;

    }

    protected function error($msg = 'Es ist ein Fehler aufgetreten.') {
        $response = new JsonResponse();
        $response -> setData(array('status' => 'ERROR: ' . $msg));

        return $response;
    }

}
