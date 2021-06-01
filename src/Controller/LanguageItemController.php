<?php


namespace App\Controller;


use App\Entity\Items;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LanguageItemController extends AbstractController
{
    /**
     * @Route("/items/add", methods={"POST"})
     */
    public function addItems(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent(), true);

        $newItems = new Items();
        $newItems->setNameEn($data["name_en"]);
        $newItems->setNameFi($data["name_fi"]);
        $newItems->setImg($data["img"]);

        $entityManager->persist($newItems);

        $entityManager->flush();

        return new Response('adding new items..' . $newItems->getId());
    }

    /**
     * @Route("/items/all", methods={"GET"})
     */
    public function getAllItems() {
        $items = $this->getDoctrine()->getRepository(Items::class)->findAll();

        $response = [];

        foreach($items as $item) {
            $response[] = array(
                'id'=>$item->getId(),
                'name_en'=>$item->getNameEn(),
                'name_fi'=>$item->getNameFi(),
                'img'=>$item->getImg()
            );
        }

        return $this->json($response);
    }

    /**
     * @Route("/items/chunks/0")
     */
    public function getChunks() {
        $items = $this->getDoctrine()->getRepository(Items::class)->findAll();

        $response = [];



        foreach($items as $item) {
            $response[]= array(
                'id'=>$item->getId(),
                'name_en'=>$item->getNameEn(),
                'name_fi'=>$item->getNameFi(),
                'img'=>$item->getImg()
            );
        }

        $chunks = array_chunk($response, 5);

        return $this->json($chunks[0]);


    }

    /**
     * @Route("/items/chunks/1")
     */
    public function getChunks() {
        $items = $this->getDoctrine()->getRepository(Items::class)->findAll();

        $response = [];



        foreach($items as $item) {
            $response[]= array(
                'id'=>$item->getId(),
                'name_en'=>$item->getNameEn(),
                'name_fi'=>$item->getNameFi(),
                'img'=>$item->getImg()
            );
        }

        $chunks = array_chunk($response, 5);

        return $this->json($chunks[1]);


    }

    /**
     * @Route("/items/find/{id}")
     */
    public function findItem($id) {
        $item = $this->getDoctrine()->getRepository(Items::class)->find($id);

//        $response = [];

        if (!$item) {
            throw $this->createNotFoundException(
                'No item was found with the id: ' . $id
            );
        } else {
            return $this->json(
                [  'id'=>$item->getId(),
                  'name_en'=>$item->getNameEn(),
                   'name_fi'=>$item->getNameFi(),
                  'img'=>$item->getImg(),]
//                $response[] = array(
//                    'id'=>$item->getId(),
//                    'name_en'=>$item->getNameEn(),
//                    'name_fi'=>$item->getNameFi(),
//                    'img'=>$item->getImg()
//                )
            );
        }
    }

    /**
     * @Route("/items/remove/{id}")
     */
    public function removeItems($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $item = $this->getDoctrine()->getRepository(Items::class)->find($id);

        if(!$item) {
            throw $this->createNotFoundException(
                'No Items were found with id: ' . $id
            );
        }else {
            $entityManager->remove($item);
            $entityManager->flush();
            return $this->json([
                'message'=> 'Removed an item with id: ' . $id
            ]);
        }

    }

}