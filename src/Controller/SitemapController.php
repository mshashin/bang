<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use App\Entity\Element;
use App\Entity\Rule;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapController extends InitializableController
{

    //карта постоянных ссылок для поисковика
    /**
     * @Route("/sitemap_main.{_format}", name="sitemap_main", requirements={"_format" = "xml"}, priority="2")
     */
    public function mainsitemapAction()
    {
        $urls = array();
        $hostname = $this->request->getHost();
        // домашняя страница
        $urls[] = array('loc' => $this->get('router')->generate('homepage',array(),UrlGeneratorInterface::ABSOLUTE_URL), 'changefreq' => 'hourly', 'priority' => '1.0');

        //страницы с элементами игры
        $elements=$this->getRepository(Element::class)
            ->createQueryBuilder('e')
            ->select('e.alias as ealias, t.alias as talias')
            ->leftJoin('e.typeElement','t')
            ->orderBy('talias')
            ->addOrderBy('ealias')
            ->getQuery()->getArrayResult();

        foreach ($elements as $element) {
            $urls[] = array('loc' => $this->get('router')->generate('oneelement',
                array('typealias' => $element['talias'], 'elementalias'=>$element['ealias']), UrlGeneratorInterface::ABSOLUTE_URL),'changefreq' => 'weekly', 'priority' => '0.5');
        }

        return $this->render('sitemaps/sitemap_template.xml.twig', array('urls' => $urls, 'hostname' => ''));
    }

    //индексовая карта сайта
    /**
     * @Route("/sitemap.{_format}", name="index_sitemap", requirements={"_format" = "xml"}, priority="2")
     */
    public function indexsitemapAction()
    {
        $urls = array();
        //сколько правил в 1 xml
        $pagination=$this->getPagination();
        //считаем сколько раз у нас будет по $pagination файлов
        $count=$this->getRepository(Rule::class)->count(array());

        $count=(floor($count/$pagination) + ($count % $pagination > 0 ? 1 : 0));

        //для каждой кучки правил генерим свою xml
        for ($i=0; $i<$count; $i++) {
            $urls[] = array('loc' => $this->get('router')->generate('dinamic_sitemap',
                array('i' => $i,'_format'=>'xml'), UrlGeneratorInterface::ABSOLUTE_URL));
        }

        return $this->render('sitemaps/sitemap_index.xml.twig', array('urls' => $urls));
    }


    //карта сайта для поисковика
    /**
     * @Route("/sitemap{i}.{_format}", name="dinamic_sitemap", requirements={"_format" = "xml"}, priority="2")

     */
    public function sitemapAction($i)
    {
        $urls = array();
        $hostname = $this->request->getHost();
        $pagination=$this->getPagination();
        //для каждого правила
        $rules=$this->getRepository(Rule::class)->createQueryBuilder('r')
            ->setFirstResult($i*$pagination)->setMaxResults($pagination)
            ->getQuery()->getResult();

        if ($rules) {
            /** @var Rule $rule */
            foreach ($rules as $rule) {
                $elements=$rule->getElements();
                if (count($elements)>1) {
                    $urls[] = array('loc' => $this->get('router')->generate('homepage',
                        array('element_id1' => $elements[0]->getId(),'element_id2' => $elements[1]->getId()), UrlGeneratorInterface::ABSOLUTE_URL),
                        'priority' => '1',
                        'changefreq'=>'weekly');
                }
            }
            return $this->render('sitemaps/sitemap_template.xml.twig',array('urls' => $urls, 'hostname' => $hostname));
            //return array('urls' => $urls, 'hostname' => $hostname);
        }
        else {
            throw $this->createNotFoundException();
        }

    }

    public function getPagination() {
        return 2000;
    }


}