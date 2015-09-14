<?php

namespace WebLinks\Controller;


use Silex\Application;
use WebLinks\Form\Type\LinkType;
use Symfony\Component\HttpFoundation\Request;
use WebLinks\Domain\Link;
use WebLinks\Domain\User;
use WebLinks\Form\Type\UserType;

/**
 * Description of AdminController
 *
 * @author demoniakillah
 */
class AdminController{
    
    /**
     * Admin home page controller
     * 
     * @param Application $app
     * @return the admin home page view
     */
    public function indexAction(Application $app, Request $request){
        $links = $app['dao.link']->findAll();
        $users = $app['dao.user']->findAll();
        
        $link = new Link();
        $linkForm = $app['form.factory']->create(new LinkType(), $link);
        $linkForm->handleRequest($request);
        if ($linkForm->isSubmitted() && $linkForm->isValid()) {
            $token = $app['security']->getToken()->getUser();
            $userId = $token->getId();
            $state = $app['dao.link']->save($link,$userId);
            $app['session']->getFlashBag()->add($state['type'],$state['message']);
            return $app->redirect('/admin');
        }
        
        $user = new User();
        $userForm = $app['form.factory']->create(new UserType(),$user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            // generate a random salt value
            $salt = substr(md5(time()), 0, 23);
            $user->setSalt($salt);
            $plainPassword = $user->getPassword();
            // find the default encoder
            $encoder = $app['security.encoder.digest'];
            // compute the encoded password
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password); 
            $state=$app['dao.user']->save($user);
            $$app['session']->getFlashBag()->add($state['type'],$state['message']);
        }
        
        return $app['twig']->render('admin.html.twig', array(
            'links' => $links, 
            'users'=>$users,
            'linkForm' => $linkForm->createView(),
            'userForm'=>$userForm->createView()
            ));
    }
    
    public function editUserAction(Request $request, Application $app, $id){
        $user = $app['dao.user']->find($id);
        $userForm = $app['form.factory']->create(new UserType(), $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $plainPassword = $user->getPassword();
            // find the encoder for the user
            $encoder = $app['security.encoder_factory']->getEncoder($user);
            // compute the encoded password
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password); 
            $app['dao.user']->save($user);
            $app['session']->getFlashBag()->add('message', 'The user was succesfully updated.');
            return $app->redirect('/admin');
        }
        return $app['twig']->render('user_form.html.twig', array(
            'userForm' => $userForm->createView()));
    }
    
    public function deleteUserAction(Application $app, $id){
        // Delete all associated link
        $app['dao.link']->deleteAllByUser($id);
        // Delete the user
        $app['dao.user']->delete($id);
        $app['session']->getFlashBag()->add('message', 'The user was succesfully removed.');
        return $app->redirect('/admin');
    }

    public function editLinkAction(Application $app, Request $request, $id){
        if($id=='new'){
            $link = new Link();
            $title = 'New Link';
        }
        else{
            $link = $app['dao.link']->find($id);
            $title = 'Edit Link';
        }
        $linkForm = $app['form.factory']->create(new LinkType(), $link);
        $linkForm->handleRequest($request);
        if ($linkForm->isSubmitted() && $linkForm->isValid()) {
            $token = $app['security']->getToken()->getUser();
            $userId = $token->getId();
            $state = $app['dao.link']->save($link,$userId);
            $app['session']->getFlashBag()->add($state['type'],$state['message']);
            return $app->redirect('/admin');
        }
        return $app['twig']->render('link_form.html.twig', array(
            'title' => $title,
            'linkForm' => $linkForm->createView()));
    }
    
    public function deleteLinkAction(Application $app, $id){
        $app['dao.link']->delete($id);
        $app['session']->getFlashBag()->add('message','Link deleted succefully');
        return $app->redirect('/admin');
    }
    
   
}
