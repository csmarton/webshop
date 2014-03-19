<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\UserBundle\Form\UserType;
use Frontend\UserBundle\Entity\User;
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
        
        if($userId == null){
            $user = new User();        
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
            $request = $this->get('request');
            $productId = $request->request->get('productId');
            
            $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneById($productId);
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($product);
            $em->flush();
    
            return new JsonResponse(array('success' => true,'productName'=>$product->getName()));
         }else{
             return new JsonResponse(array('success' => false));
         }   
    }
    
}
