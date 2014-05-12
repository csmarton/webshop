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
    /*
     * Regisztráció, új user és profil felvétele
     */
    public function registrationAction(){
        $request = $this->get('request');
        if('POST' === $request->getMethod()){
            $username = $request->request->get('username');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $user = $this->getDoctrine()->getRepository('FrontendUserBundle:User')->findOneByEmail($email);
            if($user != null){ //Létezik ilyen email címmel regisztrált felhasználó
                return new JsonResponse(array ('success'=>true,'userExists' => true));
            }else{
                /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
                $formFactory = $this->container->get('fos_user.registration.form.factory');
                /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
                $userManager = $this->container->get('fos_user.user_manager');
                /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
                $dispatcher = $this->container->get('event_dispatcher');

                $user = $userManager->createUser();
                $user->setEnabled(true);
                $user->setUsername($email);
                $user->setPlainPassword($password);
                $user->setEmail($email);
                $userManager->updateUser($user);

                $newProfile = new Profile();
                $newProfile->setUser($user);

                $em = $this->getDoctrine()->getManager();
                $em->persist($newProfile);
                $em->flush();
                
                //Beléptetjük a felhasználót a munkafolyamat segítségével
                $token = new UsernamePasswordToken($user, null, "secured_area", $user->getRoles());
                $this->get("security.context")->setToken($token); 
                $request = $this->get("request");
                $event = new InteractiveLoginEvent($request, $token); 
                $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
                
                return new JsonResponse(array ('success'=>true,'userExists' => false));
            }     
        }
            return new JsonResponse(array('success' => false));  
    }
    
    
    public function loginAction(){            
        return $this->render('FrontendUserBundle:Default:loginModal.html.twig');
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
    
    /*
     * Új jelszó kérése
     */ 
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
                $context = $this->get('router')->getContext();
                $mailer = $this->get('mailer');
                $link = "http://".$context->getHost(). "".$this->generateUrl('frontend_password_confirmation', array('confirmationToken' => $confirmationToken ));
                //Megerősítési email küldése
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
                $mailer->send($confirmationMessage);
                /*$spool = $mailer->getTransport()->getSpool();
                $transport = $container->get('swiftmailer.transport.real');
                $spool->flushQueue($transport);*/

                $html = "Új jelszó igényléséhez kattints a ". $user->getEmail(). " email címedre küldött linkre!";

                return new JsonResponse(array ('success'=>true,'userExists' => true, 'sendEmail' => true, 'html' => $html));
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
        
    /*
     * Jelszó megerősítési oldal
     */
    public function passwordConfirmationAction($confirmationToken){
        $user = $this->getDoctrine()->getRepository('FrontendUserBundle:User')->findOneByConfirmationToken($confirmationToken);
        if($user != null){ //Létezik a generált token
            $exists = true;
        }else{
            $exists = false;
        }  
        return $this->render('FrontendUserBundle:Reset:resetWithConfirmationToken.html.twig', array('exists' => $exists, 'confirmationToken' => $confirmationToken));

    }
      
    /*
     * Új jelszó beállítása
     */
    public function passwordResetWithConfirmaionTokenAction(){
        $request = $this->get('request');
            if('POST' === $request->getMethod()){
                    $em = $this->getDoctrine()->getEntityManager();		

                    $request = $this->get('request');		
                    $confirmationToken = $request->request->get('confirmationToken');
                    $password = $request->request->get('password');
                    $user = $this->getDoctrine()->getRepository('FrontendUserBundle:User')->findOneByConfirmationToken($confirmationToken);

                    if($user != null){ //Létezik a generált token
                        $user->setPlainPassword($password);
                        $user->setConfirmationToken(NULL);
                        $user->setPasswordRequestedAt(NULL);
                        $userManager = $this->container->get('fos_user.user_manager');
                        $userManager->updateUser($user);
                        return new JsonResponse(array ('success'=>true));
                    } 
                    return new JsonResponse(array ('success'=>false));

             }
             else{
                    return new JsonResponse(array ('success'=>false));
             }
    }
        

        
}
