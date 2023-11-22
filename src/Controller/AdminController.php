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

class AdminController extends InitializableController
{
    /**
     * @Route("/admin/addrule", methods={"GET","HEAD"}, name="addrule", priority="2")
     */
    public function addRule()
    {
        $element_id1=$this->request->get('element_id1');
        $elements2=$this->request->get('elements2');
        $description=$this->request->get('description');
        /** @var Element $element */
        $element=$this->getRepository(Element::class)->findOneBy(array('id'=>$element_id1));

        if ($elements2) {
            $rule= new Rule();
            $rule->setCaption($description);
            if ($element) {
                $rule->addElement($element);
            }
            foreach ($elements2 as $element_id2){
                /** @var Element $element2 */
                $element2=$this->getRepository(Element::class)->findOneBy(array('id'=>$element_id2));
                if ($element2) {
                    $rule->addElement($element2);
                }
            }
            $this->manager->persist($rule);
            $this->manager->flush();
        }
        else {
            $element->setDescription($element->getDescription().PHP_EOL.'* '.$description);
            $this->manager->persist($element);
            $this->manager->flush();
        }
        if (!$element) {
            if (count($elements2)>1){
                $gets=array('element_id1'=>$elements2[0],'element_id2'=>$elements2[1]);
            }
            else {
                $gets=array();
            }
            return $this->redirectToRoute('homepage',$gets);
        }
        else {
            return $this->redirectToRoute('oneelement',
                array('typealias'=>$element->getTypeElement()->getAlias(), 'elementalias'=>$element->getAlias()));
        }
    }

    /**
     * @Route("/admin/editrule", methods={"GET","HEAD"}, name="editrule", priority="2")
     */
    public function editRule()
    {
        $element_id1=$this->request->get('element_id');
        $description=$this->request->get('description');
        $rule_id=$this->request->get('rule_id');
        $elements2=$this->request->get('elements2');

        /** @var Element $element */
        $element=$this->getRepository(Element::class)->findOneBy(array('id'=>$element_id1));
        /** @var Rule $rule */
        $rule=$this->getRepository(Rule::class)->findOneBy(array('id'=>$rule_id));
        if (!$rule) {
            return $this->createNotFoundException();
        }
        if (!$elements2 and !$element) {
            $this->manager->remove($rule);
            $this->manager->flush();
            return $this->redirectToRoute('homepage');
        }
        if ($element) {
            array_push($elements2,$element->getId());
        }



        if (count($elements2)>1) {
            $rule->setCaption($description);
            //удаляем старые связи элементов с редактируемым правилом
            /** @var Element $elem */
            foreach ($rule->getElements() as $elem){
                    $rule->removeElement($elem);
            }
            //сохраняем удаление
            $this->manager->persist($rule);
            $this->manager->flush();
            //добавляем новые связи
            foreach ($elements2 as $element_id2){
                /** @var Element $element2 */
                $element2=$this->getRepository(Element::class)->findOneBy(array('id'=>$element_id2));
                if ($element2) {
                    $rule->addElement($element2);
                }
            }
            $this->manager->persist($rule);
            $this->manager->flush();
        }
        else {
            if (!$element) {
                $element=$this->getRepository(Element::class)->findOneBy(array('id'=>$elements2[0]));
            }
            $element->setDescription($element->getDescription().PHP_EOL.'* '.$description);
            $this->manager->remove($rule);
            $this->manager->persist($element);
            $this->manager->flush();
        }

        if ($element_id1) {
            return $this->redirectToRoute('oneelement',
                array('typealias'=>$element->getTypeElement()->getAlias(), 'elementalias'=>$element->getAlias()));
        }
        else {
            if (count($elements2)>1){
                $gets=array('element_id1'=>$elements2[0],'element_id2'=>$elements2[1]);
            }
            else {
                $gets=array();
            }
            return $this->redirectToRoute('homepage',$gets);
        }



    }

    /**
     * @Route("/admin/editelement", methods={"GET","HEAD"}, name="editelement", priority="2")
     */
    public function editElement()
    {
        $element_id1=$this->request->get('element_id');
        $description=$this->request->get('description');


        /** @var Element $element */
        $element=$this->getRepository(Element::class)->findOneBy(array('id'=>$element_id1));
        $element->setDescription($description);
        $this->manager->persist($element);
        $this->manager->flush();

        return $this->redirectToRoute('oneelement',
            array('typealias'=>$element->getTypeElement()->getAlias(), 'elementalias'=>$element->getAlias()));

    }





}