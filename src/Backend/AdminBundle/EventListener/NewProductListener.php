<?php 
namespace Backend\AdminBundle\EventListener;

use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event;
use Doctrine\ORM\EntityManager;

use Frontend\ProductBundle\Entity\Product;
use Backend\AdminBundle\Entity\Log;
use Frontend\UserBundle\Entity\User;
class NewProductListener implements EventSubscriber{
    private $container;

    function __construct($container) {
        $this->container = $container;
    }

    public function getSubscribedEvents(){
        /**
         * @todo Check if this is running in the console or what...
         */
        if (isset($_SERVER['HTTP_HOST'])) {
            return array('preUpdate', 'postPersist','postFlush');
        }

        return array();
    }
	
    private $log;
    
    
    public function preUpdate(Event\LifecycleEventArgs $eventArgs){  //UPDATE-HEZ
        $entityManager = $eventArgs->getEntityManager();
        $uow = $entityManager->getUnitOfWork();
        $entity = $eventArgs->getEntity();
        $doctrine = $this->container->get('doctrine');		
        $user = $this->container->get('security.context')->getToken()->getUser();
        $userId = $user->getId();
        if (is_object($user) && $entity instanceof Product) {
            $changeset = $uow->getEntityChangeSet($entity);
            if($changeset){
                $changedData = "";
                foreach($changeset as $k=>$cs){
                    if($k=='name'){
                        
                        $changedData .= "Név: " . $eventArgs->getOldValue($k) . " -> ". $eventArgs->getNewValue($k);
                    }	
                    if($k=='slug'){

                    }
                    if($k=='description'){

                    }
                    if($k=='salesPrice'){

                    }
                    if($k=='category'){

                    }
                    if($k=='isActive'){

                    }
                    if($k=='inStock'){

                    }
                }
                $now = new \DateTime('now');
                $this->log = new Log();
                $this->log->setAction("Frissítés");
                $this->log->setObjectClass("Frontend\ProductBundle\Entity\Product");
                $this->log->setObjectId($entity->getId());
                $this->log->setTime($now);
                $this->log->setUserId($userId);
                $this->log->setData($changedData);
            }
        }
    }
    public function postFlush(Event\PostFlushEventArgs $event)
    {
        
            $em = $event->getEntityManager();
            $uow = $em->getUnitOfWork();

            
            //$uow->persist($this->log);
            //$em->flush();
            //$classMetadata = $em->getClassMetadata(get_class($this->log));
            //$uow->computeChangeSet($classMetadata, $this->log);
        //$md = $em->getClassMetadata('Frontend\ProductBundle\Entity\Product');
       // $uow->scheduleForInsert($this->log);
    }
    
    public function postPersist(Event\LifecycleEventArgs $args) //új bejegyzés az adatbázisba
    {
        
    }
}
?>