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
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
        $request = $this->get('request');
        
        $page = (int)$request->query->get('page');
        if($page == NULL){
             $page = 1;
        }
        //OLDALAK
        $maxResult = (int)$request->query->get('maxResult');
        if($maxResult == NULL){
             $maxResult = 10;
        }
        
        //RENDEZÉS
        $order = $request->query->get('order');
        $by = $request->query->get('by');
        if($order == NULL){
             $order = "id";
             $by= "asc";
        }
        if($by != "asc" && $by != "desc"){
            $by = "asc";die;
        }
        
        $users = $this->getDoctrine()->getRepository('FrontendUserBundle:User')->createQueryBuilder('u')
                ->select('u');
        
        $filterId = "";
        $filterEmail = "";
        $filterAdmin = "";
        $parameters = "";
        
        if ($request->getMethod() == 'GET') {
            $filterId = $request->query->get('filterId');
            $filterEmail = $request->query->get('filterEmail');
            $filterAdmin = $request->query->get('filterAdmin');
            
            $parameters .= "&filterId=";
            if($filterId!= ""){
                $users = $users
                    ->andWhere('u.id = :id')
                    ->setParameter('id', (int)$filterId); 
                $parameters .= $filterId;
            }
            $parameters .= "&filterEmail=";
            if($filterEmail!= ""){
                $users = $users
                    ->andWhere('u.email LIKE :email')
                    ->setParameter('email', "%".$filterEmail."%");
                $parameters .= $filterEmail;
            }
            $parameters .= "&filterAdmin=";
            if($filterAdmin!= ""){
                if($filterAdmin == "yes"){
                    $users = $users
                         ->andWhere('u.roles LIKE :roles')
                         ->setParameter('roles', '%ROLE_ADMIN%');
                }else{
                    $users = $users
                         ->andWhere('u.roles NOT LIKE :roles')
                         ->setParameter('roles', '%ROLE_ADMIN%');
                }  
                $parameters .= $filterAdmin;
            }
        }
        if($order == "id"){
            $users = $users
                ->orderBy('u.id',$by);
        }else if($order == "name"){
            $users = $users
                ->orderBy('u.email',$by);
        }else if($order == "orderDate"){
            $users = $users
                ->orderBy('u.lastLogin',$by);
        }else{
            $users = $users
                ->orderBy('u.id',$by);
        }
        $parameters .= "&maxResult=".$maxResult;
        $pageCount = ceil(count($users->getQuery()->getResult()) / $maxResult);
        
        if($pageCount == 0)
            $pageCount = 1;
        
        $users = $users
                ->setFirstResult($page*$maxResult - $maxResult)
                ->setMaxResults($maxResult)
                ->getQuery()->getResult();
        
        return $this->render('BackendAdminBundle:User:list.html.twig', array(
            'users' => $users,
            'filterId'=> $filterId,
            'filterEmail'=> $filterEmail,
            'filterAdmin'=> $filterAdmin,
            'actualPage' => $page,
            'pageCount' => $pageCount,
            'parameters' => $parameters,
            'maxResult' => $maxResult,
            'order' => $order,
            'by' => $by
        ));
    }    
   
    public function newAction()
    {   
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
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
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
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
