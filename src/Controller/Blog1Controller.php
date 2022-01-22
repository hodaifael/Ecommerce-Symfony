<?php

namespace App\Controller;
use App\Form\EntityFormType;
use App\Entity\Produit;
use App\Entity\Cmdclient;
use App\Entity\Client;
use App\Entity\Compte;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;

class Blog1Controller extends AbstractController
{
   
  
     /**
     * @Route("/shop", name="blog")
     */
    public function all(): Response
    {
        $nom="hodaifa";
        return $this->render('blog1/shop.html.twig',[ 'name'=>$nom]);
    }


      
     /**
     * @Route("/ ", name="blog1")
     */
    public function home(): Response
    {
        $nom="hodaifa";
        return $this->render('blog1/index.html.twig');
    }

     /**
     * @Route("/form ", name="add_product")
     */
    public function form(Request $request): Response
    {
        
        $produit= new Produit;
        $form = $this->createForm(ProduitFormType::class,$produit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $produit=$form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();
        }
        return $this->render("blog1/form.html.twig", [
            "form_title" => "Ajouter un produit",
            "form_product" => $form->createView(),
        ]);
    }
    /**
     * @Route("/products", name="products")
     */
    public function products()
    {
        $products = $this->getDoctrine()->getRepository(Produit::class)->findAll();

        return $this->render("blog1/products.html.twig", [
            "products" => $products,
        ]);
    }

/**
 * @Route("/cart", name="cart")
 */
public function cart(SessionInterface $session): Response
{

    $panier=$session->get('panier',[]);

    $panierWithData=[];
    foreach ($panier as $id=>$quantity){
        $panierWithData[]=[
            'product'=>$this->getDoctrine()->getRepository(Produit::class)->find($id),
            'quantity'=>$quantity
        ];
    }
    $total=0;

    foreach($panierWithData as $item){
        $totalItem = $item['product']->getPu()*$item['quantity'];
        $total+=$totalItem;
    }

    return $this->render("blog1/cart.html.twig", [
        "items" => $panierWithData,
        'total' => $total
    ]);
    
}


/**
 * @Route("/cart/remove/{id}", name="cart_remove")
 */
public function removeCart(int $id ,SessionInterface $session): Response
{

    $panier=$session->get('panier',[]);

   if(!empty($panier[$id])){
       unset($panier[$id]);
   }

   $session->set('panier',$panier);
    return $this->redirectToRoute("cart");
    
}

/**
 * @Route("/product/{id}", name="product")
 */
public function product(int $id): Response
{
    $product = $this->getDoctrine()->getRepository(Produit::class)->find($id);

    return $this->render("blog1/product.html.twig", [
        "product" => $product,
    ]);
}


/**
 * @Route("/delete-product/{id}", name="delete_product")
 */
public function deleteProduct(int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $product = $entityManager->getRepository(Produit::class)->find($id);
    $entityManager->remove($product);
    $entityManager->flush();

    return $this->redirectToRoute("products");
    
}





/**
 * @Route("/products/{id}", name="addcart")
 */
public function addToCart(int $id): Response
{
    $product = $this->getDoctrine()->getRepository(Produit::class)->find($id);

    return $this->render("blog1/product.html.twig", [
        "product" => $product,
    ]);
}

/**
 * @Route("/panier/{id}", name="panier")
 */
public function panier(int $id,SessionInterface $session): Response
{

    $panier=$session->get('panier',[]);
    if(!empty($panier[$id])){
        $panier[$id]++;   
    }else{
        $panier[$id]=1;
    }
    $session->set('panier',$panier);
    return $this->redirectToRoute("products");
    
}
/**
 * @Route("/check", name="check")
 */

public function check(SessionInterface $session ,Request $request, EntityManagerInterface $manager)
{
    $cmd=new Cmdclient(); 
    $panier=$session->get('panier',[]);

    $panierWithData=[];
    foreach ($panier as $id=>$quantity){
        $panierWithData[]=[
            'product'=>$this->getDoctrine()->getRepository(Produit::class)->find($id),
            'quantity'=>$quantity
        ];

    }
    $total=0;

    foreach($panierWithData as $item){
        $totalItem = $item['product']->getPu()*$item['quantity'];
        $total+=$totalItem;
    }
    $client=new Client();

    $form = $this->createFormBuilder($client)
                 ->add('fname')
                 ->add('lname')
                 ->getForm();
    $form->handleRequest($request);
    $fortunes = $this->getDoctrine()->getRepository(Client::class)->findbymax();
    foreach($fortunes as $item ){
        $x=$item["idc"][0];
        $s=(int)++$x;
        $client->setIdcl($s);

    }
    
    if($form->isSubmitted() && $form->isValid()){
        $manager->persist($client);
        $manager->flush();
 
        $fname = $form->get('fname')->getData();
        $fortunesPrinted = $this->getDoctrine()->getRepository(Client::class)->countNumberPrintedForCategory($fname);
    
        foreach($fortunesPrinted as $item )
        {
            $x=$item["idcl"][0];
            $cmd->setIdclient((int)$x);
            foreach ($panier as $id=>$quantity)
            {
                $cmd->setIdproduit($id);
                $cmd->setQt((int)$quantity);
                $manager->persist($cmd);
                $manager->flush(); 
            }
        }

    
    }
    return $this->render("blog1/checkout.html.twig", [
        "formclient" => $form->createView(),
        "items" => $panierWithData,
        'total' => $total
    ]);
}

