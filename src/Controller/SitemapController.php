<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapController extends InitializableController
{

    //карта постоянных ссылок для поисковика
    /**
     * @Route("/sitemap_main.{_format}", name="sitemap_main", requirements={"_format" = "xml"})
     */
    public function mainsitemapAction()
    {
        /*$urls = array();
        $hostname = $this->request->getHost();
        // домашняя страница
        $urls[] = array('loc' => $this->get('router')->generate('homepage',array(),UrlGeneratorInterface::ABSOLUTE_URL), 'changefreq' => 'hourly', 'priority' => '1.0');

        //сбываемость снов сегодня
        $urls[] = array('loc' => $this->get('router')->generate('today',array(),UrlGeneratorInterface::ABSOLUTE_URL), 'changefreq' => 'daily', 'priority' => '1.0');

        //страницы с буквами
        $letters=$this->getRepository(Letter::class)->createQueryBuilder('l')->orderBy('l.caption')->getQuery()->getResult();
        /** @var Letter $letter */
        /*foreach ($letters as $letter) {
            $urls[] = array('loc' => $this->get('router')->generate('wordsbyletter',
                array('alias' => $letter->getAlias()), UrlGeneratorInterface::ABSOLUTE_URL),'changefreq' => 'weekly', 'priority' => '0.5');
        }
*/
        return $this->render('sitemaps/sitemap_template.xml.twig', array('urls' => array(), 'hostname' => ''));
    }

    //индексовая карта сайта
    /**
     * @Route("/sitemap.{_format}", name="index_sitemap", requirements={"_format" = "xml"})
     */
   /* public function indexsitemapAction()
    {
        $urls = array();
        //сколько слов в 1 xml
        $pagination=$this->getPagination();
        //считаем сколько раз у нас будет по $pagination файлов
        $count=$this->getRepository(Word::class)->count(array('active'=>1));

        $count=(floor($count/$pagination) + ($count % $pagination > 0 ? 1 : 0));

        //для каждой кучки слов генерим свою xml
        for ($i=0; $i<$count; $i++) {
            $urls[] = array('loc' => $this->get('router')->generate('dinamic_sitemap',
                array('i' => $i,'_format'=>'xml'), UrlGeneratorInterface::ABSOLUTE_URL));
        }

        return $this->render('sitemaps/sitemap_index.xml.twig', array('urls' => $urls));
    }


    //карта сайта для поисковика
    /**
     * @Route("/sitemap{i}.{_format}", name="dinamic_sitemap", requirements={"_format" = "xml"})

     */
    /*public function sitemapAction($i)
    {
        $urls = array();
        $hostname = $this->request->getHost();
        $pagination=$this->getPagination();
        //для каждой страницы каждого файла
        $words=$this->getRepository(Word::class)->createQueryBuilder('w')
            ->select('w.alias')
            ->where('w.active = 1')
            ->orderBy('w.caption')
            ->setFirstResult($i*$pagination)->setMaxResults($pagination)
            ->getQuery()->getArrayResult();


        if ($words) {
            foreach ($words as $word) {
                $urls[] = array('loc' => $this->get('router')->generate('oneword',
                    array('alias' => $word['alias']), UrlGeneratorInterface::ABSOLUTE_URL),
                    'priority' => '1',
                    'changefreq'=>'weekly');
            }
            return $this->render('sitemaps/sitemap_template.xml.twig',array('urls' => $urls, 'hostname' => $hostname));
            //return array('urls' => $urls, 'hostname' => $hostname);
        }
        else {
            throw $this->createNotFoundException();
        }

    }*/

    public function getPagination() {
        return 2000;
    }


}