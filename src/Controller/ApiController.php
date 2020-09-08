<?php

namespace App\Controller;

use App\Repository\CatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/v1/cats/search", name="api_cats_search", methods={"GET"})
     */
    public function catsSearch(Request $request, CatRepository $catRepository)
    {
        $keyword = $request->query->get('keyword');
        $foundCats = $catRepository->search($keyword);

        //c'est moche, mais bon
        //je souhaite que l'API puisse renvoyer l'Url de détail pour chaque chat
        //mais je n'ai pas accès au Router depuis les entités
        foreach($foundCats as &$foundCat){
            $detailUrl = $this->generateUrl('cat_detail', ['id' => $foundCat->getId()]);
            $foundCat->setUrl($detailUrl);
        }

        //permet de convertir la liste des chatons en json (sinon les objets sont vides)
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        //convertie en json, en omettant l'attribut dateCreated
        $jsonContent = $serializer->serialize(['cats' => $foundCats], 'json', ['ignored_attributes' => ['dateCreated']]);

        //faut faire comme ça quand les données sont déjà converties en json !
        return JsonResponse::fromJsonString($jsonContent);
    }

    /**
     * @Route("/api/v1/cart/add", name="api_add_to_cart", methods={"POST"})
     */
    public function addToCart(Request $request)
    {
        //requête à la bdd
        $json = $request->getContent();
        $data = json_decode($json);
        $catId = $data->id;

        return new JsonResponse([
            "status" => "ok",
            "data" => [

            ]
        ]);
    }
}
