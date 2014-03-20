<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\UserBundle\Form\UserType;
use Frontend\ProfileBundle\Form\ProfileType;
use Frontend\UserBundle\Entity\User;
use Frontend\ProfileBundle\Entity\Profile;
use Symfony\Component\HttpFoundation\JsonResponse;
class UserController extends Controller
{
    public function listAction()
    {   
        $users = $this->getDoctrine()->getRepository('FrontendUserBundle:User')->findAll();
        //var_dump($users[0]->getRoles());die;
        //$form = $this->createForm(new ProductType());
        return $this->render('BackendAdminBundle:User:list.html.twig', array(
            //'form' => $form->createView(),
            'users' => $users
        ));
    }    
   
    public function newAction()
    {   
        $request = $this->get('request');
        $userId = $request->query->get('userId');
        
        $myUser = $this->get('security.context')->getToken()->getUser();
        $profile = null;
        if($userId == null){
            $user = new User();   
            $user->setUserName('');
            $user->setPassword('');
            $profile = new Profile();            
        }else{
            $user = $this->getDoctrine()->getRepository('FrontendUserBundle:User')->findOneById($userId);
        }

        $form = $this->createForm(new UserType(),$user);
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) { 
               if (isset($form['isAdmin'])) {
                    $isAdmin = $form['isAdmin']->getData();
               }
               if (isset($form['password'])) {
                    $password = $form['password']->getData();
               }   
               
               if($isAdmin){
                   $user->addRole("ROLE_ADMIN");
               }else{
                   $user->removeRole("ROLE_ADMIN");
               }
               
               $user->setPlainPassword($password);
               
               $em = $this->getDoctrine()->getManager();
               $em->persist($user);               
               $em->flush();        
               
               if($profile != null){
                   $profile->setUser($user);
                   $em->persist($profile);               
                   $em->flush();  
               }
               
               return $this->redirect($this->generateUrl('backend_admin_user'));
            }
        }    
                

        
        return $this->render('BackendAdminBundle:User:new.html.twig', array(
            'form' => $form->createView(),
            'userId'=>$userId,
            'user' => $user,
            'myUser' => $myUser
        ));
    }
    
    public function removeAction(){
         $request = $this->get('request');
         if ($request->getMethod() == 'POST') {
            $userId = $request->request->get('userId');
            
            $user = $this->getDoctrine()->getRepository('FrontendUserBundle:User')->findOneById($userId);
            $profile = $this->getDoctrine()->getRepository('FrontendProfileBundle:Profile')->findOneByUser($user);
            
            $em = $this->getDoctrine()->getEntityManager();            
            $em->remove($user);
            $em->remove($profile);
            $em->flush();
    
            return new JsonResponse(array('success' => true,'userName'=>$user->getUserName()));
         }else{
             return new JsonResponse(array('success' => false));
         }   
    }
    
    public function profileEditAction($userId = null){
        $request = $this->get('request');
        
        if($userId == null){
            $userId = $request->query->get('userId');
        }
        $user = $this->getDoctrine()->getRepository('FrontendUserBundle:User')->findOneById($userId);
        $profile = $this->getDoctrine()->getRepository('FrontendProfileBundle:Profile')->findOneByUser($user);
        
        $form = $this->createForm(new ProfileType(), $profile);
        
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) { 
               $em = $this->getDoctrine()->getManager();
               $em->persist($profile);
               $em->flush();
               return $this->redirect($this->generateUrl('backend_admin_user_new').'?userId='.$userId);
            }
        } 
        return $this->render('BackendAdminBundle:User:profiles.html.twig', array(
            'form' => $form->createView(),
            'profile' => $profile,
            'userId' => $userId
        ));
    }
    
 
}
