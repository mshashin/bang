<?php
// src/Controller/DefaultController.php
namespace App\Controller;


use App\Entity\Element;
use App\Entity\Rule;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AjaxController extends InitializableController
{

    /**
     * @Route("/searchajax", methods={"POST","HEAD"}, name="searchajax", priority="2")
     */
    public function searchajax()
    {
        if (isset($_POST['search'])) {
            $search=$_POST['search'];
        }
        else {
            $search=false;
        }

        if ($search) {
            $query=$this->getRepository(Element::class)->createQueryBuilder('e')
                ->select ('e.id as id, e.caption as caption, e.alias as alias, t.caption as tcaption, t.alias as talias')
                ->leftJoin('e.typeElement','t')
                ->where('LOWER(e.caption) like LOWER(:search)')
                ->setParameter('search', '%'.trim($search) . '%');

            $elements = $query
                ->orderBy('e.caption')
                ->setMaxResults(25)
                ->getQuery()->getResult();
            if ($elements) {
                return $this->render('searchajax.html.twig', array('elements'=>$elements));
            }
            else {
                return new Response('');
            }

        }
        else return new Response('');
    }

    /**
     * @Route("/getruleajax", methods={"POST","HEAD"}, name="getruleajax", priority="2")
     */
    public function getruleajax()
    {
        $element_id1=null;
        $element_id2=null;

        if (isset($_POST['element_id1'])) {
            $element_id1=$_POST['element_id1'];
        }
        if (isset($_POST['element_id2'])) {
            $element_id2=$_POST['element_id2'];
        }
        if ($element_id1 and $element_id2) {
            $element1=$this->getRepository(Element::class)->findOneBy(array('id'=>$element_id1));
            $element2=$this->getRepository(Element::class)->findOneBy(array('id'=>$element_id2));
            $rules=$this->getRepository(Rule::class)->createQueryBuilder('r')
                ->leftJoin('r.elements','e1')
                ->leftJoin('r.elements','e2')
                ->where('(e1.id = :element_id1 OR e2.id = :element_id1) ')
                ->andWhere('(e2.id = :element_id2 OR e2.id = :element_id2) ')
                ->setParameters(array('element_id1'=>$element_id1,'element_id2'=>$element_id2))
                ->getQuery()->getResult();
            $rules_arr=array();
            /** @var Rule $rule */
            foreach ($rules as $rule) {
                $also_arr=array();
                /** @var Element $element */
                foreach ($rule->getElements() as $element){
                    if (!($element->getId()==$element_id1 or $element->getId()==$element_id2)){
                        array_push($also_arr,$element);
                    }
                }
                array_push($rules_arr, array('rule'=>$rule,'also'=>$also_arr));
            }

            return $this->render('answerajax.html.twig', array('rules_arr'=>$rules_arr, 'element1'=>$element1, 'element2'=>$element2));

        }
        else {
            return new Response('<p>Чего-то в запросе не хватает</p>');
        }




        if (isset($_POST['search'])) {
            $search=$_POST['search'];
        }
        else {
            $search=false;
        }

        if ($search) {
            $query=$this->getRepository(Element::class)->createQueryBuilder('e')
                ->select ('e.id as id, e.caption as caption, e.alias as alias, t.caption as tcaption, t.alias as talias')
                ->leftJoin('e.typeElement','t')
                ->where('LOWER(e.caption) like LOWER(:search)')
                ->setParameter('search', '%'.trim($search) . '%');

            $elements = $query
                ->orderBy('e.caption')
                ->setMaxResults(25)
                ->getQuery()->getResult();
            if ($elements) {
                return $this->render('searchajax.html.twig', array('elements'=>$elements));
            }
            else {
                return new Response('');
            }

        }
        else return new Response('');
    }
}