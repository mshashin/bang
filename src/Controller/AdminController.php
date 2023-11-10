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
        if (!$element) {
            return $this->createNotFoundException();
        }
        $rule= new Rule();
        $rule->setCaption($description);
        $rule->addElement($element);
        foreach ($elements2 as $element_id2){
            /** @var Element $element2 */
            $element2=$this->getRepository(Element::class)->findOneBy(array('id'=>$element_id2));
            if ($element2) {
                $rule->addElement($element2);
            }
        }
        $this->manager->persist($rule);
        $this->manager->flush();
        return $this->redirectToRoute('oneelement',
           array('typealias'=>$element->getTypeElement()->getAlias(), 'elementalias'=>$element->getAlias()));

    }

    /**
     * @Route("/admin/editrule", methods={"GET","HEAD"}, name="editrule", priority="2")
     */
    public function editRule()
    {
        $element_id1=$this->request->get('element_id');
        $description=$this->request->get('description');
        $rule_id=$this->request->get('rule_id');

        /** @var Element $element */
        $element=$this->getRepository(Element::class)->findOneBy(array('id'=>$element_id1));
        /** @var Rule $rule */
        $rule=$this->getRepository(Rule::class)->findOneBy(array('id'=>$rule_id));
        if (!$element or !$rule) {
            return $this->createNotFoundException();
        }

        $rule->setCaption($description);
        $this->manager->persist($rule);
        $this->manager->flush();

        return $this->redirectToRoute('oneelement',
            array('typealias'=>$element->getTypeElement()->getAlias(), 'elementalias'=>$element->getAlias()));

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