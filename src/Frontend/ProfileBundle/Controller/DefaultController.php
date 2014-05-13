<?php

namespace Frontend\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProfileBundle\Entity\Profile;
use Frontend\ProfileBundle\Form\ProfileType;
use Frontend\OrderBundle\Entity\Orders;
class DefaultController extends Controller
{
    /*
     * Profil szerkesztése
     */
     public function editAction(){
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') === false) { 
            return $this->redirect($this->generateUrl('frontend_product_homepage'));
        }
        $request = $this->get('request');
        $user = $this->get('security.context')->getToken()->getUser();
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY') )
            //return $this->redirect($this->generateUrl('frontend_product_homepage'));

        $profile = $user->getProfile(); //profil lekérdezése

        $form = $this->createForm(new ProfileType(),$profile); //profil űrlap

        if ($request->getMethod() == 'POST') {
           $form->bind($request);
           if ($form->isValid()) { 
               $em = $this->getDoctrine()->getManager();
               $em->persist($profile); //Változások mentése
               $em->flush();
               $html = "Sikeresen megváltoztattad az adataidat!";
                   return $this->render('FrontendProfileBundle:Default:edit.html.twig', array(
                       'form' => $form->createView(),
                       'succesChanges' => $html
                       ));
               }                
        }    

       return $this->render('FrontendProfileBundle:Default:edit.html.twig', array(
           'form' => $form->createView())
       );
    }
     
    /*
     * Rendeléseim listázása
     */
     public function myOrdersAction(){
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') === false) { 
            return $this->redirect($this->generateUrl('frontend_product_homepage'));
        }
         $user = $this->get('security.context')->getToken()->getUser();
         $profile = $user->getProfile();
         $myOrders =  $this->getDoctrine()->getRepository('FrontendOrderBundle:Orders')->createQueryBuilder('o')
                    ->select('o,oi,p,pi,pp,prop')
                    ->leftJoin('o.orderItems','oi')
                    ->leftJoin('oi.product', 'p')                    
                    ->leftJoin('p.productImages','pi')
                    ->leftJoin('p.productPropertys', 'pp')
                    ->leftJoin('pp.property', 'prop')
                    ->where('o.user = :user')
                    ->setParameter('user', $user)
                    ->getQuery()->getResult();
         return $this->render('FrontendProfileBundle:MyOrders:myOrders.html.twig', array(
             'myOrders'=>$myOrders
         ));
     }   
}
