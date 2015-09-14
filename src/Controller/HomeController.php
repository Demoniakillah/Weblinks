<?php

namespace WebLinks\Controller;


use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WebLinks\Domain\Link;
use WebLinks\Form\Type\LinkType;
/**
 * Description of HomeController
 *
 * @author demoniakillah
 */
class HomeController{
    
    /**
     * Home page controller
     * 
     * @param Application $app
     * @return the home page view
     */
    public function indexAction(Application $app, Request $request){
        $links = $app['dao.link']->findAll();
        $link = new Link();
        $linkForm = $app['form.factory']->create(new LinkType(), $link);
        $linkForm->handleRequest($request);
        if ($linkForm->isSubmitted() && $linkForm->isValid()) {
            $token = $app['security']->getToken()->getUser();
            $userId = $token->getId();
            $state = $app['dao.link']->save($link,$userId);
            $app['session']->getFlashBag()->add($state['type'],$state['message']);
        }
        return $app['twig']->render('index.html.twig', array(
            'links' => $links,
            'linkForm' => $linkForm->createView()));
    }
    
    /**
     * Login controller
     * 
     * @param Application $app
     * @param Request $request
     * @return login form
     */
    public function loginAction(Application $app, Request $request){
        return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
    }
    
    
    public function linkAction(Application $app, Request $request){
        $link = new Link();
        $linkForm = $app['form.factory']->create(new LinkType(), $link);
        $linkForm->handleRequest($request);
        if ($linkForm->isSubmitted() && $linkForm->isValid()) {
            $token = $app['security']->getToken()->getUser();
            $userId = $token->getId();
            $state = $app['dao.link']->save($link,$userId);
            $app['session']->getFlashBag()->add($state['type'],$state['message']);
            return $app->redirect('/link');
        }
        return $app['twig']->render('link_form.html.twig', array(
            'title' => 'New link',
            'linkForm' => $linkForm->createView()));
    }
}
