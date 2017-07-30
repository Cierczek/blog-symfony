<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use BlogBundle\Entity\Category;
use BlogBundle\Form\CategoryType;

class CategoryController extends Controller {

	private $session;

	public function __construct() {
		$this->session = new Session();
	}
	
	public function indexAction(){
		
		$em= $this->getDoctrine()->getEntityManager();
		$category_repo = $em->getRepository("BlogBundle:Category");
		$categories=$category_repo->findAll();
		
		$autheticationUtils = $this->get("security.authentication_utils");
		$error = $autheticationUtils->getLastAuthenticationError();
		
		
		return $this->render("BlogBundle:Category:index.html.twig", ([
			"errors" => $error,
			"categories" => $categories
		]));
		
	}
	
	public function addAction(Request $request) {
		
		$category = new Category();
		$form = $this->createForm(CategoryType::class, $category);
		

		$form->handleRequest($request);

		if ($form->isSubmitted()) {
			if ($form->isValid()) {

				$em = $this->getDoctrine()->getEntityManager();
				$category_repo = $em->getRepository("BlogBundle:Category");
				$category = $category_repo->findBy((["name" => $form->get("name")->getData()]));

				if (count($category) == 0) {
					$category = new Category();
					$category->setName($form->get("name")->getData());
					$category->setDescription($form->get("description")->getData());

					
					$em->persist($category);
					$flush = $em->flush();

					if ($flush == null) {
						$status = "La categoria se ha añadido correctamente";
						$this->session->getFlashBag()->add("success", $status);
					}
				}
				else {
					$status = "La categoria ya existe";
					$this->session->getFlashBag()->add("danger", $status);
				}
				
				return $this->redirectToRoute("blog_index_categories");
			}
			else {
				$validator = $this->get('validator');
				$error = $validator->validate($category);
			}
		}

		return $this->render("BlogBundle:Category:add.html.twig", ([
		
			"form" => $form->createView()
		]));
	}
	
	public function deleteAction($id){
		
		$em = $this->getDoctrine()->getEntityManager();
		$category_repo = $em->getRepository("BlogBundle:Category");
		$category = $category_repo->find($id);
		
		if(count(($category->getEntries()))== 0){
			$em->remove($category);
			$em->flush();	
		}
		
		return $this->redirectToRoute("blog_index_categories");
	}
	
	public function editAction(Request $request, $id){
		
		$em = $this->getDoctrine()->getEntityManager();
		$category_repo = $em->getRepository("BlogBundle:Category");
		$category = $category_repo->find($id);
		
		$form = $this->createForm(CategoryType::class, $category);
		
		$form->handleRequest($request);
		
		if ($form->isSubmitted()) {
			if ($form->isValid()) {

		
				$category_repo = $em->getRepository("BlogBundle:Category");
				$category = $category_repo->findBy((["name" => $form->get("name")->getData()]));

				if (count($category) == 0) {

					$category->setName($form->get("name")->getData());
					$category->setDescription($form->get("description")->getData());

				
					$em->persist($category);
					$flush = $em->flush();

					if ($flush == null) {
						$status = "La categoria se ha editado correctamente";
						$this->session->getFlashBag()->add("success", $status);
					}
				}
				else {
					$status = "La categoria ya existe";
					$this->session->getFlashBag()->add("danger", $status);
				}
				
				return $this->redirectToRoute("blog_index_categories");
			}
			else {
				$validator = $this->get('validator');
				$error = $validator->validate($category);
			}
		}
		
		return $this->render("BlogBundle:Category:edit.html.twig", ([
		
			"form" => $form->createView()
		]));
	}

}