     /**
     * @Route("/register", name="register")
     */
    public function register(SessionInterface $session ,Request $request, EntityManagerInterface $manager): Response
    {
        $compte=new Compte();

        $form = $this->createFormBuilder($compte)
                    ->add('user')
                    ->add('pass')
                    ->getForm();
        $form->handleRequest($request);
        $compte->setRole("client");
        
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($compte);
            $manager->flush();
        }
        return $this->render('blog1/register.html.twig', [
            "formcmp" => $form->createView()
        ]);

    }

     /**
     * @Route("/login", name="login")
     */
    public function login(SessionInterface $session ,Request $request, EntityManagerInterface $manager): Response
    {
        $compte=new Compte();

        $form = $this->createFormBuilder($compte)
                    ->add('user')
                    ->add('pass')
                    ->getForm();
        $form->handleRequest($request);
        
        $fortunesPrinted = $this->getDoctrine()->getRepository(Compte::class)->findcompte();
        foreach($fortunesPrinted as $item )
        {
            $x=$item["role"][0];
            $cmd->setIdclient((int)$x);
            foreach ($panier as $id=>$quantity)
            {
                $cmd->setIdproduit($id);
                $cmd->setQt((int)$quantity);
                $manager->persist($cmd);
                $manager->flush(); 
            }
        }
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($compte);
            $manager->flush();
        }
        return $this->render('blog1/register.html.twig', [
            "formlog" => $form->createView()
        ]);

    }
    /**
     * @Route("/cmdclient", name="cmdclient")
     */
    public function cmdclient()
    {
        $Clients = $this->getDoctrine()->getRepository(Client::class)->findAll();

        return $this->render("blog1/Cmdclient.html.twig", [
            "Clients" => $Clients,
        ]);
    }

     /**
     * @Route("/singlecmdclient/{id}", name="singlecmdclient")
     */
    public function singlecmdclient(int $id)
    {   
        $panierWithData=[];    
        $fortunesPrinted = $this->getDoctrine()->getRepository(Cmdclient::class)->selectcmdclient($id);
        foreach($fortunesPrinted as $item )
        {
            $x=$item["idproduit"][0];
            $y=$item["qt"][0];
            $panierWithData[]=[
                'product'=>$this->getDoctrine()->getRepository(Produit::class)->find($x),
                'quantity'=>$y
            ];
    
        }
        return $this->render("blog1/singlecmdclient.html.twig", [
            "items" => $panierWithData
        ]);
    }
     /**
     * @Route("/products/categorie/{name}", name="search")
     */
    public function search(string $name)
    {
        $products = $this->getDoctrine()->getRepository(Produit::class)->findbyname($name);
        return $this->render("blog1/categorie.html.twig", [
            "products" => $products,
        ]);
    }
    /**
     * @Route("/productPers", name="productPers")
     */
    public function productPers()
    {
        return $this->render("blog1/produitPersonaliser.html.twig");
    }


}