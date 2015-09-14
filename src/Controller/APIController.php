<?php

namespace WebLinks\Controller;

use Silex\Application;

/**
 * Description of APIController
 *
 * @author demoniakillah
 */
Class APIController{
    
    public function linksAction(Application $app){
        $links=$app['dao.link']->findAll();
        // Convert an array of objects ($links) into an array of associative arrays ($responseData)
        foreach ($links as $link){
            $responseData[]=array(
                $link->getId(),
                $link->getTitle(),
                $link->getUrl()
            );
        }
        // Create and return a JSON response
        return $app->json($responseData);
    }
    
    public function linkAction(Application $app, $id){
        $link = $app['dao.link']->find($id);
        // Convert object $link into an assosiative array
        $responseData = array(
            $link->getId(),
                $link->getTitle(),
                $link->getUrl()
        );
        // create and return JSON response
        return $app->json($responseData);
    }
}