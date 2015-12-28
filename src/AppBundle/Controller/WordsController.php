<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Word;
use AppBundle\Form\WordType;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WordsController extends FOSRestController
{

    /**
     * Note: here the name is important
     * get => the action is restricted to GET HTTP method
     * word => (without s) generate /words/SOMETHING
     * Action => standard things for symfony for a controller method which
     *           generate an output
     *
     * it generates so the route GET .../words/{id}
     *
     * @return Word
     */
    public function getWordAction(Word $word)
    {
        return $word;
    }

    /**
     * retrieve all words
     * TODO: add pagination
     *
     * @return words[]
     */
    public function getWordsAction()
    {
        $words = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Word')
            ->findAll();

        return $words;
    }

    /**
     *
     */
    public function postWordsAction(Request $request)
    {
        //TODO: there's a simpler method using FOSRestBundle body converter
        // that's the reason why we need to be able to create
        // an article without body or title, to use it as
        // a placeholder for the form
        $jsonWord = json_decode($request->getContent());
        $formWord = new Word($jsonWord->title, $jsonWord->body);

        //$word = new Word();

        $errors = $this->treatAndValidateRequest($formWord, $request);
        if (count($errors) > 0) {
            return new View(
                $errors,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        $this->persistAndFlush($formWord);
        // created => 201, we need View here because we're not
        // returning the default 200
        return new View($formWord, Response::HTTP_CREATED);
    }

    /**
     *
     */
    public function putWordsAction(Request $request)
    {
       //@todo should this methode return succes or fail ?
       // yes we replace totally the old article by a new one
       // except the id, because that's how PUT works
       // if you just want to "merge" you want to use PATCH, not PUT
       $jsonWord = json_decode($request->getContent());
       $formWord = new Word($jsonWord->title, $jsonWord->body);

       $errors = $this->treatAndValidateRequest($formWord, $request);
       if (count($errors) > 0) {
           return new View(
              $errors,
              Response::HTTP_UNPROCESSABLE_ENTITY
           );
       }
       $this->persistAndFlush($formWord);
       return "";
    }

    private function treatAndValidateRequest(Word $word, Request $request)
    {
        // createForm is provided by the parent class
        $form = $this->createForm(
            WordType::class,
            $word,
            array(
                'method' => $request->getMethod()
            )
        );
        // this method is the one that will use the value in the POST
        // to update $article
        $form->handleRequest($request);
        // we use it like that instead of the standard $form->isValid()
        // because the json generated
        // is much readable than the one by serializing $form->getErrors()
        $errors = $this->get('validator')->validate($word);
        return $errors;
    }

    private function persistAndFlush(Word $word)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($word);
        $manager->flush();
    }

}
