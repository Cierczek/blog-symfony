<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use BlogBundle\Entity\Tag;
use BlogBundle\Form\TagType;

class TagController extends Controller {

	private $session;

	public function __construct() {
		$this->session = new Session();
	}
	
	public function indexAction(){
		$autheticationUtils = $this->get("security.authentication_utils");
		$error = $autheticationUtils->getLastAuthenticationError();
		$em= $this->getDoctrine()->getEntityManager();
		$tag_repo = $em->getRepository("BlogBundle:Tag");
		$tags=$tag_repo->findAll();
		
		
		return $this->render("BlogBundle:Tag:index.html.twig", ([
			"errors" => $error,
			"tags" => $tags
		]));
		
	}
	
	public function addAction(Request $request) {
		
		$tag = new Tag();
		$form = $this->createForm(TagType::class, $tag);
		

		$form->handleRequest($request);

		if ($form->isSubmitted()) {
			if ($form->isValid()) {

				$em = $this->getDoctrine()->getEntityManager();
				$tag_repo = $em->getRepository("BlogBundle:Tag");
				$tag = $tag_repo->findBy((["name" => $form->get("name")->getData()]));

				if (count($tag) == 0) {
					$tag = new Tag();
					$tag->setName($form->get("name")->getData());
					$tag->setDescription($form->get("description")->getData());

					$em->persist($tag);
					$flush = $em->flush();

					if ($flush == null) {
						$status = "La etiqueta se ha añadido correctamente";
						$this->session->getFlashBag()->add("success", $status);
					}
				}
				else {
					$status = "La etiqueta ya existe";
					$this->session->getFlashBag()->add("danger", $status);
				}
				
				return $this->redirectToRoute("blog_index_tag");
			}
			else {
				$validator = $this->get('validator');
				$error = $validator->validate($tag);
			}
		}

		return $this->render("BlogBundle:Tag:add.html.twig", ([
		
			"form" => $form->createView()
		]));
	}
	
	public function deleteAction($id){
		
		$em = $this->getDoctrine()->getEntityManager();
		$tag_repo = $em->getRepository("BlogBundle:Tag");
		$tag = $tag_repo->find($id);
		
		if(count(($tag->getEntryTag()))== 0){
			$em->remove($tag);
			$em->flush();	
		}
		
		return $this->redirectToRoute("blog_index_tag");
	}

}
