<?php

require_once("DB_Connection.php");
require_once("dao/UserDAO.php");
require_once("dao/RoleDAO.php");
require_once("dao/CategoryDAO.php");
require_once("dao/BookDAO.php");
require_once("dao/AuthorDAO.php");
require_once("dao/PublisherDAO.php");
require_once("dao/ConditionDAO.php");
require_once("dao/FormatDAO.php");
require_once("dao/RatingDAO.php");
require_once("dao/OrderDAO.php");
require_once("dao/ReviewDAO.php");
require_once("dao/BookOfferDAO.php");
require_once("dao/CartDAO.php");
require_once("dao/WishListDAO.php");
require_once("dao/PaymentMethodDAO.php");
require_once("dao/ShippingMethodDAO.php");
require_once("dao/OfferDAO.php");
require_once("dao/OrderItemDAO.php");



class DataLayer{


    private ?DB_Connection $DBConnection;
    private ?PDO $conn;
    


    private UserDAO $userDAO;
    private RoleDAO $roleDAO;
    private CategoryDAO $categoryDAO;
    private BookDAO $bookDAO;
    private AuthorDAO $authorDAO;
    private PublisherDAO $publisherDAO;
    private ConditionDAO $conditionDAO;
    private FormatDAO $formatDAO;
    private RatingDAO $ratingDAO;
    private OrderDAO $orderDAO;
    private ReviewDAO $reviewDAO;
    private BookOfferDAO $bookOfferDAO;
    private CartDAO $cartDAO;
    private WishListDAO $wishListDAO;
    private PaymentMethodDAO $paymentMethodDAO;
    private ShippingMethodDAO $shippingMethodDAO;
    private OfferDAO $offerDAO;
    private OrderItemDAO $orderItemDAO;


    public function __construct(DB_Connection $DBConnection) {
        $this->DBConnection = $DBConnection;
        $this->conn = $DBConnection->getConnection();
        $this->init();
    }

    public function getDBConnection(): ?DB_Connection{
        return $this->DBConnection;
    }

    public function getConnection(): ?PDO{
        return $this->DBConnection->getConnection();
    }

    
    public function init(){
        $this->userDAO = new UserDAO($this);
        $this->roleDAO = new RoleDAO($this);
        $this->categoryDAO = new CategoryDAO($this);
        $this->bookDAO = new BookDAO($this);
    }

    public function getCategoryDAO(): CategoryDAO{
        return $this->categoryDAO;
    }

    public function getBookDAO(): BookDAO{
        return $this->bookDAO;
    }

    public function getUserDAO(): UserDAO{
        return $this->userDAO;
    }

    public function getRoleDAO(): RoleDAO{
        return $this->roleDAO;
    }

    public function getAuthorDAO(): AuthorDAO{
        return $this->authorDAO;
    }

    public function getPublisherDAO(): PublisherDAO{
        return $this->publisherDAO;
    }

    public function getConditionDAO(): ConditionDAO{
        return $this->conditionDAO;
    }

    public function getFormatDAO(): FormatDAO{
        return $this->formatDAO;
    }

    public function getRatingDAO(): RatingDAO{
        return $this->ratingDAO;
    }

    public function getOrderDAO(): OrderDAO{
        return $this->orderDAO;
    }

    public function getReviewDAO(): ReviewDAO{
        return $this->reviewDAO;
    }

    public function getBookOfferDAO(): BookOfferDAO{
        return $this->bookOfferDAO;
    }

    public function getCartDAO(): CartDAO{
        return $this->cartDAO;
    }

    public function getWishListDAO(): WishListDAO{
        return $this->wishListDAO;
    }

    public function getPaymentMethodDAO(): PaymentMethodDAO{
        return $this->paymentMethodDAO;
    }

    public function getShippingMethodDAO(): ShippingMethodDAO{
        return $this->shippingMethodDAO;
    }

    public function getOfferDAO(): OfferDAO{
        return $this->offerDAO;
    }

    public function getOrderItemDAO(): OrderItemDAO{
        return $this->orderItemDAO;
    }



}
?>