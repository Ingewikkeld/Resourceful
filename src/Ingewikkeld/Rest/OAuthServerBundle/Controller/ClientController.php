<?php

namespace Ingewikkeld\Rest\OAuthServerBundle\Controller;

use Hal\Resource;
use Ingewikkeld\Rest\Resource\MapperInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class ClientController
{
    /** @var FormInterface $formFactory */
    protected $form;

    /** @var MapperInterface  */
    protected $mapper;

    /**
     * Initializes this controller with a translator and Resource DataMapper
     *
     * @param FormInterface   $form
     * @param MapperInterface $mapper
     */
    public function __construct(FormInterface $form, MapperInterface $mapper)
    {
        $this->form   = $form;
        $this->mapper = $mapper;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function browseAction(Request $request)
    {
        $response = $this->convertResourceToPlainText(
            $request->getRequestFormat('xml'),
            $this->mapper->getCollection()
        );

        return new Response($response);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function readAction(Request $request)
    {
        $response = $this->convertResourceToPlainText(
            $request->getRequestFormat('xml'),
            $this->mapper->getResource($request->get('id'))
        );

        return new Response($response);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(Request $request)
    {
        $formData = $this->validateAddAndEditRequestParameters($request);

        $resource = $this->mapper->getResource($request->get('id'));
        $resource->setData(
            array(
                'redirectUris' => $formData['redirectUris'],
                'grants'       => $formData['grants']
            )
        );

        $this->mapper->update($resource);

        // returns an empty 200 (OK)
        return new Response();
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function addAction(Request $request)
    {
        $resource = $this->mapper->create($this->validateAddAndEditRequestParameters($request));

        return new Response('', 201, array('Location' => $this->mapper->generateReadUrl($resource)));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function deleteAction(Request $request)
    {
        $this->mapper->delete($this->mapper->getResource($request->get('id')));

        return new Response('', 204);
    }

    /**
     *
     *
     * @param Request $request
     *
     * @throws BadRequestHttpException
     *
     * @return FormInterface
     */
    protected function validateAddAndEditRequestParameters(Request $request)
    {
        $data = $request->request->all();
        if (isset($data['id'])) {
            unset($data['id']);
        }

        // we explicitly do not load existing data; an edit is a PUT and should supply all valid data!
        $this->form->submit($data);
        if (!$this->form->isValid()) {
            throw new BadRequestHttpException($this->form->getErrorsAsString());
        }

        return $this->form->getData();
    }

    /**
     * Converts the give HAL Resource to a plain text representation that can be returned in the response.
     *
     * @param string   $format
     * @param Resource $resource
     *
     * @throws NotAcceptableHttpException
     *
     * @return string
     */
    protected function convertResourceToPlainText($format, Resource $resource)
    {
        switch ($format) {
            case 'xml':
                $response = $resource->getXML();

                return $response;
            case 'json':
                $response = (string)$resource;

                return $response;
            default:
                throw new NotAcceptableHttpException();
        }
    }
}
