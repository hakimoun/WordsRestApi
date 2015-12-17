<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class WordsController extends Controller
{

    /**
     * Note: here the name is important
     * get => the action is restricted to GET HTTP method
     * Word => (without s) generate /words/SOMETHING
     * Action => standard things for symfony for a controller method which
     *           generate an output
     *
     * it generates so the route GET .../words/{id}
     */
    public function getWordAction($id)
    {
        return array('word' => 'meaning of word');
    }
}
