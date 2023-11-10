<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use App\Entity\Element;
use App\Entity\Rule;
use App\Entity\TypeElement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends InitializableController
{
    /**
     * @Route("/", methods={"GET","HEAD"}, name="homepage")
     */
    public function index()
    {
        $elements=$this->getRepository(Element::class)->createQueryBuilder('e')
        ->orderBy('e.caption')
        ->getQuery()->getResult();


        return $this->render('pages/homepage.html.twig',
            array('elements'=>$elements)
        );

    }

    /**
     * @Route("/byid/{id}", methods={"GET","HEAD"}, name="byid", priority="2")
     */
    public function byId($id):Response
    {
        /** @var Element $element */
        $element=$this->getRepository(Element::class)->findOneBy(array('id'=>$id));

        if (!$element) {
            throw $this->createNotFoundException(
                'Нет элемента, связанного с '.$id
            );
        }
        else {
            return $this->redirectToRoute('oneelement',array('typealias'=>$element->getType()->getAlias(),'elementalias'=>$element->getAlias()));
        }
    }

    /**
     * @Route("/{typealias}/{elementalias}", methods={"GET","HEAD"}, name="oneelement")
     */
    public function oneElement($typealias, $elementalias):Response
    {
        /** @var TypeElement $type */
        $type=$this->getRepository(TypeElement::class)->findOneBy(array('alias'=>$typealias));

        /** @var Element $element */
        $element=$this->getRepository(Element::class)->findOneBy(array('alias'=>$elementalias));

        if (!$type or !$element) {
            throw $this->createNotFoundException(
                'Нет слова, связанного с '.$typealias.' или с '.$elementalias
            );
        }
        else {
            $element->setViews($element->getViews()+1);
            $this->manager->persist($element);
            $this->manager->flush();

            $types=$this->getRepository(TypeElement::class)->createQueryBuilder('t')
                ->getQuery()->getResult();
            $content_arr=array();

            foreach ($types as $type){
               $rules=$this->getRepository(Rule::class)->createQueryBuilder('r')
                    ->leftJoin('r.elements', 'e1')
                    ->leftJoin('r.elements', 'e2')
                    ->leftJoin('e2.typeElement','t')
                    ->where('e1.id = :id')
                    ->andWhere('e2.id <> :id')
                    ->andWhere('t.id = :typeid')
                    ->setParameters(array('id'=>$element->getId(),'typeid'=>$type->getId()))
                    ->getQuery()->getResult();
                $rules_arr=array();
               /** @var Rule $rule */
                foreach ($rules as $rule) {
                    $also_arr=array();
                    unset($main);
                    $i=1;
                    foreach ($rule->getElements() as $elem) {
                       if ($elem->getId()!=$element->getId() and $i<2) {
                           $main=$elem;
                           $i++;
                       }
                       else {
                           if ($elem->getId()!=$element->getId()) {
                               array_push($also_arr,$element);
                           }
                       }
                    }

                   array_push($rules_arr,array('rule'=>$rule,'main'=>$main,'also'=>$also_arr));
               }

               array_push($content_arr,array('type'=>$type,'rules'=>$rules_arr));
            }

            $rules=$this->getRepository(Rule::class)->createQueryBuilder('r')
                ->select('t.caption as type, t.alias as talias, e2.caption as caption, e2.alias as elemalias, e2.id as elemid, e2.image as image, r.caption as rule, r.id as rid')
                ->leftJoin('r.elements', 'e1')
                ->leftJoin('r.elements', 'e2')
                ->leftJoin('e2.typeElement','t')
                ->where('e1.id = :id')
                ->andWhere('e2.id <> :id')
                ->setParameters(array('id'=>$element->getId()))
               // ->groupBy('type')
                ->orderBy('rid')
                ->addOrderBy('e2.caption')
                ->addOrderBy('r.id')
                ->getQuery()->getResult();

            $alsoelements=$this->getRepository(Element::class)->findAll();

            return  $this->render('pages/element.html.twig', array('element'=>$element, 'rules'=>$rules, 'alsoelements'=>$alsoelements,'content_arr'=>$content_arr));
        }
    }

    /**
     * @Route("/{typealias}", methods={"GET","HEAD"}, name="onetypeelement")
     */
    public function oneTypeElement($typealias):Response
    {


        /** @var TypeElement $type */
        $type=$this->getRepository(TypeElement::class)->findOneBy(array('alias'=>$typealias));


        if (!$type) {
            throw $this->createNotFoundException(
                'Нет слова, связанного с '.$typealias
            );
        }
        else {
            return  $this->render('pages/typeelement.html.twig', array('typeelement'=>$type, 'elements'=>$type->getElements()));
        }
    }


    /**
     * @Route("/search", methods={"GET","HEAD"}, name="search")
     */
    public function search()
    {
        if (isset($_GET['search'])) {
            $search=$_GET['search'];
        }
        else {
            $search=false;
            $words=array();
        }

        if ($search) {
            $searchtext=$search;
            $searchtext=trim($searchtext);
            //в строке поиска запятую с пробелом заменяем пробелом
            $searchtext=str_replace(', ', ' ',$searchtext);
            //одиночные запятые заменяем пробелом
            $searchtext=str_replace(',', ' ',$searchtext);
            //разбиваем по пробелам
            $searcharray=explode(' ',$searchtext);
            //по каждому слову запрашиаем список айдишников продуктов, которые подходят под этот запрос
            $allwords=array();

            foreach ($searcharray as $item){
                //исключаем предлоги
                if (strlen($item)>4) {
                    $tempwords=$this->getRepository(Word::class)->createQueryBuilder('w')
                        ->where('LOWER(w.caption) like LOWER(:search)')
                        ->andWhere('w.active = 1')
                        ->setParameter('search', '%'.trim($item).'%')
                        ->getQuery()->getResult();;
                    $allwords=array_merge($allwords,$tempwords);
                }
            }
            $arrayid=array();
            //Считаем количество повторений
            foreach ($allwords as $word) {
                if (isset($arrayid[$word->getId()])) {
                    $arrayid[$word->getId()]['count']=$arrayid[$word->getId()]['count']+1;
                }
                else {
                    $arrayid[$word->getId()] = array('word'=>$word, 'count'=>1);
                }
            }
            usort($arrayid, array($this, "cmp") );
            $count=count($arrayid);

            //пагинация
            if($this->request->get('pagenum')){$pagenum=$this->request->get('pagenum');}else{$pagenum=1;}
            //$count=$productscountquery->getQuery()->getSingleScalarResult();

            $pages = floor($count / 20) + ($count % 20 > 0 ? 1 : 0);
            if ($pages < 1) $pages = 1;
            if ($pagenum > $pages) $pagenum = $pages;


            $words=array();
            $first=($pagenum - 1)*20;
            $last=min($pagenum*20,$count);
            for ($i=$first;$i<$last;$i++) {
                array_push($words,$arrayid[$i]['word']);
            }
        }

        return $this->render('pages/search.html.twig',
            array('words'=>$words,'search'=>$search, 'count'=>$count, 'page'=>$pagenum, 'pages'=>$pages));
    }



}