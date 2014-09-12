<?php
namespace WoohooLabs\ApiFramework\Request;

use WoohooLabs\ApiFramework\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use WoohooLabs\ApiFramework\Config;

class FoundationRequest implements RequestInterface
{
    /**
     * @var \WoohooLabs\ApiFramework\Serializer\SerializerInterface
     */
    private $serializer;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var array
     */
    private $pathParameters;

    /**
     * @var array
     */
    private $requestParameters;

    public function __construct(Config $config, SerializerInterface $serializer)
    {
        $this->request= Request::createFromGlobals();
        if ($config->isHttpMethodParameterOverrideSupported()) {
            $this->request->enableHttpMethodParameterOverride();
        }
        $this->serializer= $serializer;
    }

    /**
     * @return string
     * @example http
     */
    public function getScheme()
    {
        return $this->request->getScheme();
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->request->getHost();
    }

    /**
     * @return string
     * @example http://example.com
     */
    public function getSchemeAndHost()
    {
        return $this->request->getSchemeAndHttpHost();
    }

    /**
     * @return string
     */
    public function getPort()
    {
        return $this->request->getPort();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->request->getUri();
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->request->getMethod();
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->request->getRequestUri();
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->request->getRequestUri();
    }

    /**
     * @return array
     */
    public function getPathParameters()
    {
        return $this->pathParameters;
    }

    /**
     * @param array $pathParameters
     */
    public function setPathParameters(array $pathParameters)
    {
        $this->pathParameters = $pathParameters;
    }

    /**
     * @return string
     */
    public function getQueryString()
    {
        return $this->request->getQueryString();
    }

    /**
     * @return array
     */
    public function getQueryStringAsArray()
    {
        $result= [];
        parse_str($this->request->getQueryString(), $result);

        return $result;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->request->getContent();
    }

    /**
     * @return array
     */
    public function getBodyAsArray()
    {
        return $this->serializer->deserialize($this->getBody(), $this->getMediaType());
    }

    /**
     * @param string $type
     * @return array
     */
    public function getBodyAsObject($type)
    {
        return $this->serializer->deserialize($this->getBody(), $this->getMediaType(), $type);
    }

    /**
     * @return string|null
     */
    public function getMediaType()
    {
        return $this->request->getContentType();
    }

    /**
     * @return array
     */
    public function getAcceptableLanguages()
    {
        return $this->request->getLanguages();
    }

    /**
     * @return array
     */
    public function getAcceptableCharsets()
    {
        return $this->request->getCharsets();
    }

    /**
     * @return array
     */
    public function getAcceptableEncodings()
    {
        return $this->request->getEncodings();
    }

    /**
     * @return array
     */
    public function getAcceptableMediaTypes()
    {
        return $this->request->getAcceptableContentTypes();
    }

    /**
     * @return boolean
     */
    public function isXmlHttpRequest()
    {
        return $this->request->isXmlHttpRequest();
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getHeader($name)
    {
        return $this->request->headers->get($name, null);
    }

    /**
     * @return array
     */
    public function getRequestParametersAsArray()
    {
        if ($this->requestParameters == null) {
            if ($this->getMethod() == HttpMethods::GET || HttpMethods::HEAD || HttpMethods::DELETE) {
                $this->requestParameters = $this->getQueryStringAsArray();
            } else {
                $this->requestParameters = $this->getBodyAsArray();
            }
        }

        return $this->requestParameters;
    }
}
