<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Word;

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
     *
     * @return Word
     */
    public function getWordAction($id)
    {
        return new Word("title $id", "body $id");
    }
}
