<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use App\Entity\DreamBook;
use App\Entity\DreamCalendar;
use App\Entity\Interpretation;
use App\Entity\Letter;
use App\Entity\Word;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ApiController extends InitializableController
{

    //карта постоянных ссылок для поисковика
    /**
     * @Route("/saveimage", name="saveimage")
     */
    public function saveimageAction()
    {

        if (isset($_POST['word_id'])) {
            $filename= __DIR__ . '/../../public/images/words/'.$_POST['filename'];
            move_uploaded_file($_FILES['upfile']['tmp_name'], $filename);
            $this->manager->getRepository(Word::class)->createQueryBuilder('w')
                ->update('w.image = :image')
                ->where('w.id = :word_id')
                ->setParameters(array('image'=>$_POST['filename'],'word_id'=>$_POST['word_id']))
                ->getQuery()->execute();
        }
        /** @var Word $word */
        $word=$this->manager->getRepository(Word::class)->createQueryBuilder('w')
            ->where('w.image IS NULL')
            ->getQuery()->getOneOrNullResult();
        if ($word) {
            $response = new JsonResponse();
            $response->setContent(json_encode(array('word_id'=>$word->getId(), 'alias'=>$word->getAlias(), 'caption'=>$word->getCaption())));
            return $response;
        }
        else {
            return false;
        }

    }




}