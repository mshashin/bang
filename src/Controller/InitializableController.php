<?php

namespace App\Controller;

use App\Entity\Code;
use App\Entity\TypeElement;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;


abstract class InitializableController extends AbstractController
{
    /** @var AuthorizationChecker */
    protected $authChecker;
    /** @var array */
    protected $forms;
    /** @var EntityManager */
    protected $manager;
    /** @var array|EntityRepository[] */
    protected $repositories;
    /** @var Request */
    protected $request;
    /** @var Session */
    protected $session;
    /** @var User */
    protected $user;
    /** @var array */
    protected $view;
    /** @var array */
    protected $navigation;
    /** @var ManagerRegistry */
    protected $doctrine;
    /** @var array */
    protected $codes;
    /** @var ArrayCollection */
    protected $menuitems;

    /**
     * @param string $level
     * @param string $view
     * @param array $parameters
     */
    public function addNotice($level, $view, array $parameters = array())
    {
        $this->get('session')->getFlashBag()->add(
            'notice.' . $level,
            $this->renderView('AppBundle:Notice:' . $view, $parameters)
        );
    }


    /**
     * @param $entity
     * @return EntityRepository
     */
    public function getRepository($entity):EntityRepository
    {
        if (!array_key_exists($entity, $this->repositories))
            $this->repositories[$entity] = $this->manager->getRepository($entity);

        return $this->repositories[$entity];
    }

    public function initialize(Request $request)
    {
        $this->request = $request;
        $this->authChecker = $this->get('security.authorization_checker');
        $this->forms = array();
        $this->navigation = array();
        $this->manager = $this->getDoctrine()->getManager();
        $this->repositories = array();
        $this->session = $this->request->getSession();
        $this->user = $this->getUser();
        $this->view = array();
        $this->codes = $this->getCodes();
        $this->menuitems = $this->getMenuItems();
    }

    public function getMoonDay()
    {
        $year=date('Y');
        $day=date('d');
        $month=date('n');

        $moonday=(((((($year % 19)+1)*11-14) % 30 )+$day+$month) % 30);
        if ($moonday==30 or $moonday==0) {
            return 1;
        }
        else {
            return  $moonday;
        }

    }

    public function getPhaseMoon():int
    {
        $moonday=$this->getMoonDay();

        if ($moonday==1 or $moonday==30) {
            return 1;
        }
        elseif ($moonday<=15) {
            return 2;
        }
        elseif ($moonday==15) {
            return 3;
        }
        else {
            return 4;
        }

    }

    /**
     * @return array
     */
    public function getCodes():array
    {
        $allpageshead=$this->manager->getRepository(Code::class)->findOneBy(array('type'=>'allpages_head','status'=>1));
        $allpagesfoot=$this->manager->getRepository(Code::class)->findOneBy(array('type'=>'allpages_foot','status'=>1));
        $intexts=$this->manager->getRepository(Code::class)->findBy(array('type'=>'intext','status'=>1));
        $allpagesafterbody=$this->manager->getRepository(Code::class)->findOneBy(array('type'=>'afterbody','status'=>1));


        return array('allpageshead'=>$allpageshead,'intexts'=>$intexts,'allpagesfoot'=>$allpagesfoot,'allpagesafterbody'=>$allpagesafterbody);
    }

    /**
     * @return array
     */
    public function getMenuItems():array
    {
        $items=$this->getRepository(TypeElement::class)->findAll();
        return $items;
    }

    /**
     * Renders a view.
     */
    public function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $parameters['codes']=$this->getCodes();
        $parameters['menuitems']=$this->getMenuItems();
        return parent::render($view, $parameters, $response);
    }

    function cmp($a, $b)
    {
        if ($a['count'] == $b['count']) {
            return 0;
        }
        return ($a['count'] > $b['count']) ? -1 : 1;
    }

}
