<?php

namespace Frontend\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Frontend\ProfileBundle\Entity\Profile;
use Frontend\ProfileBundle\Form\ProfileType;
use Frontend\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
class DefaultController extends Controller
{
    public function registrationAction(Request $request){
            
		  /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');
        $user = $userManager->createUser();
        $user->setEnabled(true);
        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            
            return $event->getResponse();
        }
         $form = $formFactory->createForm();
        $form->setData($user);
        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->container->get('router')->generate('frontend_product_homepage');
                    $response = new RedirectResponse($url);
                }
                
                $newProfile = new Profile();
                $newProfile->setUser($user);
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($newProfile);
                $em->flush();
                
                
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }
        }
		//return $this->render('FrontendProductBundle:Default:registration.html.twig');
                return $this->container->get('templating')->renderResponse('FrontendUserBundle:Default:registration.html.twig', array(
                    'form' => $form->createView(),
                ));
	}
        
        public function loginAction(){
            
            return $this->render('FrontendUserBundle:Default:login.html.twig');
        }
        
        /*
         * Bejelentkezés ellenőrzése
         */
        public function checkLoginAction() {
           $request = $this->get('request');
            if('POST' === $request->getMethod()){
                $em = $this->getDoctrine()->getEntityManager();		
                $email = $request->request->get('email');
                $password = $request->request->get('password');
                
                $user = $this->getDoctrine()->getRepository('FrontendUserBundle:User')
                    ->createQueryBuilder('u')
                    ->where('u.email = :email')
                    ->setParameter(':email', $email)
                    ->getQuery()
                    ->getOneOrNullResult();                        

                if (count($user) != 0){
                    $encoder_service = $this->get('security.encoder_factory');
                    $encoder = $encoder_service->getEncoder($user);
                    $encoded_pass = $encoder->encodePassword($password, $user->getSalt());
                    if($encoded_pass == $user->getPassword()){  
                            $token = new UsernamePasswordToken($user, null, "secured_area", $user->getRoles());
                            $this->get("security.context")->setToken($token); 
                            //now dispatch the login event
                            $request = $this->get("request");
                            $event = new InteractiveLoginEvent($request, $token);
                            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
    
                        return new JsonResponse(array('success' => true, 'exists' => true));
                    }    
                }
                return new JsonResponse(array('success' => true,'exists' => false));
                
            }
            return new JsonResponse(array ('success'=>false));
        }
        
        /*
         * Email cím ellenőrzése, hogy nincs -e már ilyen címmel regisztrált vásárló
         */
        public function emailCheckAction(){
            $request = $this->get('request');
            if('POST' === $request->getMethod()){
                $email = $request->request->get('email');
                $user = $this->getDoctrine()->getRepository('FrontendUserBundle:User')->findByEmail($email);
                
                if(count($user) == 0){
                    return new JsonResponse(array ('success'=>true,'userExists' => false));
                }else{
                    return new JsonResponse(array ('success'=>true,'userExists' => true));
                }
            }
            return new JsonResponse(array ('success'=>false));  
        }
        
        public function passwordResetAction(){
            $request = $this->get('request');
            if('POST' === $request->getMethod()){
                $email = $request->request->get('email');
                $user = $this->getDoctrine()->getRepository('FrontendUserBundle:User')->findOneByEmail($email);
                
                if($user == null){
                    return new JsonResponse(array ('success'=>true,'userExists' => false));
                }else{
                    $tokenGenerator = $this->container->get('fos_user.util.token_generator');
                    $confirmationToken = $tokenGenerator->generateToken(); 
                    $user->setConfirmationToken($confirmationToken);
                    $now = new \DateTime("now"); 
                    $user->setPasswordRequestedAt($now);
                    $em = $this->getDoctrine()->getEntityManager(); 
                    $em->persist($user);
                    $em->flush();
                    
                    $link = $this->generateUrl('frontend_password_confirmation', array('confirmationToken' => $confirmationToken ));
                    $confirmationMessage = \Swift_Message::newInstance()
                        ->setSubject('Új jelszó generálás')
                        ->setFrom('noreply@marcitech.hu')
                        ->setTo($user->getEmail())
                        ->setBody(
                            $this->renderView(
                                'FrontendUserBundle:Reset:confirmationPasswordReset.html.twig',
                                array('link' => $link, 'user' => $user)
                            ),
                            "text/html"
                        )
                    ;
                    $this->get('mailer')->send($confirmationMessage);
                    return new JsonResponse(array ('success'=>true,'userExists' => true, 'sendEmail' => true));
                }
            }
            return new JsonResponse(array ('success'=>false));  
        }
        /*
         * Email cím megváltoztatása
         */
        public function changeEmailAction(){
		$request = Request::createFromGlobals();
		if('POST' === $request->getMethod()){
			$em = $this->getDoctrine()->getEntityManager();		
			
			$request = $this->get('request');		
			$email = $request->request->get('email');
			$user    = $this->get('security.context')->getToken()->getUser();
			$user->setEmail($email);
			$userManager = $this->container->get('fos_user.user_manager');
			$userManager->updateUser($user);
			
			return new JsonResponse(array ('success'=>true));
		 }
		 else{
			return new JsonResponse(array ('success'=>false));
		 }
	}
        
        public function passwordConfirmationAction(){
            
        }
        

        
}
