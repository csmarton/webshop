<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProductBundle\Entity\Product;
use Frontend\ProductBundle\Entity\ProductQuestions;
use Frontend\ProductBundle\Form\ProductQuestionsType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Backend\AdminBundle\Entity\Log;

class QuestionController extends Controller
{
    /*
     * Kérdések listázása
     */
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
            $by = "asc";
        }
        
        $productQuestions = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductQuestions')->createQueryBuilder('p')
                ->select('p');
        $filterId = "";
        $filterProductId = "";
        $filterStatus = "";
        $parameters = "";
        //Szűrés
        if ($request->getMethod() == 'GET') {
            $filterId = $request->query->get('filterId');
            $filterProductId = $request->query->get('filterProductId');
            $filterStatus = $request->query->get('filterStatus');
            $parameters .= "&filterId=";
            if($filterId!= ""){ //Azonosító alapján
                $productQuestions = $productQuestions
                    ->andWhere('p.id = :id')
                    ->setParameter('id', (int)$filterId); 
                $parameters .= $filterId;
            }
            $parameters .= "&filterProductId=";
            if($filterProductId!= ""){ //Név alapján
                $productQuestions = $productQuestions
                    ->andWhere('p.productId = :productId')
                    ->setParameter('productId', $filterProductId);
                $parameters .= $filterProductId;
            }
            $parameters .= "&filterStatus=";
            if($filterStatus!= ""){ //Név alapján
                $productQuestions = $productQuestions
                    ->andWhere('p.status = :status')
                    ->setParameter('status', (int)$filterStatus);
                $parameters .= $filterStatus;
            }
            
        }
        
        //Rendezés
        if($order == "id"){
            $productQuestions = $productQuestions
                ->orderBy('p.id',$by);
        }else if($order == "name"){
            $productQuestions = $productQuestions
                ->orderBy('p.name',$by);        
        }else{
            $productQuestions = $productQuestions
                ->orderBy('p.id',$by);
        }
        $parameters .= "&maxResult=".$maxResult;
        $pageCount = ceil(count($productQuestions->getQuery()->getResult()) / $maxResult);
        
        if($pageCount == 0)
            $pageCount = 1;
        
        $productQuestions = $productQuestions
                ->setFirstResult($page*$maxResult - $maxResult)
                ->setMaxResults($maxResult)
                ->getQuery()->getResult();
        
        //$form = $this->createForm(new ProductType());
        return $this->render('BackendAdminBundle:Question:questionList.html.twig', array(
            //'form' => $form->createView(),
            'productQuestions' => $productQuestions,
            'filterId'=> $filterId,
            'filterProductId'=> $filterProductId,   
            'filterStatus'=> $filterStatus,
            'actualPage' => $page,
            'pageCount' => $pageCount,
            'parameters' => $parameters,
            'maxResult' => $maxResult,
            'order' => $order,
            'by' => $by
        ));
    }
    
    /*
     * Kérdések szerkesztése
     */
    public function editAction()
    {   
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
        
        $user = $this->get('security.context')->getToken()->getUser();
        $userId = $user->getId();
        
        $request = $this->get('request');
        $productQuestionId = $request->query->get('productQuestionId'); 
        $productQuestion = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductQuestions')->findOneById($productQuestionId);           
        
        $form = $this->createForm(new ProductQuestionsType(),$productQuestion);
        if ($request->getMethod() == 'POST') {
            
            $form->bind($request);
            if ($form->isValid()) { 
                if($productQuestion->getAnswer() != NULL ){
                    $answerTime = new \DateTime('now');
                    $productQuestion->setAnswerTime($answerTime);                    
                    
                    $mailer = $this->get('mailer');
                    $message = \Swift_Message::newInstance()
                        ->setSubject('Termékkel kapcsolatos kérdésre válasz')
                        ->setFrom('noreply@marcitech.hu')
                        ->setTo($productQuestion->getEmail())
                        ->setBody(
                            $this->renderView(
                                'BackendAdminBundle:Question:questionAnswer.html.twig',
                                array('productQuestion' => $productQuestion)
                            ),
                            "text/html"
                        );
                    $mailer->send($message); //Levél küldése
                }
                    
                    $em = $this->getDoctrine()->getEntityManager(); 
                    $em->persist($productQuestion);
                    $em->flush();

               }
              
                
               return $this->redirect($this->generateUrl('backend_admin_product_question_edit').'?productQuestionId='.$productQuestionId);
        }
        
        
                 

        
        return $this->render('BackendAdminBundle:Question:questionEdit.html.twig', array(
            'form' => $form->createView(),
            'productQuestionId'=>$productQuestionId,
            'productQuestion' => $productQuestion       
        ));
    }
    
}
